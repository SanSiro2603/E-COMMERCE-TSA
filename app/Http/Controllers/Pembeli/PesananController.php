<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
   public function index(Request $request)
{
    $query = Order::with(['items.product'])
        ->where('user_id', Auth::id())
        ->latest();

    if ($request->filled('status') && $request->status !== 'all') {
        $query->where('status', $request->status);
    }

    $orders = $query->paginate(10);

    $statuses = [
        'all' => 'Semua',
        'pending' => 'Menunggu Pembayaran',
        'paid' => 'Sudah Dibayar',
        'processing' => 'Diproses',
        'shipped' => 'Dikirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    // Ambil cart user
    $carts = Cart::with('product')->where('user_id', Auth::id())->get();

    // Hitung total dan shipping
    $total = $carts->sum(function ($cart) {
        return $cart->subtotal;
    });
    $shippingCost = 15000; // default, bisa disesuaikan

    $grandTotal = $total + $shippingCost;

    return view('pembeli.pesanan.index', compact('orders', 'statuses', 'carts', 'total', 'shippingCost', 'grandTotal'));
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
        $carts = Cart::with(['product'])
            ->where('user_id', Auth::id())
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('pembeli.keranjang')
                ->with('error', 'Keranjang Anda kosong');
        }

        // Check stock availability
        foreach ($carts as $cart) {
            if (!$cart->product || !$cart->product->is_active) {
                return redirect()->route('pembeli.keranjang')
                    ->with('error', 'Produk ' . ($cart->product->name ?? 'tidak tersedia') . ' sudah tidak tersedia');
            }

            if ($cart->product->stock < $cart->quantity) {
                return redirect()->route('pembeli.keranjang')
                    ->with('error', 'Stok produk ' . $cart->product->name . ' tidak mencukupi');
            }
        }

        $total = $carts->sum('subtotal');
        $shippingCost = 15000; // Default shipping cost
        $grandTotal = $total + $shippingCost;

        return view('pembeli.pesanan.checkout', compact('carts', 'total', 'shippingCost', 'grandTotal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string',
            'courier' => 'nullable|string|max:50',
        ], [
            'shipping_address.required' => 'Alamat pengiriman wajib diisi',
        ]);

        try {
            DB::beginTransaction();

            // Get cart items
            $carts = Cart::with(['product'])
                ->where('user_id', Auth::id())
                ->get();

            if ($carts->isEmpty()) {
                throw new \Exception('Keranjang kosong');
            }

            // Check stock and calculate total
            $totalAmount = 0;
            foreach ($carts as $cart) {
                if (!$cart->product || !$cart->product->is_active) {
                    throw new \Exception('Produk tidak tersedia: ' . ($cart->product->name ?? 'Unknown'));
                }

                if ($cart->product->stock < $cart->quantity) {
                    throw new \Exception('Stok tidak mencukupi untuk: ' . $cart->product->name);
                }

                $totalAmount += $cart->subtotal;
            }

            $shippingCost = 15000;
            $grandTotal = $totalAmount + $shippingCost;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'total_amount' => $totalAmount,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'status' => 'pending',
                'shipping_address' => $validated['shipping_address'],
                'courier' => $validated['courier'] ?? 'JNE',
            ]);

            // Create order items and update stock
            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                    'subtotal' => $cart->subtotal,
                ]);

                // Update product stock
                $cart->product->decrement('stock', $cart->quantity);
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('pembeli.pesanan.show', $order)
                ->with('success', 'Pesanan berhasil dibuat! Order #' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
        try {
            $order = Order::where('user_id', Auth::id())->findOrFail($id);

            if (!$order->canBeCancelled()) {
                return redirect()->back()
                    ->with('error', 'Pesanan tidak dapat dibatalkan pada status ini');
            }

            DB::beginTransaction();

            // Restore stock
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            $order->update(['status' => 'cancelled']);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Pesanan berhasil dibatalkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
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
                'status' => 'completed',
                'paid_at' => $order->paid_at ?? now(),
            ]);

            return redirect()->back()
                ->with('success', 'Pesanan telah diselesaikan. Terima kasih!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menyelesaikan pesanan: ' . $e->getMessage());
        }
    }
}