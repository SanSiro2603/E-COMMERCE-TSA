<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Payment;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
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
                'success'   => "Pembayaran #{$orderNumber} BERHASIL! Produk segera kami proses.",
                'pending'   => "Pembayaran #{$orderNumber} sedang DIPROSES. Kami akan konfirmasi secepatnya.",
                'error'     => "Pembayaran #{$orderNumber} GAGAL. Silakan coba metode lain.",
                'cancelled' => "Pembayaran #{$orderNumber} DIBATALKAN.",
                default     => "Status pembayaran #{$orderNumber} diperbarui.",
            };

            $flashType = in_array($midtransStatus, ['success', 'pending']) ? 'success' : 'error';
            $flashMessage = $message;

            if (in_array($midtransStatus, ['success', 'pending'])) {
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

        return view('pembeli.pesanan.index', compact(
            'orders',
            'statuses',
            'highlightedOrder'
        ));
    }

    public function show($id)
{
    $order = Order::with([
            'items.product.category',
            'payment',
            'shipment',
            'address'  // TAMBAHKAN INI!
        ])
        ->where('user_id', Auth::id())
        ->findOrFail($id);

    return view('pembeli.pesanan.show', compact('order'));
}
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
            'courier'    => 'required|in:jne,pos,tiki,jnt,sicepat,anteraja',
        ]);

        try {
            DB::beginTransaction();

            $carts = Cart::with('product')->where('user_id', Auth::id())->get();
            if ($carts->isEmpty()) throw new \Exception('Keranjang kosong');

            $address = Auth::user()->addresses()->findOrFail($validated['address_id']);

            $totalAmount = $carts->sum('subtotal');
            $weight = $carts->sum(fn($c) => ($c->product->weight ?? 1000) * $c->quantity);
            $weightKg = ceil($weight / 1000);

            // === HITUNG ONGKIR BERDASARKAN PROVINSI ===
            $ongkirMap = [
                '31' => 15000, // Lampung
                '1'  => 40000, '2'  => 40000, '3'  => 40000, '4'  => 40000, '5'  => 40000, '6'  => 40000, // Jawa
                '32' => 30000, '33' => 35000, '34' => 40000, '35' => 35000, '36' => 40000, '37' => 35000, '38' => 30000, '39' => 30000, // Sumatera
                '61' => 70000, '62' => 75000, '63' => 75000, '64' => 80000, '65' => 85000, // Kalimantan
                '71' => 70000, '72' => 75000, '73' => 75000, '74' => 80000, '75' => 80000, '76' => 75000, // Sulawesi
                '51' => 60000, '52' => 90000, '53' => 95000, // Bali & NTT
                '81' => 120000, '82' => 125000, '91' => 130000, '92' => 130000 // Maluku & Papua
            ];

            $baseCost = $ongkirMap[$address->province_id] ?? 60000;
            $shippingCost = $baseCost + ($weightKg > 1 ? ($weightKg - 1) * 10000 : 0);
            $grandTotal = $totalAmount + $shippingCost;


            // 1. VALIDASI STOK EKSTRA KETAT (PENTING!)
            // Kita lock baris database untuk mencegah Race Condition (rebutan stok)
            foreach ($carts as $cart) {
                $product = Product::lockForUpdate()->find($cart->product_id);
                
                if (!$product) {
                    throw new \Exception("Produk tidak ditemukan.");
                }

                if ($product->stock < $cart->quantity) {
                    throw new \Exception("Stok produk '{$product->name}' tidak mencukupi (Sisa: {$product->stock}). Silakan update keranjang.");
                }
            }

            // 2. JIKA AMAN, BUAT ORDER
            $order = Order::create([
                'user_id'           => Auth::id(),
                'order_number'      => Order::generateOrderNumber(),
                'subtotal'          => $totalAmount,
                'shipping_cost'     => $shippingCost,
                'grand_total'       => $grandTotal,
                'status'            => 'pending',
                'address_id'        => $address->id,
                'recipient_name'    => $address->recipient_name,
                'recipient_phone'   => $address->recipient_phone,
                'province'          => $address->province_name,
                'province_id'       => $address->province_id,
                'city'              => $address->city_type . ' ' . $address->city_name,
                'city_id'           => $address->city_id,
                'postal_code'       => $address->postal_code,
                'shipping_address'  => $address->full_address,
                'courier'           => $validated['courier'],
            ]);

            // 3. KURANGI STOK & BUAT ORDER ITEMS
            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity'   => $cart->quantity,
                    'price'      => $cart->product->price,
                    'subtotal'   => $cart->subtotal,
                ]);

                // Kurangi stok (Aman karena sudah divalidasi & dilock di atas)
                $product = Product::find($cart->product_id);
                $product->decrement('stock', $cart->quantity);
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
            'courier'    => 'required|in:jne,pos,tiki,jnt,sicepat,anteraja',
        ]);

        try {
            DB::beginTransaction();

            $address = Auth::user()->addresses()->findOrFail($validated['address_id']);

            // Hitung ulang ongkir
            $weight = $order->items->sum(fn($i) => ($i->product->weight ?? 1000) * $i->quantity);
            $weightKg = ceil($weight / 1000);

            $ongkirMap = [
                '31' => 15000, '1' => 40000, '2' => 40000, '3' => 40000, '4' => 40000, '5' => 40000, '6' => 40000,
                '32' => 30000, '33' => 35000, '34' => 40000, '35' => 35000, '36' => 40000, '37' => 35000, '38' => 30000, '39' => 30000,
                '61' => 70000, '62' => 75000, '63' => 75000, '64' => 80000, '65' => 85000,
                '71' => 70000, '72' => 75000, '73' => 75000, '74' => 80000, '75' => 80000, '76' => 75000,
                '51' => 60000, '52' => 90000, '53' => 95000,
                '81' => 120000, '82' => 125000, '91' => 130000, '92' => 130000
            ];

            $baseCost = $ongkirMap[$address->province_id] ?? 60000;
            $shippingCost = $baseCost + ($weightKg > 1 ? ($weightKg - 1) * 10000 : 0);
            $grandTotal = $order->subtotal + $shippingCost;

            $order->update([
                'address_id'        => $address->id,
                'recipient_name'    => $address->recipient_name,
                'recipient_phone'   => $address->recipient_phone,
                'province'          => $address->province_name,
                'province_id'       => $address->province_id,
                'city'              => $address->city_type . ' ' . $address->city_name,
                'city_id'           => $address->city_id,
                'postal_code'       => $address->postal_code,
                'shipping_address'  => $address->full_address,
                'courier'           => $validated['courier'],
                'shipping_cost'     => $shippingCost,
                'grand_total'       => $grandTotal,
            ]);

            DB::commit();

            return redirect()->route('pembeli.pesanan.index')
                ->with('success', 'Pesanan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
        try {
            $order = Order::where('user_id', Auth::id())->findOrFail($id);

            if (!$order->canBeCancelled()) {
                return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses atau dikirim.');
            }

            DB::transaction(function () use ($order) {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }

                $order->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                ]);
            });

            return back()->with('success', "Pesanan #{$order->order_number} berhasil dibatalkan. Stok produk telah dikembalikan.");

        } catch (\Exception $e) {
            DB::rollBack();
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
                'status'   => 'completed',
                'paid_at'  => $order->paid_at ?? now(),
            ]);

            return back()->with('success', 'Pesanan telah diselesaikan. Terima kasih!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyelesaikan pesanan: ' . $e->getMessage());
        }
    }
}