{{-- resources/views/superadmin/reports/index.blade.php --}}
@extends('layouts.superadmin')

@section('page-title', 'Laporan Penjualan')
@section('page-subtitle', 'Laporan detail transaksi penjualan')

@section('content')

<style>
    nav svg {
        color: #2D6A4F !important;
    }

    nav a.relative.inline-flex {
        color: #2D6A4F !important;
        background-color: #ffffff !important;
        border-color: #2D6A4F !important;
    }

    nav a.relative.inline-flex:hover {
        background-color: #2D6A4F !important;
        color: #ffffff !important;
        border-color: #2D6A4F !important;
    }

    nav span[aria-current="page"] span.relative.inline-flex {
        background: #2D6A4F !important;
        color: #ffffff !important;
        border-color: #2D6A4F !important;
    }

    nav span[aria-disabled="true"] span {
        color: #9ca3af !important;
        border-color: #e5e7eb !important;
        background-color: #ffffff !important;
    }

    .dark nav a.relative.inline-flex {
        color: #4ade80 !important;
        background-color: #27272a !important;
        border-color: #2D6A4F !important;
    }

    .dark nav a.relative.inline-flex:hover {
        background-color: #2D6A4F !important;
        color: #ffffff !important;
        border-color: #2D6A4F !important;
    }

    .dark nav span[aria-current="page"] span.relative.inline-flex {
        background: #2D6A4F !important;
        color: #ffffff !important;
        border-color: #2D6A4F !important;
    }

    .dark nav span[aria-disabled="true"] span {
        color: #52525b !important;
        border-color: #3f3f46 !important;
        background-color: #27272a !important;
    }
</style>

