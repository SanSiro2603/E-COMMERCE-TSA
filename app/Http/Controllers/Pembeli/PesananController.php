<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Payment;
use App\Models\SystemSetting;
use App\Services\BiteshipService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\MidtransService;

class PesananController extends Controller
{
    protected $midtrans;
    protected $biteship;
    protected $orderService;

    public function __construct(MidtransService $midtrans, BiteshipService $biteship, OrderService $orderService)
    {
        $this->midtrans = $midtrans;
        $this->biteship = $biteship;
        $this->orderService = $orderService;
    }
    public function index(Request $request)
    {
        $userId = Auth::id();

        // DETEKSI RETURN DARI MIDTRANS
        $midtransStatus = $request->query('status');
        $orderNumber = $request->query('order');

        $highlightedOrder = null;
        $flashMessage = null;
        $flashType = 'success';

        if ($midtransStatus && $orderNumber) {
            $message = match ($midtransStatus) {
                    'success' => "Pembayaran #{$orderNumber} BERHASIL! Produk segera kami proses.",
                    'pending' => "Pembayaran #{$orderNumber} sedang DIPROSES. Kami akan konfirmasi secepatnya.",
                    'error' => "Pembayaran #{$orderNumber} GAGAL. Silakan coba metode lain.",
                    'cancelled' => "Pembayaran #{$orderNumber} DIBATALKAN.",
                    default => "Status pembayaran #{$orderNumber} diperbarui.",
                };

            $flashType = in_array($midtransStatus, ['success', 'pending']) ? 'success' : 'error';
            $flashMessage = $message;

            if (in_array($midtransStatus, ['success', 'pending'])) {
                // Sync status ke database jika sukses/pending
                try {
                    $order = Order::where('order_number', $orderNumber)->first();
                    if ($order && $order->status === 'pending') {
                        $statusResult = $this->midtrans->checkStatus($order->order_number);
                        if ($statusResult['success']) {
                            $status = $statusResult['data']->transaction_status;
                            if (in_array($status, ['capture', 'settlement'])) {
                                $order->update([
                                    'status' => 'paid',
                                    'paid_at' => now()
                                ]);

                                // Update juga di table payments
                                if ($order->payment) {
                                    $order->payment->update(['transaction_status' => $status]);
                                }
                            }
                        }
                    }
                }
                catch (\Exception $e) {
                    \Log::error("Status sync failed for order {$orderNumber}: " . $e->getMessage());
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
            'all' => 'Semua Pesanan',
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'processing' => 'Sedang Diproses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return view('pembeli.pesanan.index', compact(
            'orders',
            'statuses',
            'highlightedOrder'
        ));
    }

    public function show($id)    {
        $order = Order::with([
            'items.product.category',
            'payment',
            'address'
        ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('pembeli.pesanan.show', compact('order'));    }
    // === CHECKOUT: PILIH ALAMAT TERSIMPAN + KURIR ===
    public function checkout()
    {
        // --- TAMBAHAN BARU ---
        $isStoreOpen = SystemSetting::where('key', 'shopping_enabled')->value('value');
        if ($isStoreOpen !== '1') {
            return redirect()->route('pembeli.keranjang.index')
                ->with('error', 'Maaf, toko sedang tutup. Fitur checkout sementara dinonaktifkan.');
        }

        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        if ($carts->isEmpty()) {
            return redirect()->route('pembeli.keranjang.index')
                ->with('error', 'Keranjang kosong');
        }

        $addresses = Auth::user()->addresses()->get();
        if ($addresses->isEmpty()) {
            return redirect()->route('pembeli.alamat.create')
                ->with('error', 'Silakan tambah alamat terlebih dahulu');
        }

        $subtotal = $carts->sum('subtotal');
        $totalWeight = $carts->sum(fn($c) => ($c->product->weight ?? 1000) * $c->quantity);

        return view('pembeli.pesanan.checkout', compact('carts', 'subtotal', 'totalWeight', 'addresses'));
    }

    // === AJAX: CEK ONGKIR BITESIP ===
    public function checkShippingCost(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'courier' => 'required|string',
            'weight' => 'required|numeric'
        ]);

        $address = Auth::user()->addresses()->findOrFail($request->address_id);

        $payload = [
            'destination_postal_code' => $address->postal_code ?? '',
            'destination_area_id' => '',
            'couriers' => $request->courier,
            'weight' => $request->weight,
            'items_value' => Cart::where('user_id', Auth::id())->get()->sum('subtotal')
        ];

        $response = $this->biteship->getRates($payload);

        if (!$response['success']) {
            return response()->json(['success' => false, 'message' => $response['message']]);
        }

        $rates = $response['pricing'] ?? [];
        if (empty($rates)) {
            return response()->json(['success' => false, 'message' => 'Layanan kurir tidak tersedia untuk rute ini.']);
        }

        // Ambil harga terendah dari hasil kurir yang sama jika ada beberapa jenis layanan (karena user cuma pilih kurir company di dropdown Checkout)
        $selectedCourier = collect($rates)->sortBy('price')->first();

        return response()->json([
            'success' => true,
            'service' => strtoupper($selectedCourier['company']) . ' ' . strtoupper($selectedCourier['type']),
            'cost' => $selectedCourier['price'],
            'courier_type' => $selectedCourier['type']
        ]);
    }

    // === STORE: BUAT PESANAN DARI ALAMAT TERPILIH ===
    public function store(Request $request)
    {
        // --- TAMBAHAN BARU ---
        $isStoreOpen = SystemSetting::where('key', 'shopping_enabled')->value('value');
        if ($isStoreOpen !== '1') {
            return redirect()->back()
                ->with('error', 'Transaksi DITOLAK: Toko sedang tutup.');
        }

        $validated = $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'courier' => 'required|in:jne,pos,tiki,jnt,sicepat,anteraja',
        ]);

