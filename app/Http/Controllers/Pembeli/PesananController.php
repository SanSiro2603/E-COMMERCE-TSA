<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Payment;
use App\Models\SystemSetting;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\MidtransService;
use App\Services\BiteshipService;

class PesananController extends Controller
{
    protected $midtrans;
    protected $biteship;
    protected $orderService;

    public function __construct(MidtransService $midtrans, BiteshipService $biteship, OrderService $orderService)
    {
        $this->midtrans     = $midtrans;
        $this->biteship     = $biteship;
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $userId = Auth::id();

        $midtransStatus = $request->query('status');
        $orderNumber    = $request->query('order');

        $highlightedOrder = null;
        $flashMessage     = null;
        $flashType        = 'success';

        if ($midtransStatus && $orderNumber) {
            $message = match ($midtransStatus) {
                'success'   => "Pembayaran #{$orderNumber} BERHASIL! Produk segera kami proses.",
                'pending'   => "Pembayaran #{$orderNumber} sedang DIPROSES. Kami akan konfirmasi secepatnya.",
                'error'     => "Pembayaran #{$orderNumber} GAGAL. Silakan coba metode lain.",
                'cancelled' => "Pembayaran #{$orderNumber} DIBATALKAN.",
                default     => "Status pembayaran #{$orderNumber} diperbarui.",
            };

            $flashType    = in_array($midtransStatus, ['success', 'pending']) ? 'success' : 'error';
            $flashMessage = $message;

            if (in_array($midtransStatus, ['success', 'pending'])) {
                try {
                    $order = Order::where('order_number', $orderNumber)->first();
                    if ($order && $order->status === 'pending') {
                        $statusResult = $this->midtrans->checkStatus($order->order_number);
                        if ($statusResult['success']) {
                            $status = $statusResult['data']->transaction_status;
                            if (in_array($status, ['capture', 'settlement'])) {
                                $order->update(['status' => 'paid', 'paid_at' => now()]);
                                if ($order->payment) {
                                    $order->payment->update(['transaction_status' => $status]);
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Status sync failed for order {$orderNumber}: " . $e->getMessage());
                }
                $request->merge(['status' => $midtransStatus === 'success' ? 'paid' : 'pending']);
            }

            $highlightedOrder = Order::where('order_number', $orderNumber)
                ->where('user_id', $userId)
                ->first();
        }

        $query = Order::with(['items.product'])
            ->where('user_id', $userId)
            ->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        if ($flashMessage) {
            session()->flash($flashType, $flashMessage);
        }

        $statuses = [
            'all'        => 'Semua Pesanan',
            'pending'    => 'Menunggu Pembayaran',
            'paid'       => 'Sudah Dibayar',
            'processing' => 'Sedang Diproses',
            'shipped'    => 'Dikirim',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
        ];

        return view('pembeli.pesanan.index', compact('orders', 'statuses', 'highlightedOrder'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product.category', 'payment', 'address'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('pembeli.pesanan.show', compact('order'));
    }

    // =========================================================================
    // BUY NOW — langsung checkout tanpa menyentuh keranjang
    // Dipanggil via POST dari halaman detail produk (show.blade.php)
    // Menyimpan data sementara ke session('buy_now') lalu redirect ke checkout
    // =========================================================================
    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product  = Product::where('is_active', true)->findOrFail($request->product_id);
        $quantity = (int) $request->quantity;

        // Validasi stok
        if ($product->stock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi. Tersedia: ' . $product->stock,
            ], 422);
        }

        // Cek toko buka
        $isStoreOpen = SystemSetting::where('key', 'shopping_enabled')->value('value');
        if ($isStoreOpen !== '1') {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, toko sedang tutup.',
            ], 403);
        }

        // Simpan ke session — TIDAK masuk keranjang
        session([
            'buy_now' => [
                'product_id' => $product->id,
                'quantity'   => $quantity,
                'price'      => $product->price,
                'subtotal'   => $product->price * $quantity,
                'weight'     => ($product->weight ?? 1000) * $quantity,
                'product'    => [
                    'id'     => $product->id,
                    'name'   => $product->name,
                    'image'  => $product->image,
                    'price'  => $product->price,
                    'weight' => $product->weight ?? 1000,
                    'unit'   => $product->unit ?? 'ekor',
                    'stock'  => $product->stock,
                ],
            ],
        ]);

        return response()->json([
            'success'      => true,
            'redirect_url' => route('pembeli.pesanan.checkout'),
        ]);
    }

    // =========================================================================
    // CHECKOUT — cek session buy_now dulu, kalau tidak ada pakai keranjang
    // =========================================================================
    public function checkout()
    {
        $isStoreOpen = SystemSetting::where('key', 'shopping_enabled')->value('value');
        if ($isStoreOpen !== '1') {
            return redirect()->route('pembeli.keranjang.index')
                ->with('error', 'Maaf, toko sedang tutup. Fitur checkout sementara dinonaktifkan.');
        }

        $addresses = Auth::user()->addresses()->get();
        if ($addresses->isEmpty()) {
            return redirect()->route('pembeli.alamat.create')
                ->with('error', 'Silakan tambah alamat terlebih dahulu');
        }

        // ── MODE BUY NOW ──────────────────────────────────────────────────────
        $buyNow = session('buy_now');

        if ($buyNow) {
            // Pastikan produk masih aktif dan stok masih cukup
            $product = Product::where('is_active', true)->find($buyNow['product_id']);

            if (!$product || $product->stock < $buyNow['quantity']) {
                session()->forget('buy_now');
                return redirect()->route('pembeli.produk.index')
                    ->with('error', 'Produk tidak tersedia atau stok habis.');
            }

            // Buat object serupa Cart agar blade checkout bisa dipakai ulang
            $fakeCarts = collect([
                (object) [
                    'id'         => null,
                    'product_id' => $buyNow['product_id'],
                    'quantity'   => $buyNow['quantity'],
                    'subtotal'   => $buyNow['subtotal'],
                    'product'    => (object) $buyNow['product'],
                ]
            ]);

            $subtotal      = $buyNow['subtotal'];
            $totalWeight   = $buyNow['weight'];
            $isBuyNow      = true;
            $shoppingEnabled = true;

            return view('pembeli.pesanan.checkout', compact(
                'fakeCarts', 'subtotal', 'totalWeight', 'addresses', 'shoppingEnabled', 'isBuyNow'
            ))->with('carts', $fakeCarts);
        }

        // ── MODE KERANJANG NORMAL ─────────────────────────────────────────────
        $selectedCartIds = session('checkout_cart_ids', []);

        if (empty($selectedCartIds)) {
            return redirect()->route('pembeli.keranjang.index')
                ->with('error', 'Silakan pilih item yang ingin di-checkout terlebih dahulu.');
        }

        $carts = Cart::with('product')
            ->where('user_id', Auth::id())
            ->whereIn('id', $selectedCartIds)
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('pembeli.keranjang.index')
                ->with('error', 'Item yang dipilih tidak ditemukan di keranjang.');
        }

        $subtotal        = $carts->sum('subtotal');
        $totalWeight     = $carts->sum(fn($c) => ($c->product->weight ?? 1000) * $c->quantity);
        $isBuyNow        = false;
        $shoppingEnabled = $isStoreOpen === '1';

        return view('pembeli.pesanan.checkout', compact(
            'carts', 'subtotal', 'totalWeight', 'addresses', 'shoppingEnabled', 'isBuyNow'
        ));
    }

