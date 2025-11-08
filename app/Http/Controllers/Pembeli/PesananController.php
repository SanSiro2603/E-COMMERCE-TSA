<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
   public function index(Request $request)
{
    $userId = Auth::id();

    // DETEKSI RETURN DARI MIDTRANS (SNAP REDIRECT)
    $midtransStatus = $request->query('status'); // success, pending, error, cancelled
    $orderNumber = $request->query('order');

    $highlightedOrder = null;
    $flashMessage = null;
    $flashType = 'success';

    if ($midtransStatus && $orderNumber) {
        $message = match($midtransStatus) {
            'success'   => "Pembayaran #{$orderNumber} BERHASIL! Produk segera kami proses.",
            'pending'   => "Pembayaran #{$orderNumber} sedang DIPROSES. Kami akan konfirmasi secepatnya.",
            'error'     => "Pembayaran #{$orderNumber} GAGAL. Silakan coba metode lain.",
            'cancelled' => "Pembayaran #{$orderNumber} DIBATALKAN.",
            default     => "Status pembayaran #{$orderNumber} diperbarui.",
        };

        $flashType = in_array($midtransStatus, ['success', 'pending']) ? 'success' : 'error';
        $flashMessage = $message;

        // AUTO SET FILTER + HIGHLIGHT ORDER
        if (in_array($midtransStatus, ['success', 'pending'])) {
            $request->merge(['status' => $midtransStatus === 'success' ? 'paid' : 'pending']);
        }

        // Cari order untuk highlight
        $highlightedOrder = Order::where('order_number', $orderNumber)
            ->where('user_id', $userId)
            ->first();
    }

    // QUERY PESANAN
    $query = Order::with(['items.product'])
        ->where('user_id', $userId)
        ->latest();

    // FILTER STATUS
    if ($request->filled('status') && $request->status !== 'all') {
        $query->where('status', $request->status);
    }

    $orders = $query->paginate(10)->withQueryString();

    // FLASH MESSAGE KE BLADE
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

    return view('pembeli.pesanan.index', compact(
        'orders',
        'statuses',
        'highlightedOrder'
    ));
}

   public function show($id)
{
    $order = Order::with(['items.product.category', 'payment', 'shipment'])
        ->where('user_id', Auth::id())
        ->findOrFail($id);

    return view('pembeli.pesanan.show', compact('order'));
}


    public function checkout()
    {
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        if ($carts->isEmpty()) {
            return redirect()->route('pembeli.keranjang.index')
                ->with('error', 'Keranjang kosong');
        }

        $subtotal = $carts->sum('subtotal');
        $totalWeight = $carts->sum(fn($c) => ($c->product->weight ?? 1000) * $c->quantity);

        return view('pembeli.pesanan.checkout', compact('carts', 'subtotal', 'totalWeight'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_name'    => 'required|string|max:255',
            'recipient_phone'   => 'required|string|regex:/^08[0-9]{8,11}$/|max:15',
            'province_id'       => 'required',
            'province_name'     => 'required|string',
            'city_id'           => 'required',
            'city_name'         => 'required|string',
            'city_type'         => 'required|in:Kota,Kabupaten',
            'postal_code'       => 'nullable|string|max:10',
            'shipping_address'  => 'required|string|max:500',
            'courier'           => 'required|in:jne,pos,tiki,jnt,sicepat,anteraja',
        ]);

        try {
            DB::beginTransaction();

            $carts = Cart::with('product')->where('user_id', Auth::id())->get();
            if ($carts->isEmpty()) throw new \Exception('Keranjang kosong');

            $totalAmount = $carts->sum('subtotal');

            // === ONGKIR MANUAL (SERVER) ===
            $weight = $carts->sum(fn($c) => ($c->product->weight ?? 1000) * $c->quantity);
            $weightKg = ceil($weight / 1000);

            $zones = [
                'Lampung' => 3000,
                'near'    => 20000,
                'far'     => 50000,
                'very_far'=> 90000
            ];

            $provinceZones = [
                'Lampung' => 'Lampung',
                'Sumatera Selatan' => 'near', 'Bengkulu' => 'near', 'Jambi' => 'near',
                'Sumatera Utara' => 'near', 'Aceh' => 'near', 'Riau' => 'near',
                'Kepulauan Riau' => 'near', 'Bangka Belitung' => 'near',
                'DKI Jakarta' => 'far', 'Jawa Barat' => 'far', 'Banten' => 'far',
                'Jawa Tengah' => 'far', 'DI Yogyakarta' => 'far', 'Jawa Timur' => 'far',
                'Kalimantan Barat' => 'far', 'Kalimantan Tengah' => 'far',
                'Kalimantan Selatan' => 'far', 'Kalimantan Timur' => 'far',
                'Kalimantan Utara' => 'far', 'Sulawesi Selatan' => 'far',
                'Nusa Tenggara Barat' => 'very_far', 'Nusa Tenggara Timur' => 'very_far',
                'Maluku' => 'very_far', 'Maluku Utara' => 'very_far',
                'Papua' => 'very_far', 'Papua Barat' => 'very_far',
            ];

            $zone = $provinceZones[$validated['province_name']] ?? 'far';
            $ratePerKg = $zone === 'Lampung' ? 3000 : ($zones[$zone] ?? 50000);
            $shippingCost = $ratePerKg * $weightKg;

            $grandTotal = $totalAmount + $shippingCost;

            $order = Order::create([
                'user_id'           => Auth::id(),
                'order_number'      => Order::generateOrderNumber(),
                'subtotal'          => $totalAmount,
                'shipping_cost'     => $shippingCost,
                'grand_total'       => $grandTotal,
                'status'            => 'pending',
                'recipient_name'    => $validated['recipient_name'],
                'recipient_phone'   => $validated['recipient_phone'],
                'province'          => $validated['province_name'],
                'province_id'       => $validated['province_id'],
                'city'              => $validated['city_type'] . ' ' . $validated['city_name'],
                'city_id'           => $validated['city_id'],
                'postal_code'       => $validated['postal_code'],
                'shipping_address'  => $validated['shipping_address'],
                'courier'           => $validated['courier'],
            ]);

            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity'   => $cart->quantity,
                    'price'      => $cart->product->price,
                    'subtotal'   => $cart->subtotal,
                ]);
                $cart->product->decrement('stock', $cart->quantity);
            }

            Cart::where('user_id', Auth::id())->delete();
            DB::commit();

            return redirect()->route('pembeli.payment.show', $order)
                ->with('success', 'Pesanan berhasil dibuat! Silakan bayar.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }


   public function edit($id)
{
    $order = Order::with('items.product')
        ->where('user_id', Auth::id())
        ->findOrFail($id);

    if ($order->status !== 'pending') {
        return redirect()->route('pembeli.pesanan.index')
            ->with('error', 'Pesanan hanya bisa diedit jika status menunggu pembayaran.');
    }

    // Pastikan data lama tersedia di form (terutama untuk select RajaOngkir)
    return view('pembeli.pesanan.edit', compact('order'));
}

public function update(Request $request, $id)
{
    $order = Order::where('user_id', Auth::id())
        ->findOrFail($id);

    if ($order->status !== 'pending') {
        return redirect()->route('pembeli.pesanan.index')
            ->with('error', 'Pesanan hanya bisa diedit jika status menunggu pembayaran.');
    }

    // VALIDASI SAMA PERSIS SEPERTI CHECKOUT
    $validated = $request->validate([
        'province_id'       => 'required|string',
        'province_name'     => 'required|string',
        'city_id'           => 'required|string',
        'city_name'         => 'required|string',
        'city_type'         => 'required|in:Kota,Kabupaten',
        'postal_code'       => 'nullable|string|max:10',
        'shipping_address'  => 'required|string|max:500',
        'courier'           => 'required|in:JNE,JNT,SiCepat,Anteraja',
    ], [
        'province_id.required'      => 'Provinsi wajib dipilih',
        'city_id.required'          => 'Kota/Kabupaten wajib dipilih',
        'city_type.required'        => 'Tipe kota wajib diisi',
        'city_type.in'              => 'Tipe kota harus Kota atau Kabupaten',
        'shipping_address.required' => 'Alamat lengkap wajib diisi',
        'courier.required'          => 'Kurir wajib dipilih',
    ]);

    try {
        DB::beginTransaction();

        // Update semua field alamat
        $order->update([
            'province'          => $validated['province_name'],
            'province_id'       => $validated['province_id'],
            'city'              => $validated['city_type'] . ' ' . $validated['city_name'],
            'city_id'           => $validated['city_id'],
            'postal_code'       => $validated['postal_code'],
            'shipping_address'  => $validated['shipping_address'],
            'courier'           => $validated['courier'],
        ]);

        DB::commit();

        return redirect()->route('pembeli.pesanan.index')
            ->with('success', 'Alamat pengiriman berhasil diperbarui!');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal memperbarui pesanan: ' . $e->getMessage());
    }
}

    public function cancel($id)
{
    try {
        // === 1. CARI ORDER + CEK PEMILIK ===
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        // === 2. CEK APAKAH BISA DIBATALKAN ===
        if (!$order->canBeCancelled()) {
            return back()
                ->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses atau dikirim.');
        }

        // === 3. PROSES PEMBATALAN DALAM TRANSAKSI ===
        DB::transaction(function () use ($order) {
            // Restore stok produk
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            // Update status
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

        });

        // === 4. FLASH MESSAGE + REDIRECT ===
        return back()->with('success', "Pesanan #{$order->order_number} berhasil dibatalkan. Stok produk telah dikembalikan.");

    } catch (\Exception $e) {
        DB::rollBack();

        \Log::error('Gagal batalkan pesanan ID ' . $id, [
            'user_id' => Auth::id(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return back()->with('error', 'Gagal membatalkan pesanan. Silakan coba lagi atau hubungi admin.');
    }
}

    public function complete($id)
    {
        try {
            $order = Order::where('user_id', Auth::id())->findOrFail($id);

            if (!$order->canBeCompleted()) {
                return redirect()->back()
                    ->with('error', 'Pesanan belum dapat diselesaikan');
            }

            $order->update([
                'status'   => 'completed',
                'paid_at'  => $order->paid_at ?? now(),
            ]);

            return redirect()->back()
                ->with('success', 'Pesanan telah diselesaikan. Terima kasih!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menyelesaikan pesanan: ' . $e->getMessage());
        }
    }
}