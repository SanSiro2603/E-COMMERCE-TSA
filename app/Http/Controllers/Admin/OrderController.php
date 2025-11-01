<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product'])->latest();

        // Filter status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Pencarian
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        $orders = $query->paginate(10);

        $statuses = [
            'all' => 'Semua Status',
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'payment', 'shipment']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,processing,shipped,completed,cancelled',
            'courier' => 'nullable|string|max:50',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        // Update shipment jika status shipped
        if ($request->status === 'shipped' && $order->shipment) {
            $order->shipment->update([
                'courier' => $request->courier,
                'tracking_number' => $request->tracking_number,
                'status' => 'shipped',
                'shipped_at' => now(),
            ]);
        }

        // Jika completed
        if ($request->status === 'completed' && $order->shipment) {
            $order->shipment->update(['status' => 'delivered', 'delivered_at' => now()]);
        }

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}