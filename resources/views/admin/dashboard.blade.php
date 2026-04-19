{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard Admin - E-Commerce TSA')

@section('content')
<div class="space-y-6">

    {{-- ===================== PAGE HEADER ===================== --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">
                Selamat datang kembali, {{ Auth::user()->name }}! 👋
            </p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl shadow-sm text-sm text-gray-500 dark:text-zinc-400">
            <span class="material-symbols-outlined text-base text-soft-green">calendar_today</span>
            <span>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</span>
        </div>
    </div>

    {{-- ===================== ALERT BANNER STOK RENDAH ===================== --}}
    @if($lowStockProducts > 0)
    <div class="flex items-center justify-between gap-4 px-5 py-3.5 bg-white dark:bg-zinc-900 border border-red-200 dark:border-red-500/30 border-l-4 border-l-red-500 rounded-xl shadow-sm">
        <div class="flex items-center gap-3">
            <span class="flex-shrink-0 w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
            <div>
                <span class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ $lowStockProducts }} produk hampir habis stok
                </span>
                <span class="text-sm text-gray-500 dark:text-zinc-400 ml-2">
                    · Segera lakukan restock sebelum kehabisan
                </span>
            </div>
        </div>
        <a href="{{ route('admin.products.index') }}"
           class="flex-shrink-0 text-xs font-medium px-3 py-1.5 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 rounded-lg transition-colors">
            Lihat Produk
        </a>
    </div>
    @endif

    {{-- ===================== KPI CARDS ===================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5">

        {{-- Total Pesanan --}}
        <div class="card-hover relative bg-white dark:bg-zinc-900 p-5 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-blue-400 dark:bg-blue-500 rounded-b-xl"></div>
            <div class="flex items-start justify-between mb-3">
                <p class="text-sm font-medium text-gray-500 dark:text-zinc-400">Total Pesanan</p>
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-500/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-500 dark:text-blue-400 text-xl">shopping_cart</span>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalOrders) }}</p>
            <div class="flex items-center gap-2 mt-2.5">
                @if(isset($ordersTrend))
                    <span class="inline-flex items-center gap-0.5 text-xs font-semibold px-2 py-0.5 rounded-full
                        {{ $ordersTrend >= 0 ? 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-500/10' : 'text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-500/10' }}">
                        <span class="material-symbols-outlined text-sm">{{ $ordersTrend >= 0 ? 'arrow_upward' : 'arrow_downward' }}</span>
                        {{ abs($ordersTrend) }}%
                    </span>
                @endif
                <span class="text-xs text-gray-400 dark:text-zinc-500">vs bulan lalu</span>
            </div>
            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1.5">
                Selesai: <span class="font-semibold text-green-600 dark:text-green-400">{{ $completedOrders }}</span>
            </p>
        </div>

        {{-- Menunggu Konfirmasi --}}
        <div class="card-hover relative bg-white dark:bg-zinc-900 p-5 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-yellow-400 dark:bg-yellow-500 rounded-b-xl"></div>
            <div class="flex items-start justify-between mb-3">
                <p class="text-sm font-medium text-gray-500 dark:text-zinc-400">Menunggu Konfirmasi</p>
                <div class="w-10 h-10 bg-yellow-50 dark:bg-yellow-500/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-yellow-500 dark:text-yellow-400 text-xl">hourglass_top</span>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pendingOrders }}</p>
            <div class="flex items-center gap-2 mt-2.5">
                @if(isset($pendingTrend))
                    <span class="inline-flex items-center gap-0.5 text-xs font-semibold px-2 py-0.5 rounded-full
                        {{ $pendingTrend >= 0 ? 'text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-500/10' : 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-500/10' }}">
                        <span class="material-symbols-outlined text-sm">{{ $pendingTrend >= 0 ? 'arrow_upward' : 'arrow_downward' }}</span>
                        {{ abs($pendingTrend) }}%
                    </span>
                @endif
                <span class="text-xs text-gray-400 dark:text-zinc-500">vs bulan lalu</span>
            </div>
            <div class="mt-1.5">
                @if($pendingOrders > 0)
                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full bg-yellow-50 dark:bg-yellow-500/10 text-yellow-700 dark:text-yellow-400">
                        <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full animate-pulse"></span>
                        Perlu tindakan
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400">
                        <span class="material-symbols-outlined text-sm">check_circle</span>
                        Semua terproses
                    </span>
                @endif
            </div>
        </div>

        {{-- Total Pendapatan --}}
        <div class="card-hover relative bg-white dark:bg-zinc-900 p-5 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-soft-green rounded-b-xl"></div>
            <div class="flex items-start justify-between mb-3">
                <p class="text-sm font-medium text-gray-500 dark:text-zinc-400">Total Pendapatan</p>
                <div class="w-10 h-10 bg-green-50 dark:bg-green-500/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-500 dark:text-green-400 text-xl">attach_money</span>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white leading-tight">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </p>
            <div class="flex items-center gap-2 mt-2.5">
                @if(isset($revenueTrend))
                    <span class="inline-flex items-center gap-0.5 text-xs font-semibold px-2 py-0.5 rounded-full
                        {{ $revenueTrend >= 0 ? 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-500/10' : 'text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-500/10' }}">
                        <span class="material-symbols-outlined text-sm">{{ $revenueTrend >= 0 ? 'arrow_upward' : 'arrow_downward' }}</span>
                        {{ abs($revenueTrend) }}%
                    </span>
                @endif
                <span class="text-xs text-gray-400 dark:text-zinc-500">vs bulan lalu</span>
            </div>
            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1.5">
                Hari ini: <span class="font-semibold text-green-600 dark:text-green-400">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</span>
            </p>
        </div>

        {{-- Stok Rendah --}}
        <div class="card-hover relative bg-white dark:bg-zinc-900 p-5 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-red-400 dark:bg-red-500 rounded-b-xl"></div>
            <div class="flex items-start justify-between mb-3">
                <p class="text-sm font-medium text-gray-500 dark:text-zinc-400">Stok Rendah</p>
                <div class="w-10 h-10 bg-red-50 dark:bg-red-500/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-red-500 dark:text-red-400 text-xl">inventory</span>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $lowStockProducts }}</p>
            <div class="mt-2.5">
                @if($lowStockProducts > 0)
                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400">
                        <span class="material-symbols-outlined text-sm">warning</span>
                        Segera restock
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400">
                        <span class="material-symbols-outlined text-sm">check_circle</span>
                        Stok aman
                    </span>
                @endif
            </div>
            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1.5">Produk aktif dengan stok ≤ 5</p>
        </div>
    </div>

    {{-- ===================== PERLU PERHATIAN HARI INI ===================== --}}
    @php
        $hasAttention = $stalePendingOrders->count() > 0
                     || $lowStockItems->count() > 0
                     || $todayOrdersCount > 0;
    @endphp

    @if($hasAttention)
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        {{-- Header --}}
        <div class="px-5 py-4 border-b border-gray-200 dark:border-zinc-800 flex items-center gap-3">
            <div class="w-8 h-8 bg-amber-50 dark:bg-amber-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-amber-500 text-lg">notifications_active</span>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Perlu Perhatian Hari Ini</h3>
                <p class="text-xs text-gray-500 dark:text-zinc-400">Item yang membutuhkan tindakan segera</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-100 dark:divide-zinc-800">

            {{-- Kolom 1: Pending > 24 jam --}}
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-red-500 {{ $stalePendingOrders->count() > 0 ? 'animate-pulse' : '' }}"></span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Pending Terlama</span>
                    </div>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                        {{ $stalePendingOrders->count() > 0
                            ? 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400'
                            : 'bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400' }}">
                        {{ $stalePendingOrders->count() }} pesanan
                    </span>
                </div>

                @if($stalePendingOrders->count() > 0)
                    <div class="space-y-2">
                        @foreach($stalePendingOrders as $order)
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="flex items-center justify-between p-2.5 bg-red-50/50 dark:bg-red-500/5 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors group">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-gray-900 dark:text-white truncate group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">
                                        {{ $order->order_number ?? 'ORD-'.$order->id }}
                                    </p>
                                    <p class="text-[11px] text-gray-500 dark:text-zinc-400 truncate">
                                        {{ $order->user->name ?? 'Customer' }}
                                    </p>
                                </div>
                                <span class="flex-shrink-0 text-[11px] font-medium text-red-600 dark:text-red-400 ml-2">
                                    {{ $order->created_at->diffForHumans(null, true) }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-4 text-center">
                        <span class="material-symbols-outlined text-green-400 dark:text-green-500 text-3xl">check_circle</span>
                        <p class="text-xs text-gray-400 dark:text-zinc-500 mt-1.5">Tidak ada pesanan terlambat</p>
                    </div>
                @endif
            </div>

            {{-- Kolom 2: Stok Hampir Habis --}}
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-orange-500 {{ $lowStockItems->count() > 0 ? 'animate-pulse' : '' }}"></span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Stok Hampir Habis</span>
                    </div>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                        {{ $lowStockItems->count() > 0
                            ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400'
                            : 'bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400' }}">
                        {{ $lowStockItems->count() }} produk
                    </span>
                </div>

                @if($lowStockItems->count() > 0)
                    <div class="space-y-2">
                        @foreach($lowStockItems as $product)
                            <a href="{{ route('admin.products.index') }}"
                               class="flex items-center justify-between p-2.5 bg-orange-50/50 dark:bg-orange-500/5 hover:bg-orange-50 dark:hover:bg-orange-500/10 rounded-lg transition-colors group">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="flex-shrink-0 w-7 h-7 bg-gray-100 dark:bg-zinc-700 rounded-md overflow-hidden border border-gray-200 dark:border-zinc-600">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <span class="material-symbols-outlined text-gray-400 text-sm">inventory_2</span>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-xs font-medium text-gray-900 dark:text-white truncate group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                        {{ Str::limit($product->name, 20) }}
                                    </p>
                                </div>
                                <span class="flex-shrink-0 ml-2 text-xs font-bold
                                    {{ $product->stock == 0 ? 'text-red-600 dark:text-red-400' : 'text-orange-600 dark:text-orange-400' }}">
                                    {{ $product->stock }} sisa
                                </span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-4 text-center">
                        <span class="material-symbols-outlined text-green-400 dark:text-green-500 text-3xl">inventory</span>
                        <p class="text-xs text-gray-400 dark:text-zinc-500 mt-1.5">Semua stok aman</p>
                    </div>
                @endif
            </div>

            {{-- Kolom 3: Pesanan Masuk Hari Ini --}}
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500 {{ $todayOrdersCount > 0 ? 'animate-pulse' : '' }}"></span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Masuk Hari Ini</span>
                    </div>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                        {{ $todayOrdersCount > 0
                            ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400'
                            : 'bg-gray-50 dark:bg-zinc-800 text-gray-500 dark:text-zinc-400' }}">
                        {{ $todayOrdersCount }} pesanan
                    </span>
                </div>

                @if($todayOrders->count() > 0)
                    <div class="space-y-2">
                        @foreach($todayOrders as $order)
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="flex items-center justify-between p-2.5 bg-blue-50/50 dark:bg-blue-500/5 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-lg transition-colors group">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-gray-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                        {{ $order->order_number ?? 'ORD-'.$order->id }}
                                    </p>
                                    <p class="text-[11px] text-gray-500 dark:text-zinc-400 truncate">
                                        {{ $order->user->name ?? 'Customer' }}
                                    </p>
                                </div>
                                <span class="flex-shrink-0 text-sm font-bold text-gray-900 dark:text-white ml-2">
                                    Rp {{ number_format($order->grand_total ?? 0, 0, ',', '.') }}
                                </span>
                            </a>
                        @endforeach
                        @if($todayOrdersCount > 5)
                            <a href="{{ route('admin.orders.index') }}"
                               class="block text-center text-xs text-soft-green hover:underline pt-1">
                                +{{ $todayOrdersCount - 5 }} pesanan lainnya →
                            </a>
                        @endif
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-4 text-center">
                        <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-3xl">inbox</span>
                        <p class="text-xs text-gray-400 dark:text-zinc-500 mt-1.5">Belum ada pesanan hari ini</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
    @endif

    {{-- ===================== CHARTS ROW ===================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Revenue Chart --}}
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-zinc-800">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Grafik Pendapatan</h3>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5" id="chartSubtitle">Performa 7 hari terakhir</p>
                    </div>
                    <div class="flex items-center gap-1.5" id="periodButtons">
                        <button onclick="changePeriod(7, this)"
                            class="period-btn active-period px-3 py-1.5 text-xs font-medium rounded-lg border transition-all duration-200">
                            7 Hari
                        </button>
                        <button onclick="changePeriod(30, this)"
                            class="period-btn px-3 py-1.5 text-xs font-medium rounded-lg border transition-all duration-200">
                            30 Hari
                        </button>
                        <button onclick="changePeriod(90, this)"
                            class="period-btn px-3 py-1.5 text-xs font-medium rounded-lg border transition-all duration-200">
                            3 Bulan
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-5">
                <div class="relative">
                    <div id="chartLoader" class="hidden absolute inset-0 bg-white/60 dark:bg-zinc-900/60 flex items-center justify-center z-10 rounded-xl">
                        <div class="w-5 h-5 border-2 border-soft-green border-t-transparent rounded-full animate-spin"></div>
                    </div>
                    <div class="h-[260px] w-full">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-zinc-800 flex items-center justify-between text-sm">
                    <span class="text-gray-500 dark:text-zinc-400">Total periode ini</span>
                    <span class="font-bold text-gray-900 dark:text-white" id="chartTotal">
                        Rp {{ number_format(array_sum($revenues ?? []), 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Statistik Cepat --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-zinc-800">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Statistik Cepat</h3>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Tren 7 hari terakhir</p>
            </div>
            <div class="p-4 space-y-2.5">

                {{-- Pelanggan --}}
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-zinc-800/50 rounded-xl">
                    <div class="w-9 h-9 bg-purple-100 dark:bg-purple-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-lg">person</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-500 dark:text-zinc-400">Total Pelanggan</p>
                        <p class="text-base font-bold text-gray-900 dark:text-white leading-tight">{{ number_format($totalCustomers) }}</p>
                    </div>
                    {{-- Sparkline pelanggan baru per hari --}}
                    <div class="flex-shrink-0 flex items-end gap-[3px] h-8">
                        @foreach($sparkCustomers ?? [] as $val)
                            @php $h = $val > 0 ? max(4, round(($val / (max($sparkCustomers) ?: 1)) * 28)) : 3; @endphp
                            <div class="w-[4px] rounded-sm bg-purple-300 dark:bg-purple-500/60" style="height: {{ $h }}px"></div>
                        @endforeach
                    </div>
                    @if(isset($customersTrend))
                        <span class="text-xs font-semibold flex-shrink-0 {{ $customersTrend >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $customersTrend >= 0 ? '+' : '' }}{{ $customersTrend }}%
                        </span>
                    @endif
                </div>

                {{-- Total Produk --}}
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-zinc-800/50 rounded-xl">
                    <div class="w-9 h-9 bg-orange-100 dark:bg-orange-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-orange-600 dark:text-orange-400 text-lg">inventory_2</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-500 dark:text-zinc-400">Total Produk</p>
                        <p class="text-base font-bold text-gray-900 dark:text-white leading-tight">{{ number_format($totalProducts) }}</p>
                    </div>
                    {{-- Progress bar aktif vs total --}}
                    <div class="flex-shrink-0 flex flex-col items-end gap-1 min-w-[60px]">
                        <span class="text-[11px] text-gray-400 dark:text-zinc-500">
                            {{ $totalProducts > 0 ? round(($totalProducts / max($totalProducts, 1)) * 100) : 0 }}% aktif
                        </span>
                        <div class="w-14 h-1.5 bg-gray-200 dark:bg-zinc-700 rounded-full overflow-hidden">
                            <div class="h-full bg-orange-400 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                </div>

                {{-- Diproses --}}
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-zinc-800/50 rounded-xl">
                    <div class="w-9 h-9 bg-cyan-100 dark:bg-cyan-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-cyan-600 dark:text-cyan-400 text-lg">pending</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-500 dark:text-zinc-400">Diproses</p>
                        <p class="text-base font-bold text-gray-900 dark:text-white leading-tight">{{ number_format($processingOrders) }}</p>
                    </div>
                    {{-- Sparkline order diproses per hari --}}
                    <div class="flex-shrink-0 flex items-end gap-[3px] h-8">
                        @foreach($sparkProcessing ?? [] as $val)
                            @php $h = $val > 0 ? max(4, round(($val / (max($sparkProcessing) ?: 1)) * 28)) : 3; @endphp
                            <div class="w-[4px] rounded-sm bg-cyan-300 dark:bg-cyan-500/60" style="height: {{ $h }}px"></div>
                        @endforeach
                    </div>
                    @if($processingOrders > 0)
                        <span class="flex-shrink-0 w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                    @endif
                </div>

                {{-- Selesai --}}
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-zinc-800/50 rounded-xl">
                    <div class="w-9 h-9 bg-pink-100 dark:bg-pink-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-pink-600 dark:text-pink-400 text-lg">check_circle</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-500 dark:text-zinc-400">Selesai</p>
                        <p class="text-base font-bold text-gray-900 dark:text-white leading-tight">{{ number_format($completedOrders) }}</p>
                    </div>
                    {{-- Progress selesai vs total --}}
                    @if($totalOrders > 0)
                        <div class="flex-shrink-0 flex flex-col items-end gap-1 min-w-[60px]">
                            <span class="text-[11px] text-gray-400 dark:text-zinc-500">
                                {{ round(($completedOrders / $totalOrders) * 100) }}% selesai
                            </span>
                            <div class="w-14 h-1.5 bg-gray-200 dark:bg-zinc-700 rounded-full overflow-hidden">
                                <div class="h-full bg-pink-400 rounded-full"
                                     style="width: {{ round(($completedOrders / $totalOrders) * 100) }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sparkline: Pesanan per hari (7 hari) --}}
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-zinc-800/50 rounded-xl">
                    <div class="w-9 h-9 bg-soft-green/10 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-soft-green text-lg">trending_up</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-500 dark:text-zinc-400">Pesanan / Hari</p>
                        <p class="text-base font-bold text-gray-900 dark:text-white leading-tight">
                            {{ isset($sparkOrders) ? max($sparkOrders) : 0 }} maks
                        </p>
                    </div>
                    {{-- Sparkline pesanan per hari --}}
                    <div class="flex-shrink-0 flex items-end gap-[3px] h-8">
                        @foreach($sparkOrders ?? [] as $i => $val)
                            @php
                                $maxVal = max($sparkOrders) ?: 1;
                                $h = $val > 0 ? max(4, round(($val / $maxVal) * 28)) : 3;
                                $isToday = ($i === count($sparkOrders) - 1);
                            @endphp
                            <div class="w-[4px] rounded-sm {{ $isToday ? 'bg-soft-green' : 'bg-soft-green/40' }}"
                                 style="height: {{ $h }}px"></div>
                        @endforeach
                    </div>
                    <span class="text-xs text-gray-400 dark:text-zinc-500 flex-shrink-0">7h</span>
                </div>

            </div>
        </div>
    </div>

    {{-- ===================== BOTTOM ROW: Donut + Produk | Pesanan ===================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Kolom Kiri: Donut + Produk Terlaris --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            {{-- Donut Chart Kategori --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-200 dark:border-zinc-800">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Per Kategori</h3>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Komposisi bulan ini</p>
                </div>
                <div class="p-5">
                    @if(isset($categoryStats) && count($categoryStats) > 0)
                        <div class="relative h-[160px] w-full">
                            <canvas id="categoryChart"></canvas>
                        </div>
                        <div class="mt-4 space-y-2">
                            @foreach($categoryStats as $i => $cat)
                                <div class="flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full flex-shrink-0"
                                              style="background-color: {{ ['#7BB661','#60a5fa','#f59e0b','#a78bfa','#f472b6','#34d399'][$i % 6] }}"></span>
                                        <span class="text-gray-600 dark:text-zinc-300 truncate max-w-[90px]">{{ $cat['name'] }}</span>
                                    </div>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $cat['percentage'] }}%</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10">
                            <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-4xl">pie_chart</span>
                            <p class="text-xs text-gray-400 dark:text-zinc-500 mt-2">Belum ada data</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Produk Terlaris --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-200 dark:border-zinc-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Produk Terlaris</h3>
                            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Top 5 bulan ini</p>
                        </div>
                        <a href="{{ route('admin.products.index') }}"
                           class="text-xs font-medium text-soft-green hover:underline flex items-center gap-0.5">
                            Lihat
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>
                <div class="p-4">
                    <div class="space-y-2">
                        @forelse($topProducts as $index => $product)
                            <div class="flex items-center gap-3 p-2.5 bg-gray-50 dark:bg-zinc-800/50 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors group">
                                {{-- Rank badge --}}
                                <div class="flex-shrink-0 w-6 h-6 bg-gradient-to-br from-soft-green to-primary rounded-md flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                    {{ $index + 1 }}
                                </div>
                                {{-- Gambar produk --}}
                                <div class="flex-shrink-0 w-10 h-10 bg-gray-200 dark:bg-zinc-700 rounded-lg overflow-hidden border border-gray-200 dark:border-zinc-600">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-lg">inventory_2</span>
                                        </div>
                                    @endif
                                </div>
                                {{-- Info produk --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-gray-900 dark:text-white truncate group-hover:text-soft-green transition-colors">
                                        {{ Str::limit($product->name, 18) }}
                                    </p>
                                    {{-- Progress bar --}}
                                    @php
                                        $maxSold = $topProducts->max('total_sold') ?: 1;
                                        $pct = round(($product->total_sold ?? 0) / $maxSold * 100);
                                    @endphp
                                    <div class="mt-1 h-1 bg-gray-200 dark:bg-zinc-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-soft-green rounded-full transition-all"
                                             style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                                {{-- Jumlah terjual --}}
                                <div class="flex-shrink-0 text-right">
                                    <p class="text-sm font-bold text-soft-green">{{ $product->total_sold ?? 0 }}</p>
                                    <p class="text-[10px] text-gray-400 dark:text-zinc-500">terjual</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-4xl">inventory_2</span>
                                <p class="text-xs text-gray-400 dark:text-zinc-500 mt-2">Belum ada data produk</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Pesanan Terbaru --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-zinc-800">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Pesanan Terbaru</h3>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">5 pesanan terakhir masuk</p>
                    </div>
                    <a href="{{ route('admin.orders.index') }}"
                       class="text-xs font-medium text-soft-green hover:underline flex items-center gap-0.5">
                        Lihat Semua
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>
            <div class="p-4">
                <div class="space-y-2">
                    @forelse($recentOrders ?? [] as $order)
                        <a href="{{ route('admin.orders.show', $order->id) }}"
                           class="flex items-center gap-3 p-3.5 bg-gray-50 dark:bg-zinc-800/50 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors group block">
                            <div class="flex-shrink-0 w-9 h-9 bg-soft-green/10 dark:bg-soft-green/10 rounded-lg flex items-center justify-center">
                                <span class="material-symbols-outlined text-soft-green text-lg">receipt_long</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate group-hover:text-soft-green transition-colors">
                                    {{ $order->order_number ?? 'ORD-' . $order->id }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5 truncate">
                                    {{ $order->user->name ?? 'Customer' }}
                                    <span class="mx-1">·</span>
                                    {{ $order->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <p class="text-sm font-bold text-gray-900 dark:text-white">
                                    Rp {{ number_format($order->grand_total ?? 0, 0, ',', '.') }}
                                </p>
                                @php
                                    $statusMap = [
                                        'pending'    => ['label' => 'Pending',    'class' => 'bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400'],
                                        'paid'       => ['label' => 'Dibayar',    'class' => 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400'],
                                        'processing' => ['label' => 'Diproses',   'class' => 'bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400'],
                                        'shipped'    => ['label' => 'Dikirim',    'class' => 'bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400'],
                                        'completed'  => ['label' => 'Selesai',    'class' => 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400'],
                                        'cancelled'  => ['label' => 'Dibatalkan', 'class' => 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400'],
                                    ];
                                    $s = $statusMap[$order->status] ?? ['label' => 'Unknown', 'class' => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'];
                                @endphp
                                <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-[10px] font-medium {{ $s['class'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-12">
                            <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-5xl">receipt_long</span>
                            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-2">Belum ada pesanan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>

@push('styles')
<style>
    .period-btn {
        color: #6b7280;
        border-color: #e5e7eb;
        background: transparent;
    }
    .dark .period-btn {
        color: #a1a1aa;
        border-color: #3f3f46;
    }
    .period-btn.active-period {
        color: #7BB661;
        border-color: rgba(123, 182, 97, 0.4);
        background: rgba(123, 182, 97, 0.08);
    }
    .dark .period-btn.active-period {
        color: #7BB661;
        border-color: rgba(123, 182, 97, 0.3);
        background: rgba(123, 182, 97, 0.1);
    }
    .period-btn:hover:not(.active-period) {
        background: #f9fafb;
    }
    .dark .period-btn:hover:not(.active-period) {
        background: rgba(255,255,255,0.05);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const isDarkMode = document.documentElement.classList.contains('dark');

    // =====================
    // REVENUE CHART
    // =====================
    const ctx = document.getElementById('revenueChart').getContext('2d');
    let revenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($dates->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
            datasets: [{
                label: 'Pendapatan',
                data: @json($revenues),
                backgroundColor: function(context) {
                    const chart = context.chart;
                    const {ctx, chartArea} = chart;
                    if (!chartArea) return '#7BB661';
                    const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                    gradient.addColorStop(0, 'rgba(123, 182, 97, 0.55)');
                    gradient.addColorStop(1, 'rgba(123, 182, 97, 0.95)');
                    return gradient;
                },
                borderRadius: 7,
                borderSkipped: false,
                barPercentage: 0.65,
                categoryPercentage: 0.8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDarkMode ? 'rgba(24,24,27,0.95)' : 'rgba(17,17,17,0.88)',
                    padding: 14,
                    borderRadius: 10,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    displayColors: false,
                    callbacks: {
                        label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                    },
                    caretSize: 0,
                    caretPadding: 8
                }
            },
            scales: {
                x: {
                    grid: { display: false, drawBorder: false },
                    border: { display: false },
                    ticks: {
                        color: isDarkMode ? '#71717a' : '#9ca3af',
                        font: { size: 11, weight: '500' },
                        padding: 6
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: isDarkMode ? 'rgba(113,113,122,0.1)' : 'rgba(156,163,175,0.1)',
                        drawBorder: false,
                    },
                    border: { display: false, dash: [4, 4] },
                    ticks: {
                        color: isDarkMode ? '#71717a' : '#9ca3af',
                        font: { size: 11, weight: '500' },
                        padding: 10,
                        callback: function(value) {
                            if (value === 0) return '0';
                            if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                            return 'Rp ' + (value / 1000) + 'k';
                        },
                        maxTicksLimit: 5
                    }
                }
            },
            layout: { padding: { top: 8 } }
        }
    });

    // =====================
    // FILTER PERIODE (AJAX)
    // =====================
    function changePeriod(days, btn) {
        document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active-period'));
        btn.classList.add('active-period');

        const subtitles = {
            7: 'Performa 7 hari terakhir',
            30: 'Performa 30 hari terakhir',
            90: 'Performa 3 bulan terakhir'
        };
        document.getElementById('chartSubtitle').textContent = subtitles[days] || '';
        document.getElementById('chartLoader').classList.remove('hidden');

        fetch(`{{ route('admin.dashboard.chart') }}?days=${days}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            revenueChart.data.labels = data.dates;
            revenueChart.data.datasets[0].data = data.revenues;
            revenueChart.update();
            const total = data.revenues.reduce((a, b) => a + b, 0);
            document.getElementById('chartTotal').textContent =
                'Rp ' + total.toLocaleString('id-ID');
        })
        .catch(() => alert('Gagal memuat data grafik.'))
        .finally(() => document.getElementById('chartLoader').classList.add('hidden'));
    }

    // =====================
    // DONUT CHART KATEGORI
    // =====================
    @if(isset($categoryStats) && count($categoryStats) > 0)
    const ctxCat = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxCat, {
        type: 'doughnut',
        data: {
            labels: @json(collect($categoryStats)->pluck('name')),
            datasets: [{
                data: @json(collect($categoryStats)->pluck('percentage')),
                backgroundColor: ['#7BB661','#60a5fa','#f59e0b','#a78bfa','#f472b6','#34d399'],
                borderWidth: 2,
                borderColor: isDarkMode ? '#18181b' : '#ffffff',
                hoverOffset: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDarkMode ? 'rgba(24,24,27,0.95)' : 'rgba(17,17,17,0.88)',
                    padding: 10,
                    borderRadius: 8,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    displayColors: false,
                    callbacks: {
                        label: ctx => ctx.label + ': ' + ctx.parsed + '%'
                    }
                }
            }
        }
    });
    @endif
</script>
@endpush
@endsection