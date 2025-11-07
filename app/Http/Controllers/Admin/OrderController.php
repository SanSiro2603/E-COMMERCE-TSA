<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
   public function index(Request $request)
{
    $statuses = [
        'all' => 'Semua Status',
        'pending' => 'Menunggu Pembayaran',
        'paid' => 'Sudah Dibayar',
        'processing' => 'Diproses',
        'shipped' => 'Dikirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    $query = Order::with(['user', 'items.product'])->latest();

    // Filter by search
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('order_number', 'like', '%' . $search . '%')
              ->orWhereHas('user', function($q) use ($search) {
                  $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
              });
        });
    }

    // Filter by status
    if ($request->filled('status') && $request->status !== 'all') {
        $query->where('status', $request->status);
    }

    $orders = $query->paginate(10);

    // Handle AJAX request (untuk polling real-time)
    if ($request->ajax() || $request->has('ajax')) {
        $allOrdersQuery = Order::with(['user', 'items.product']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $allOrdersQuery->where(function($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }

        $allOrders = $allOrdersQuery->get();

        // Generate table rows HTML
        $html = '';
        if ($orders->count() > 0) {
            foreach ($orders as $order) {
                $html .= $this->generateOrderRow($order);
            }
        } else {
            $html = '
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-6xl mb-3">shopping_bag</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Tidak ada pesanan ditemukan</p>
                            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Coba ubah kata kunci pencarian atau filter</p>
                        </div>
                    </td>
                </tr>
            ';
        }

        // Calculate stats
        $stats = [
            'all' => $allOrders->count(),
            'pending' => $allOrders->where('status', 'pending')->count(),
            'paid' => $allOrders->where('status', 'paid')->count(),
            'processing' => $allOrders->where('status', 'processing')->count(),
            'shipped' => $allOrders->where('status', 'shipped')->count(),
            'completed' => $allOrders->where('status', 'completed')->count(),
        ];

        return response()->json([
            'html' => $html,
            'stats' => $stats,
            'total' => $orders->total(),
        ]);
    }

    return view('admin.orders.index', compact('orders', 'statuses'));
}

   private function generateOrderRow($order)
{
    $statusClasses = [
        'pending' => 'bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400',
        'paid' => 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400',
        'processing' => 'bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400',
        'shipped' => 'bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400',
        'completed' => 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400',
        'cancelled' => 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400',
    ];

    $dotClasses = [
        'pending' => 'bg-yellow-500',
        'paid' => 'bg-blue-500',
        'processing' => 'bg-purple-500',
        'shipped' => 'bg-indigo-500',
        'completed' => 'bg-green-500',
        'cancelled' => 'bg-red-500',
    ];

    $statusClass = $statusClasses[$order->status] ?? 'bg-gray-100 dark:bg-gray-500/20 text-gray-700 dark:text-gray-400';
    $dotClass = $dotClasses[$order->status] ?? 'bg-gray-500';
    $statusLabel = ucfirst(str_replace('_', ' ', $order->status));
    $initial = strtoupper(substr($order->user->name, 0, 1));
    $itemsCount = $order->items->count();

    // HIGHLIGHT BARU DIBAYAR (jika < 2 menit)
    $isNewPaid = $order->status === 'paid' && $order->paid_at && $order->paid_at->diffInMinutes(now()) < 2;

    return '
        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-all ' . ($isNewPaid ? 'ring-2 ring-green-400 ring-opacity-50 animate-pulse' : '') . '">
            <td class="px-6 py-4">
                <a href="' . route('admin.orders.show', $order) . '" 
                   class="text-sm font-semibold text-soft-green hover:text-primary transition-colors">
                    #' . e($order->order_number) . '
                </a>
                ' . ($isNewPaid ? '<span class="ml-2 inline-flex items-center gap-1 px-2 py-0.5 text-xs font-bold text-green-700 bg-green-100 dark:bg-green-500/20 dark:text-green-300 rounded-full animate-bounce">BARU!</span>' : '') . '
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-soft-green to-primary rounded-full flex items-center justify-center text-white font-bold text-sm">
                        ' . $initial . '
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">' . e($order->user->name) . '</p>
                        <p class="text-xs text-gray-500 dark:text-zinc-400">' . e($order->user->email) . '</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <p class="text-sm font-bold text-gray-900 dark:text-white">Rp ' . number_format($order->grand_total, 0, ',', '.') . '</p>
                <p class="text-xs text-gray-500 dark:text-zinc-400">' . $itemsCount . ' item</p>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full ' . $statusClass . '">
                    <span class="w-1.5 h-1.5 rounded-full animate-ping ' . $dotClass . '"></span>
                    ' . $statusLabel . '
                </span>
            </td>
            <td class="px-6 py-4">
                <p class="text-sm text-gray-900 dark:text-white">' . $order->created_at->format('d M Y') . '</p>
                <p class="text-xs text-gray-500 dark:text-zinc-400">' . $order->created_at->format('H:i') . '</p>
            </td>
            <td class="px-6 py-4 text-center">
                <a href="' . route('admin.orders.show', $order) . '" 
                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 rounded-lg text-xs font-medium transition-colors">
                    <span class="material-symbols-outlined text-base">visibility</span>
                    Detail
                </a>
            </td>
        </tr>
    ';
}

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'payment', 'shipment']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
{
    try {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,processing,shipped,completed,cancelled',
            'courier' => 'nullable|string|max:50',
            'tracking_number' => 'nullable|string|max:100',
        ], [
            'status.required' => 'Status pesanan wajib dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
            'courier.max' => 'Nama kurir maksimal 50 karakter.',
            'tracking_number.max' => 'Nomor resi maksimal 100 karakter.',
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
            $order->shipment->update([
                'status' => 'delivered', 
                'delivered_at' => now()
            ]);
        }

        // Update paid_at if status is paid or completed
        if (in_array($request->status, ['paid', 'completed']) && !$order->paid_at) {
            $order->update(['paid_at' => now()]);
        }

        // ✅ Setelah update, langsung kembali ke halaman index
        return redirect()->route('admin.orders.index')
            ->with('success', 'Status pesanan #' . $order->order_number . ' berhasil diperbarui menjadi ' . ucfirst($request->status) . '!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput()
            ->with('error', 'Validasi gagal. Periksa kembali input Anda.');
            
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Gagal memperbarui status pesanan. ' . $e->getMessage());
    }
}

}