        try {
            $carts = Cart::with('product')->where('user_id', Auth::id())->get();
            if ($carts->isEmpty())
                throw new \Exception('Keranjang kosong');

            $address = Auth::user()->addresses()->findOrFail($validated['address_id']);

            // Delegate logic to OrderService
            $order = $this->orderService->createOrder(Auth::id(), $carts, $address, $validated['courier']);

            return redirect()->route('pembeli.payment.show', $order)
                ->with('success', 'Pesanan berhasil dibuat! Silakan bayar.');

        }
        catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // === EDIT: GANTI ALAMAT / KURIR SEBELUM BAYAR ===
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
            'address_id' => 'required|exists:addresses,id',
            'courier' => 'required|in:jne,pos,tiki,jnt,sicepat,anteraja',
        ]);

        try {
            $address = Auth::user()->addresses()->findOrFail($validated['address_id']);

            // Delegate logic to Service
            $this->orderService->updateShippingDetails($order, $address, $validated['courier']);

            return redirect()->route('pembeli.pesanan.index')
                ->with('success', 'Pesanan berhasil diperbarui!');

        }
        catch (\Exception $e) {
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

            $item = $order->items()->findOrFail($itemId);

            $result = $this->orderService->removeOrderItem($order, $item);

            if ($result === 'cancel') {
                return redirect()->route('pembeli.pesanan.index')
                    ->with('success', 'Pesanan dibatalkan karena semua produk dihapus.');
            }

            return back()->with('success', 'Produk berhasil dihapus dari pesanan.');

        }
        catch (\Exception $e) {
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

        }
        catch (\Exception $e) {
            \Log::error('Gagal batalkan pesanan ID ' . $id, [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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
                'status' => 'completed',
                'paid_at' => $order->paid_at ?? now(),
            ]);

            return back()->with('success', 'Pesanan telah diselesaikan. Terima kasih!');

        }
        catch (\Exception $e) {
            return back()->with('error', 'Gagal menyelesaikan pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Tracking pengiriman Biteship untuk pembeli (AJAX)
     */
    public function trackBiteship(Order $order)
    {
        // Pastikan hanya pemilik pesanan yang bisa akses
        if ($order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Tidak diizinkan.'], 403);
        }

        if (!$order->biteship_order_id) {
            return response()->json(['success' => false, 'message' => 'Pengiriman belum dibuat.']);
        }

        return response()->json($this->biteship->trackOrder($order->biteship_order_id));
    }
}