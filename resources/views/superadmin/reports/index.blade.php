{{-- resources/views/superadmin/reports/index.blade.php --}}
@extends('layouts.superadmin')

@section('page-title', 'Laporan Penjualan')
@section('page-subtitle', 'Laporan detail transaksi penjualan')

@section('content')
<div class="space-y-6">

    {{-- ===================== FILTER SECTION ===================== --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 shadow-soft border border-gray-100 dark:border-zinc-800">
        <form method="GET" action="{{ route('superadmin.reports.index') }}" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

                {{-- Tanggal Mulai --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-zinc-300 mb-1.5">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-transparent">
                </div>

                {{-- Tanggal Akhir --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-zinc-300 mb-1.5">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-transparent">
                </div>

                {{-- Provinsi --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-zinc-300 mb-1.5">Provinsi</label>
                    <select name="province"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-transparent">
                        <option value="">Semua Provinsi</option>
                        @foreach($provinceOptions as $prov)
                            <option value="{{ $prov }}" {{ $province === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-zinc-300 mb-1.5">Kategori</label>
                    <select name="category_id"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-transparent">
                        <option value="">Semua Kategori</option>
                        @foreach($categoryOptions as $cat)
                            <option value="{{ $cat->id }}" {{ (string)$categoryId === (string)$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Metode Pembayaran --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-zinc-300 mb-1.5">Metode Pembayaran</label>
                    <select name="payment_method"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-transparent">
                        <option value="">Semua Metode</option>
                        @foreach($paymentMethodOptions as $key => $label)
                            <option value="{{ $key }}" {{ $paymentMethod === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Pesanan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-zinc-300 mb-1.5">Status Pesanan</label>
                    <select name="status"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-transparent">
                        <option value="">Semua Status</option>
                        @foreach($statusOptions as $key => $label)
                            <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-wrap items-center gap-3 mt-5 pt-5 border-t border-gray-100 dark:border-zinc-800">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-soft-green to-primary hover:opacity-90 text-white rounded-lg font-medium text-sm shadow-lg shadow-soft-green/30 transition-all">
                    <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                    Filter Data
                </button>

                <a href="{{ route('superadmin.reports.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-gray-700 dark:text-zinc-300 rounded-lg font-medium text-sm transition-all">
                    <span class="material-symbols-outlined text-[18px]">refresh</span>
                    Reset
                </a>

                <div class="ml-auto flex flex-wrap gap-2">
                    <a href="{{ route('superadmin.reports.exportPdf', array_filter([
                            'start_date'     => $startDate,
                            'end_date'       => $endDate,
                            'province'       => $province,
                            'category_id'    => $categoryId,
                            'payment_method' => $paymentMethod,
                            'status'         => $status,
                        ])) }}"
                       target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 rounded-lg text-sm font-medium transition-colors border border-red-100 dark:border-red-500/20">
                        <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                        Export PDF
                    </a>
                    <a href="{{ route('superadmin.reports.exportExcel', array_filter([
                            'start_date'     => $startDate,
                            'end_date'       => $endDate,
                            'province'       => $province,
                            'category_id'    => $categoryId,
                            'payment_method' => $paymentMethod,
                            'status'         => $status,
                        ])) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-500/20 rounded-lg text-sm font-medium transition-colors border border-green-100 dark:border-green-500/20">
                        <span class="material-symbols-outlined text-[18px]">table_chart</span>
                        Export Excel
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- ===================== RINGKASAN STATISTIK ===================== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total Pendapatan --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-5 shadow-soft border border-gray-100 dark:border-zinc-800">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-lg shadow-green-500/30 flex-shrink-0">
                    <span class="material-symbols-outlined text-white text-[18px]">payments</span>
                </div>
                <p class="text-[10px] font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wide leading-tight">Total Pendapatan</p>
            </div>
            <p class="text-lg font-bold text-gray-900 dark:text-white">
                Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
            </p>
            <p class="text-[10px] text-gray-400 dark:text-zinc-500 mt-1">Subtotal dikurangi ongkir</p>
        </div>

        {{-- Total Pesanan --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-5 shadow-soft border border-gray-100 dark:border-zinc-800">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/30 flex-shrink-0">
                    <span class="material-symbols-outlined text-white text-[18px]">shopping_cart</span>
                </div>
                <p class="text-[10px] font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wide leading-tight">Total Pesanan</p>
            </div>
            <p class="text-lg font-bold text-gray-900 dark:text-white">
                {{ number_format($stats['total_orders']) }}
            </p>
            <p class="text-[10px] text-gray-400 dark:text-zinc-500 mt-1">Sesuai filter aktif</p>
        </div>

        {{-- Total Item Terjual --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-5 shadow-soft border border-gray-100 dark:border-zinc-800">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-indigo-500/30 flex-shrink-0">
                    <span class="material-symbols-outlined text-white text-[18px]">inventory_2</span>
                </div>
                <p class="text-[10px] font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wide leading-tight">Total Item Terjual</p>
            </div>
            <p class="text-lg font-bold text-gray-900 dark:text-white">
                {{ number_format($stats['total_items_sold']) }}
            </p>
            <p class="text-[10px] text-gray-400 dark:text-zinc-500 mt-1">Total unit produk</p>
        </div>

        {{-- Rata-rata Nilai Pesanan --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-5 shadow-soft border border-gray-100 dark:border-zinc-800">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg shadow-purple-500/30 flex-shrink-0">
                    <span class="material-symbols-outlined text-white text-[18px]">analytics</span>
                </div>
                <p class="text-[10px] font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wide leading-tight">Rata-rata Pesanan</p>
            </div>
            <p class="text-lg font-bold text-gray-900 dark:text-white">
                Rp {{ number_format($stats['avg_order_value'], 0, ',', '.') }}
            </p>
            <p class="text-[10px] text-gray-400 dark:text-zinc-500 mt-1">Per transaksi</p>
        </div>
    </div>

    {{-- ===================== TABEL DETAIL PESANAN ===================== --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-soft border border-gray-100 dark:border-zinc-800">
        <div class="p-6 border-b border-gray-100 dark:border-zinc-800">
            <div class="flex items-center justify-between flex-wrap gap-2">
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Detail Pesanan</h3>
                <span class="text-xs text-gray-500 dark:text-zinc-400">
                    Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} &ndash; {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                    &nbsp;&bull;&nbsp; {{ $orders->total() }} data ditemukan
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1100px]">
                <thead class="bg-gray-50 dark:bg-zinc-800/50 border-b border-gray-100 dark:border-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-center text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider w-10">No.</th>
                        <th class="px-4 py-3 text-left   text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">No. Pesanan</th>
                        <th class="px-4 py-3 text-left   text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left   text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Provinsi</th>
                        <th class="px-4 py-3 text-left   text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Kategori</th>
                        <th class="px-4 py-3 text-left   text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Produk</th>
                        <th class="px-4 py-3 text-center text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Qty</th>
                        <th class="px-4 py-3 text-right  text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Subtotal</th>
                        <th class="px-4 py-3 text-right  text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Ongkir</th>
                        <th class="px-4 py-3 text-right  text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Total</th>
                        <th class="px-4 py-3 text-left   text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Metode Bayar</th>
                        <th class="px-4 py-3 text-center text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                    @forelse($orders as $i => $order)
                    @php
                        $categories = $order->items
                            ->map(fn($item) => $item->product?->category?->name)
                            ->filter()->unique()->implode(', ');
                        $products = $order->items
                            ->map(fn($item) => ($item->product?->name ?? '-') . ' (x' . $item->quantity . ')')
                            ->implode(', ');
                        $qty   = $order->items->sum('quantity');
                        $total = ($order->subtotal ?? 0) + ($order->shipping_cost ?? 0);

                        $rawMethod = $order->payment?->payment_type ?? $order->payment_method ?? '';
                        $paymentLabel = match($rawMethod) {
                            'bank_transfer', 'transfer' => 'Transfer Bank',
                            'echannel'      => 'Mandiri E-Channel',
                            'cstore'        => 'Minimarket',
                            'gopay'         => 'GoPay',
                            'qris'          => 'QRIS',
                            'shopeepay'     => 'ShopeePay',
                            'credit_card'   => 'Kartu Kredit',
                            'wallet'        => 'E-Wallet',
                            'cod'           => 'COD (Cash on Delivery)',
                            ''              => '-',
                            default         => ucfirst(str_replace('_', ' ', $rawMethod)),
                        };

                        $statusConfig = [
                            'pending'    => ['label' => 'Menunggu',   'class' => 'bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400'],
                            'paid'       => ['label' => 'Dibayar',    'class' => 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400'],
                            'processing' => ['label' => 'Diproses',   'class' => 'bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400'],
                            'shipped'    => ['label' => 'Dikirim',    'class' => 'bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400'],
                            'completed'  => ['label' => 'Selesai',    'class' => 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400'],
                            'cancelled'  => ['label' => 'Dibatalkan', 'class' => 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400'],
                        ];
                        $sc = $statusConfig[$order->status] ?? ['label' => ucfirst($order->status), 'class' => 'bg-gray-100 text-gray-600'];
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <td class="px-4 py-3 text-center text-xs text-gray-500 dark:text-zinc-400">
                            {{ $orders->firstItem() + $i }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">#{{ $order->order_number }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-xs text-gray-900 dark:text-white">{{ $order->created_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-gray-500 dark:text-zinc-500">{{ $order->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs text-gray-900 dark:text-white">{{ $order->address->province_name ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs text-gray-900 dark:text-white">{{ $categories ?: '-' }}</span>
                        </td>
                        <td class="px-4 py-3 max-w-[200px]">
                            <span class="text-xs text-gray-900 dark:text-white line-clamp-2" title="{{ $products }}">{{ $products }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs font-medium text-gray-900 dark:text-white">{{ $qty }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-xs text-gray-900 dark:text-white">Rp {{ number_format($order->subtotal ?? 0, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-xs text-gray-900 dark:text-white">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-xs font-bold text-green-600 dark:text-green-400">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs text-gray-700 dark:text-zinc-300">{{ $paymentLabel }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-semibold rounded-full {{ $sc['class'] }}">
                                {{ $sc['label'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="px-6 py-16 text-center">
                            <span class="material-symbols-outlined text-gray-300 dark:text-zinc-700 text-5xl">shopping_bag</span>
                            <p class="text-sm font-medium text-gray-500 dark:text-zinc-400 mt-3">Tidak ada data pesanan</p>
                            <p class="text-xs text-gray-400 dark:text-zinc-500 mt-1">Coba ubah rentang tanggal atau filter yang dipilih</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-zinc-800">
            {{ $orders->links() }}
        </div>
        @endif
    </div>

</div>
@endsection