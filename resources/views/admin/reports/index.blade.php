{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Laporan Penjualan E-Commerce TSA')

@section('content')
<div class="space-y-6">

    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Penjualan</h1>
        <p class="text-gray-500 dark:text-zinc-400 text-sm">Pilih rentang tanggal untuk melihat laporan</p>
    </div>

    {{-- FORM FILTER — tanggal, status, tombol export
         [+] Tambah input filter baru (mis: filter by kurir) di dalam grid di bawah --}}
    <form method="GET" action="{{ route('admin.reports.index') }}"
          class="bg-white dark:bg-zinc-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-700/50">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-300 mb-1.5">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}" required
                       class="w-full px-4 py-2 border rounded-lg bg-gray-50 dark:bg-zinc-700 text-gray-900 dark:text-white border-gray-200 dark:border-zinc-600 focus:ring-2 focus:ring-soft-green focus:border-transparent">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-300 mb-1.5">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate }}" required
                       class="w-full px-4 py-2 border rounded-lg bg-gray-50 dark:bg-zinc-700 text-gray-900 dark:text-white border-gray-200 dark:border-zinc-600 focus:ring-2 focus:ring-soft-green focus:border-transparent">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-300 mb-1.5">Status Pesanan</label>
                <select name="status"
                        class="w-full px-4 py-2 border rounded-lg bg-gray-50 dark:bg-zinc-700 text-gray-900 dark:text-white border-gray-200 dark:border-zinc-600 focus:ring-2 focus:ring-soft-green focus:border-transparent">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions as $key => $label)
                        <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 mt-4 pt-4 border-t border-gray-100 dark:border-zinc-700">
            <button type="submit"
                    class="px-5 py-2 bg-soft-green text-white rounded-lg font-medium hover:shadow-lg transition-all text-sm">
                Tampilkan Laporan
            </button>

            <a href="{{ route('admin.reports.index') }}"
               class="px-5 py-2 bg-gray-100 dark:bg-zinc-700 hover:bg-gray-200 dark:hover:bg-zinc-600 text-gray-600 dark:text-zinc-300 rounded-lg font-medium text-sm transition-all">
                Reset
            </a>

            {{-- Tombol export — parameter filter ikut dikirim via URL --}}
            <div class="ml-auto flex flex-wrap gap-2">
                <a href="{{ route('admin.reports.exportPdf', array_filter([
                        'start_date' => $startDate,
                        'end_date'   => $endDate,
                        'status'     => $status,
                    ])) }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-red-500/10 text-red-500 dark:text-red-400 hover:bg-red-500/20 rounded-lg text-sm font-medium transition-colors border border-red-500/20">
                    Export PDF
                </a>
                <a href="{{ route('admin.reports.exportExcel', array_filter([
                        'start_date' => $startDate,
                        'end_date'   => $endDate,
                        'status'     => $status,
                    ])) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-green-500/10 text-green-600 dark:text-green-400 hover:bg-green-500/20 rounded-lg text-sm font-medium transition-colors border border-green-500/20">
                    Export Excel
                </a>
            </div>
        </div>
    </form>

    {{-- KARTU STATISTIK — hanya menghitung status valid (bukan pending/cancelled)
         [+] Tambah kartu baru jika dosen minta metrik tambahan
             Sesuaikan juga $stats di ReportController::index() --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-zinc-800 border border-gray-100 dark:border-zinc-700/50 p-6 rounded-xl shadow-sm card-hover">
            <p class="text-sm text-gray-500 dark:text-zinc-400">Total Pendapatan</p>
            <p class="text-2xl font-bold text-soft-green">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-800 border border-gray-100 dark:border-zinc-700/50 p-6 rounded-xl shadow-sm card-hover">
            <p class="text-sm text-gray-500 dark:text-zinc-400">Jumlah Pesanan</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_orders']) }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-800 border border-gray-100 dark:border-zinc-700/50 p-6 rounded-xl shadow-sm card-hover">
            <p class="text-sm text-gray-500 dark:text-zinc-400">Total Item Terjual</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_items_sold']) }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-800 border border-gray-100 dark:border-zinc-700/50 p-6 rounded-xl shadow-sm card-hover">
            <p class="text-sm text-gray-500 dark:text-zinc-400">Rata-rata Pesanan</p>
            <p class="text-2xl font-bold text-warm-yellow">Rp {{ number_format($stats['avg_order_value'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- TABEL DETAIL PESANAN — menampilkan SEMUA status sesuai filter user
         [+] Tambah <th> dan <td> baru jika perlu kolom tambahan
             Sesuaikan juga kolom di SalesReportExport dan pdf.blade.php --}}
    <div class="bg-white dark:bg-zinc-800 border border-gray-100 dark:border-zinc-700/50 rounded-xl shadow-sm card-hover">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-zinc-700 flex items-center justify-between flex-wrap gap-2">
            <h3 class="text-base font-bold text-gray-900 dark:text-white">Detail Pesanan</h3>
            <span class="text-xs text-gray-500 dark:text-zinc-400">
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} &ndash; {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                &nbsp;&bull;&nbsp; {{ $orders->total() }} data ditemukan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-gray-900 dark:text-white" style="min-width: 1400px;">
                <thead class="bg-gray-50 dark:bg-zinc-900">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-zinc-400 w-10">No.</th>
                        <th class="px-4 py-3 text-left  text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-zinc-400">No. Pesanan</th>
                        <th class="px-4 py-3 text-left  text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-zinc-400">Tanggal</th>
                        <th class="px-4 py-3 text-left  text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-zinc-400">Pembeli</th>
                        <th class="px-4 py-3 text-left  text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-zinc-400">Email</th>
                        <th class="px-4 py-3 text-left  text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-zinc-400">No. Telp</th>
                        <th class="px-4 py-3 text-left  text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-zinc-400">Provinsi</th>
                        <th class="px-4 py-3 text-left  text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-zinc-400">Kota/Kab.</th>
                        <th class="px-4 py-3 text-left  text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-zinc-400">Alamat</th>
                        <th class="px-4 py-3 text-left  text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-zinc-400">Produk</th>
                        <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-zinc-400">Jumlah</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-zinc-400">Total</th>
                        <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-zinc-400">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-700">
                    @forelse($orders as $i => $order)
                    @php
                        $products = $order->items
                            ->map(fn($item) => ($item->product?->name ?? '-') . ' (x' . $item->quantity . ')')
                            ->implode(', ');
                        $qty = $order->items->sum('quantity');

                        // [+] Tambah entri baru di $statusConfig jika ada status baru
                        $statusConfig = [
                            'pending'    => ['label' => 'Menunggu',   'class' => 'bg-yellow-500/20 text-yellow-600 dark:text-yellow-400'],
                            'paid'       => ['label' => 'Dibayar',    'class' => 'bg-blue-500/20 text-blue-600 dark:text-blue-400'],
                            'processing' => ['label' => 'Diproses',   'class' => 'bg-purple-500/20 text-purple-600 dark:text-purple-400'],
                            'shipped'    => ['label' => 'Dikirim',    'class' => 'bg-indigo-500/20 text-indigo-600 dark:text-indigo-400'],
                            'completed'  => ['label' => 'Selesai',    'class' => 'bg-green-500/20 text-green-600 dark:text-green-400'],
                            'cancelled'  => ['label' => 'Dibatalkan', 'class' => 'bg-red-500/20 text-red-600 dark:text-red-400'],
                        ];
                        $sc = $statusConfig[$order->status] ?? ['label' => ucfirst($order->status), 'class' => 'bg-gray-500/20 text-gray-500 dark:text-zinc-400'];
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                        <td class="px-4 py-4 text-center text-sm text-gray-400 dark:text-zinc-400">
                            {{ $orders->firstItem() + $i }}
                        </td>
                        <td class="px-4 py-4 text-sm font-medium text-soft-green">#{{ $order->order_number }}</td>
                        <td class="px-4 py-4">
                            <p class="text-sm text-gray-900 dark:text-white">{{ $order->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400 dark:text-zinc-500">{{ $order->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                            {{ $order->recipient_name ?? $order->user?->name ?? '-' }}
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-zinc-300">{{ $order->user?->email ?? '-' }}</td>
                        <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">{{ $order->recipient_phone ?? '-' }}</td>
                        <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">{{ $order->province ?? '-' }}</td>
                        <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">{{ $order->city ?? '-' }}</td>
                        <td class="px-4 py-4 text-sm max-w-[160px]">
                            <span class="line-clamp-2 text-gray-900 dark:text-white" title="{{ $order->address?->full_address ?? $order->shipping_address ?? '-' }}">
                                {{ $order->address?->full_address ?? $order->shipping_address ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm max-w-[180px]">
                            <span class="line-clamp-2 text-gray-900 dark:text-white" title="{{ $products }}">{{ $products }}</span>
                        </td>
                        <td class="px-4 py-4 text-center text-sm text-gray-900 dark:text-white">{{ $qty }}</td>
                        <td class="px-4 py-4 text-right text-sm font-medium text-soft-green">
                            Rp {{ number_format($order->grand_total ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-semibold rounded-full {{ $sc['class'] }}">
                                {{ $sc['label'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="13" class="text-center py-12 text-gray-400 dark:text-zinc-400">
                            <p class="text-sm">Tidak ada data pesanan</p>
                            <p class="text-xs mt-1">Coba ubah rentang tanggal atau filter yang dipilih</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-zinc-700">
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection