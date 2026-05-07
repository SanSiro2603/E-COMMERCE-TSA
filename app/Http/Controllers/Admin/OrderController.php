<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

// Controller pesanan admin — index, show, updateStatus
// View: resources/views/admin/orders/index.blade.php & show.blade.php
class OrderController extends Controller
{
    public function index(Request $request)
    {
        // [+] Tambah status baru di sini jika ada perubahan enum
        $statuses = [
            'all'        => 'Semua Status',
            'pending'    => 'Menunggu Pembayaran',
            'paid'       => 'Sudah Dibayar',
            'processing' => 'Diproses',
            'shipped'    => 'Dikirim',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
        ];

        // [+] Tambah relasi ke with([]) jika perlu tampilkan data dari tabel lain
        $query = Order::with(['user', 'items.product'])->latest();

        // Filter: cari berdasarkan nomor pesanan atau nama/email pembeli
        // [+] Tambah kolom pencarian lain di dalam closure $q
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter: berdasarkan status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // [+] Ganti angka 10 untuk ubah jumlah item per halaman
        $orders = $query->paginate(10)->withQueryString();

        // Query terpisah untuk menghitung stats card (tidak terpengaruh filter status)
        $statsQuery = Order::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $statsQuery->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }

        // [+] Tambah entri baru di $stats jika ada status baru
        $stats = [
            'all'        => $statsQuery->count(),
            'pending'    => (clone $statsQuery)->where('status', 'pending')->count(),
            'paid'       => (clone $statsQuery)->where('status', 'paid')->count(),
            'processing' => (clone $statsQuery)->where('status', 'processing')->count(),
            'shipped'    => (clone $statsQuery)->where('status', 'shipped')->count(),
            'completed'  => (clone $statsQuery)->where('status', 'completed')->count(),
            'cancelled'  => (clone $statsQuery)->where('status', 'cancelled')->count(),
        ];

        // Response AJAX — untuk refresh tabel tanpa reload halaman
        if ($request->ajax() || $request->has('ajax')) {
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

            return response()->json([
                'html'  => $html,
                'stats' => $stats,
                'total' => $orders->total(),
            ]);
        }

        return view('admin.orders.index', compact('orders', 'statuses', 'stats'));
    }

    // Menghasilkan satu baris <tr> tabel pesanan untuk response AJAX
    // Kolom: No. Pesanan | Tanggal | Pembeli | Total | Status | Aksi
    // [+] Tambah <td> baru di sini jika perlu kolom tambahan di tabel
    //     Jangan lupa tambahkan <th> pasangannya di index.blade.php
    private function generateOrderRow($order)
    {
        // [+] Tambah entri baru di ketiga array ini jika ada status baru
        $statusClasses = [
            'pending'    => 'bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400',
            'paid'       => 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400',
            'processing' => 'bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400',
            'shipped'    => 'bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400',
            'completed'  => 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400',
            'cancelled'  => 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400',
        ];

        $dotClasses = [
            'pending'    => 'bg-yellow-500',
            'paid'       => 'bg-blue-500',
            'processing' => 'bg-purple-500',
            'shipped'    => 'bg-indigo-500',
            'completed'  => 'bg-green-500',
            'cancelled'  => 'bg-red-500',
        ];

        $statusLabels = [
            'pending'    => 'Menunggu Pembayaran',
            'paid'       => 'Sudah Dibayar',
            'processing' => 'Diproses',
            'shipped'    => 'Dikirim',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
        ];

        $statusClass = $statusClasses[$order->status] ?? 'bg-gray-100 dark:bg-gray-500/20 text-gray-700 dark:text-gray-400';
        $dotClass    = $dotClasses[$order->status] ?? 'bg-gray-500';
        $statusLabel = $statusLabels[$order->status] ?? ucfirst($order->status);
        $initial     = strtoupper(substr($order->user->name, 0, 1));
        $itemsCount  = $order->items->count();

        // Badge "BARU!" muncul jika pesanan baru dibayar dalam 2 menit terakhir
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
                    <p class="text-sm text-gray-900 dark:text-white">' . $order->created_at->format('d M Y') . '</p>
                    <p class="text-xs text-gray-500 dark:text-zinc-400">' . $order->created_at->format('H:i') . '</p>
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
        // [+] Tambah nama relasi di load([]) jika perlu tampilkan data tambahan
        $order->load(['user', 'items.product', 'payment']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        // Hanya menerima status 'processing' — transisi: paid → processing
        // [+] Tambah nilai di 'in:...' jika perlu izinkan transisi status lain
        $request->validate([
            'status' => ['required', 'in:processing'],
        ]);

        if ($order->status !== 'paid') {
            return redirect()->back()
                ->with('error', 'Status hanya bisa diubah ke Diproses jika pesanan berstatus Sudah Dibayar.');
        }

        // [+] Tambah kolom lain di update([]) jika perlu simpan data tambahan saat status berubah
        $order->update(['status' => 'processing']);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Status pesanan #' . $order->order_number . ' berhasil diubah ke Diproses.');
    }
}