    // =========================================================================
    // AJAX: CEK ONGKIR BITESHIP
    // =========================================================================
    public function checkShippingCost(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'courier'    => 'required|string',
            'weight'     => 'required|numeric|min:1',
        ]);

        $address = Auth::user()->addresses()->findOrFail($request->address_id);

        if (!$address->postal_code) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat tidak memiliki kode pos yang valid. Silakan perbarui alamat Anda.',
            ], 422);
        }

        try {
            // Nilai barang: cek buy_now dulu, lalu keranjang
            $buyNow     = session('buy_now');
            $itemsValue = $buyNow
                ? $buyNow['subtotal']
                : Cart::where('user_id', Auth::id())
                    ->whereIn('id', session('checkout_cart_ids', []))
                    ->sum('subtotal');

            if ($itemsValue <= 0) $itemsValue = 100000;

            $payload = [
                'destination_postal_code' => $address->postal_code,
                'couriers'                => strtolower($request->courier),
                'weight'                  => (int) $request->weight,
                'items_value'             => $itemsValue,
            ];

            $response = $this->biteship->getRates($payload);

            if (!$response['success']) {
                return response()->json(['success' => false, 'message' => $response['message']]);
            }

            $services = [];
            foreach ($response['pricing'] as $pricing) {
                $services[] = [
                    'courier'      => strtolower($pricing['company'] ?? ''),
                    'courier_name' => strtoupper($pricing['company'] ?? ''),
                    'service'      => $pricing['type'] ?? 'REG',
                    'description'  => $pricing['description'] ?? '',
                    'price'        => (int) ($pricing['price'] ?? 0),
                    'etd'          => $pricing['duration'] ?? '-',
                ];
            }

            if (empty($services)) {
                return response()->json(['success' => false, 'message' => 'Tidak ada layanan kurir tersedia untuk rute ini.']);
            }

            usort($services, fn($a, $b) => $a['price'] - $b['price']);

            return response()->json(['success' => true, 'services' => $services]);

        } catch (\Exception $e) {
            Log::error('Biteship checkShippingCost exception: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
    }

    // =========================================================================
    // STORE — buat pesanan, cek buy_now dulu lalu keranjang
    // =========================================================================
    public function store(Request $request)
    {
        $isStoreOpen = SystemSetting::where('key', 'shopping_enabled')->value('value');
        if ($isStoreOpen !== '1') {
            return redirect()->back()->with('error', 'Transaksi DITOLAK: Toko sedang tutup.');
        }

        $validated = $request->validate([
            'address_id'      => 'required|exists:addresses,id',
            'courier'         => 'required|string',
            'courier_service' => 'required|string',
            'shipping_cost'   => 'required|numeric|min:0',
        ]);

        try {
            $address = Auth::user()->addresses()->findOrFail($validated['address_id']);

            // ── MODE BUY NOW ──────────────────────────────────────────────────
            $buyNow = session('buy_now');

            if ($buyNow) {
                $product = Product::where('is_active', true)->findOrFail($buyNow['product_id']);

                if ($product->stock < $buyNow['quantity']) {
                    throw new \Exception('Stok tidak mencukupi. Tersedia: ' . $product->stock);
                }

                // Buat koleksi fake Cart agar OrderService bisa dipakai ulang
                $fakeCarts = collect([
                    (object) [
                        'id'         => null,
                        'product_id' => $product->id,
                        'quantity'   => $buyNow['quantity'],
                        'subtotal'   => $buyNow['subtotal'],
                        'product'    => $product,
                    ]
                ]);

                $order = $this->orderService->createOrder(
                    Auth::id(),
                    $fakeCarts,
                    $address,
                    $validated['courier'],
                    $validated['courier_service'],
                    (int) $validated['shipping_cost']
                );

                // Bersihkan session buy_now setelah order dibuat
                session()->forget('buy_now');

                return redirect()->route('pembeli.payment.show', $order)
                    ->with('success', 'Pesanan berhasil dibuat! Silakan bayar.');
            }

            // ── MODE KERANJANG NORMAL ─────────────────────────────────────────
            $selectedCartIds = session('checkout_cart_ids', []);

            if (empty($selectedCartIds)) {
                return redirect()->route('pembeli.keranjang.index')
                    ->with('error', 'Sesi checkout habis. Silakan pilih item kembali.');
            }

            $carts = Cart::with('product')
                ->where('user_id', Auth::id())
                ->whereIn('id', $selectedCartIds)
                ->get();

            if ($carts->isEmpty()) {
                throw new \Exception('Item yang dipilih tidak ditemukan di keranjang');
            }

            $order = $this->orderService->createOrder(
                Auth::id(),
                $carts,
                $address,
                $validated['courier'],
                $validated['courier_service'],
                (int) $validated['shipping_cost']
            );

            session()->forget('checkout_cart_ids');

            return redirect()->route('pembeli.payment.show', $order)
                ->with('success', 'Pesanan berhasil dibuat! Silakan bayar.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // EDIT, UPDATE, REMOVE ITEM, CANCEL, COMPLETE, TRACK — tidak berubah
    // =========================================================================

    public function edit($id)
    {
        $order = Order::with(['items.product', 'address'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect()->route('pembeli.pesanan.index')
                ->with('error', 'Pesanan hanya bisa diedit jika status menunggu pembayaran.');
        }

        $addresses = Auth::user()->addresses()->get();

        return view('pembeli.pesanan.edit', compact('order', 'addresses'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect()->route('pembeli.pesanan.index')
                ->with('error', 'Pesanan hanya bisa diedit jika status menunggu pembayaran.');
        }

        $validated = $request->validate([
            'address_id'      => 'required|exists:addresses,id',
            'courier'         => 'required|string',
            'courier_service' => 'required|string',
            'shipping_cost'   => 'required|numeric|min:0',
        ]);

        try {
            $address = Auth::user()->addresses()->findOrFail($validated['address_id']);

            $this->orderService->updateShippingDetails(
                $order,
                $address,
                $validated['courier'],
                $validated['courier_service'],
                (int) $validated['shipping_cost']
            );

            return redirect()->route('pembeli.pesanan.index')
                ->with('success', 'Pesanan berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function removeItem($orderId, $itemId)
    {
        try {
            $order = Order::where('user_id', Auth::id())->findOrFail($orderId);

            if ($order->status !== 'pending') {
                return back()->with('error', 'Item hanya bisa dihapus jika pesanan belum dibayar.');
            }

            $item   = $order->items()->findOrFail($itemId);
            $result = $this->orderService->removeOrderItem($order, $item);

            if ($result === 'cancel') {
                return redirect()->route('pembeli.pesanan.index')
                    ->with('success', 'Pesanan dibatalkan karena semua produk dihapus.');
            }

            return back()->with('success', 'Produk berhasil dihapus dari pesanan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
        try {
            $order = Order::where('user_id', Auth::id())->findOrFail($id);

            if (!$order->canBeCancelled()) {
                return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses atau dikirim.');
            }

            $this->orderService->cancelOrder($order);

            return back()->with('success', "Pesanan #{$order->order_number} berhasil dibatalkan. Stok produk telah dikembalikan.");

        } catch (\Exception $e) {
            Log::error('Gagal batalkan pesanan ID ' . $id, [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Gagal membatalkan pesanan. Silakan coba lagi.');
        }
    }

    public function complete($id)
    {
        try {
            $order = Order::where('user_id', Auth::id())->findOrFail($id);

            if (!$order->canBeCompleted()) {
                return back()->with('error', 'Pesanan belum dapat diselesaikan');
            }

            $order->update([
                'status'  => 'completed',
                'paid_at' => $order->paid_at ?? now(),
            ]);

            return back()->with('success', 'Pesanan telah diselesaikan. Terima kasih!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyelesaikan pesanan: ' . $e->getMessage());
        }
    }

    public function trackBiteship(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Tidak diizinkan.'], 403);
        }

        if (!$order->biteship_order_id) {
            return response()->json(['success' => false, 'message' => 'Pengiriman belum dibuat.']);
        }

        return response()->json($this->biteship->trackOrder($order->biteship_order_id));
    }
}