<div class="space-y-6">

    {{-- FORM FILTER --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 shadow-soft border border-gray-100 dark:border-zinc-800">
        <div class="flex items-center gap-2 mb-1">
            <span class="material-symbols-outlined text-soft-green text-[20px]">tune</span>
            <h3 class="text-sm font-bold text-gray-800 dark:text-white">Filter Laporan</h3>
        </div>
        <p class="text-[11px] text-gray-400 dark:text-zinc-500 mb-4 ml-7">Gunakan filter di bawah untuk mempersempit data laporan sesuai kebutuhan. Klik <strong class="text-gray-500 dark:text-zinc-400">Filter Data</strong> untuk menerapkan, atau <strong class="text-gray-500 dark:text-zinc-400">Reset</strong> untuk kembali ke tampilan awal.</p>

        <form method="GET" action="{{ route('superadmin.reports.index') }}" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-zinc-300 mb-1.5">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-transparent">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-zinc-300 mb-1.5">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-transparent">
                </div>

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

            <div class="flex flex-wrap items-center gap-3 mt-5 pt-5 border-t border-gray-100 dark:border-zinc-800">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-soft-green to-primary hover:opacity-90 text-white rounded-lg font-semibold text-sm shadow-lg shadow-soft-green/30 transition-all">
                    <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                    Filter Data
                </button>

                <a href="{{ route('superadmin.reports.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-gray-700 dark:text-zinc-300 rounded-lg font-semibold text-sm transition-all">
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
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-semibold transition-colors shadow-md shadow-red-500/30">
                        <span class="material-symbols-outlined text-[18px] text-white">picture_as_pdf</span>
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
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold transition-colors shadow-md shadow-emerald-500/30">
                        <span class="material-symbols-outlined text-[18px] text-white">table_chart</span>
                        Export Excel
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- KARTU STATISTIK --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl p-5 shadow-lg shadow-emerald-500/25 border border-emerald-400/20">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-white text-[20px]">payments</span>
                </div>
                <p class="text-[10px] font-semibold text-emerald-100 uppercase tracking-wide leading-tight">Total Pendapatan</p>
            </div>
            <p class="text-xl font-bold text-white">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
            <p class="text-[10px] text-emerald-200 mt-1">Total Keseluruhan Pendapatan</p>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl p-5 shadow-lg shadow-blue-500/25 border border-blue-400/20">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-white text-[20px]">shopping_cart</span>
                </div>
                <p class="text-[10px] font-semibold text-blue-100 uppercase tracking-wide leading-tight">Total Pesanan</p>
            </div>
            <p class="text-xl font-bold text-white">{{ number_format($stats['total_orders']) }}</p>
            <p class="text-[10px] text-blue-200 mt-1">Sesuai filter aktif</p>
        </div>

        <div class="bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl p-5 shadow-lg shadow-indigo-500/25 border border-indigo-400/20">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-white text-[20px]">inventory_2</span>
                </div>
                <p class="text-[10px] font-semibold text-indigo-100 uppercase tracking-wide leading-tight">Total Item Terjual</p>
            </div>
            <p class="text-xl font-bold text-white">{{ number_format($stats['total_items_sold']) }}</p>
            <p class="text-[10px] text-indigo-200 mt-1">Total unit produk</p>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl p-5 shadow-lg shadow-amber-500/25 border border-amber-400/20">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-white text-[20px]">analytics</span>
                </div>
                <p class="text-[10px] font-semibold text-amber-100 uppercase tracking-wide leading-tight">Rata-rata Pesanan</p>
            </div>
            <p class="text-xl font-bold text-white">Rp {{ number_format($stats['avg_order_value'], 0, ',', '.') }}</p>
            <p class="text-[10px] text-amber-200 mt-1">Per transaksi</p>
        </div>
    </div>

    {{-- TABEL DETAIL PESANAN --}}
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
                <thead>
                    <tr style="background-color: #2D6A4F;" class="dark:bg-zinc-800">
                        <th class="px-4 py-3.5 text-center text-[10px] font-bold text-white dark:text-zinc-200 uppercase tracking-wider w-10">No.</th>
                        <th class="px-4 py-3.5 text-left   text-[10px] font-bold text-white dark:text-zinc-200 uppercase tracking-wider">No. Pesanan</th>
                        <th class="px-4 py-3.5 text-left   text-[10px] font-bold text-white dark:text-zinc-200 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3.5 text-left   text-[10px] font-bold text-white dark:text-zinc-200 uppercase tracking-wider">Provinsi</th>
                        <th class="px-4 py-3.5 text-left   text-[10px] font-bold text-white dark:text-zinc-200 uppercase tracking-wider">Kategori</th>
                        <th class="px-4 py-3.5 text-left   text-[10px] font-bold text-white dark:text-zinc-200 uppercase tracking-wider">Produk</th>
                        <th class="px-4 py-3.5 text-center text-[10px] font-bold text-white dark:text-zinc-200 uppercase tracking-wider">Qty</th>
                        <th class="px-4 py-3.5 text-right  text-[10px] font-bold text-white dark:text-zinc-200 uppercase tracking-wider">Subtotal</th>
                        <th class="px-4 py-3.5 text-right  text-[10px] font-bold text-white dark:text-zinc-200 uppercase tracking-wider">Ongkir</th>
                        <th class="px-4 py-3.5 text-right  text-[10px] font-bold text-white dark:text-zinc-200 uppercase tracking-wider">Total</th>
                        <th class="px-4 py-3.5 text-left   text-[10px] font-bold text-white dark:text-zinc-200 uppercase tracking-wider">Metode Bayar</th>
                        <th class="px-4 py-3.5 text-center text-[10px] font-bold text-white dark:text-zinc-200 uppercase tracking-wider">Status</th>
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
                        $qty = $order->items->sum('quantity');

                        $rawMethod    = $order->payment?->payment_type ?? $order->payment_method ?? '';
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
                            'pending'    => ['label' => 'Menunggu',   'class' => 'bg-yellow-400 text-yellow-900'],
                            'paid'       => ['label' => 'Dibayar',    'class' => 'bg-blue-500 text-white'],
                            'processing' => ['label' => 'Diproses',   'class' => 'bg-purple-500 text-white'],
                            'shipped'    => ['label' => 'Dikirim',    'class' => 'bg-indigo-500 text-white'],
                            'completed'  => ['label' => 'Selesai',    'class' => 'bg-emerald-500 text-white'],
                            'cancelled'  => ['label' => 'Dibatalkan', 'class' => 'bg-red-500 text-white'],
                        ];
                        $sc = $statusConfig[$order->status] ?? ['label' => ucfirst($order->status), 'class' => 'bg-gray-400 text-white'];

                        // Baris genap (#F0F7F4) seperti di PDF, baris ganjil putih
                        $rowBg = (($i + 1) % 2 === 0)
                            ? 'bg-[#F0F7F4] dark:bg-emerald-950/20'
                            : 'bg-white dark:bg-zinc-900';
                    @endphp
                    <tr class="{{ $rowBg }}>
                        <td class="px-4 py-3 text-center text-xs text-gray-500 dark:text-zinc-400">{{ $orders->firstItem() + $i }}</td>

                        <td class="px-4 py-3"><span class="text-xs font-semibold text-gray-900 dark:text-white">#{{ $order->order_number }}</span></td>

                        <td class="px-4 py-3">
                            <p class="text-xs text-gray-900 dark:text-white">{{ $order->created_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-gray-500 dark:text-zinc-500">{{ $order->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-4 py-3"><span class="text-xs text-gray-900 dark:text-white">{{ $order->address?->province_name ?? '-' }}</span></td>
                        <td class="px-4 py-3"><span class="text-xs text-gray-900 dark:text-white">{{ $categories ?: '-' }}</span></td>
                        <td class="px-4 py-3 max-w-[200px]">
                            <span class="text-xs text-gray-900 dark:text-white line-clamp-2" title="{{ $products }}">{{ $products }}</span>
                        </td>
                        <td class="px-4 py-3 text-center"><span class="text-xs font-medium text-gray-900 dark:text-white">{{ $qty }}</span></td>
                        <td class="px-4 py-3 text-right"><span class="text-xs text-gray-900 dark:text-white">Rp {{ number_format($order->subtotal ?? 0, 0, ',', '.') }}</span></td>
                        <td class="px-4 py-3 text-right"><span class="text-xs text-gray-900 dark:text-white">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span></td>

                        <td class="px-4 py-3 text-right"><span class="text-xs font-bold text-gray-900 dark:text-white">Rp {{ number_format($order->grand_total ?? 0, 0, ',', '.') }}</span></td>

                        <td class="px-4 py-3"><span class="text-xs text-gray-700 dark:text-zinc-300">{{ $paymentLabel }}</span></td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-semibold rounded-sm {{ $sc['class'] }}">
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