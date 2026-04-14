{{-- resources/views/superadmin/dashboard.blade.php --}}
@extends('layouts.superadmin')

@section('title', 'Dashboard Analitik - E-Commerce TSA')

@section('content')
<div class="space-y-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-display">Dashboard Analitik</h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">
                Selamat datang, {{ Auth::user()->name }}! &nbsp;·&nbsp; Periode
                <span class="font-semibold text-gray-700 dark:text-zinc-300">
                    {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} –
                    {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
                </span>
            </p>
        </div>
    </div>

    {{-- FILTER PANEL --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-5">
        <form method="GET" action="{{ route('superadmin.dashboard') }}" id="filterForm">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase mb-1.5">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}"
                        class="w-full px-3 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase mb-1.5">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}"
                        class="w-full px-3 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase mb-1.5">Provinsi</label>
                    <select name="province" class="w-full px-3 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green transition-colors">
                        <option value="">Semua Provinsi</option>
                        @foreach($provinceOptions as $prov)
                            <option value="{{ $prov }}" {{ $province == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase mb-1.5">Kategori</label>
                    <select name="category_id" class="w-full px-3 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green transition-colors">
                        <option value="">Semua Kategori</option>
                        @foreach($categoryOptions as $cat)
                            <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase mb-1.5">Metode Bayar</label>
                    <select name="payment_method" class="w-full px-3 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green transition-colors">
                        <option value="">Semua Metode</option>
                        @foreach($paymentMethodOptions as $key => $label)
                            <option value="{{ $key }}" {{ $paymentMethod == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-end gap-3 mt-4">
                <div class="w-full sm:w-56">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase mb-1.5">Status Pesanan</label>
                    <select name="payment_status" class="w-full px-3 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green transition-colors">
                        <option value="">Semua Status</option>
                        @foreach($paymentStatusOptions as $key => $label)
                            <option value="{{ $key }}" {{ $paymentStatus == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="setRange(7)"    class="px-3 py-2 text-xs font-medium bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-gray-700 dark:text-zinc-300 rounded-lg transition-colors">7 Hari</button>
                    <button type="button" onclick="setRange(30)"   class="px-3 py-2 text-xs font-medium bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-gray-700 dark:text-zinc-300 rounded-lg transition-colors">30 Hari</button>
                    <button type="button" onclick="setThisMonth()" class="px-3 py-2 text-xs font-medium bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-gray-700 dark:text-zinc-300 rounded-lg transition-colors">Bulan Ini</button>
                    <button type="button" onclick="setThisYear()"  class="px-3 py-2 text-xs font-medium bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-gray-700 dark:text-zinc-300 rounded-lg transition-colors">Tahun Ini</button>
                </div>
                <div class="flex gap-2 ml-auto">
                    <a href="{{ route('superadmin.dashboard') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-gray-700 dark:text-zinc-300 text-sm font-medium rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-base">close</span> Reset
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-soft-green hover:bg-primary text-white text-sm font-medium rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-base">search</span> Terapkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- SCORE CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-start justify-between gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wide">Total Pendapatan</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white mt-1.5 truncate">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="w-10 h-10 bg-green-50 dark:bg-green-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-xl">payments</span>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-start justify-between gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wide">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1.5">{{ number_format($totalTransactions) }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-xl">receipt_long</span>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-start justify-between gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wide">Produk Terjual</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1.5">{{ number_format($totalProductsSold) }}</p>
                </div>
                <div class="w-10 h-10 bg-orange-50 dark:bg-orange-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-orange-600 dark:text-orange-400 text-xl">inventory_2</span>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-start justify-between gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wide">Rata-rata Transaksi</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white mt-1.5 truncate">Rp {{ number_format($avgTransactionValue, 0, ',', '.') }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-50 dark:bg-purple-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-xl">query_stats</span>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-start justify-between gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wide">Pelanggan Aktif</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1.5">{{ number_format($totalBuyers) }}</p>
                </div>
                <div class="w-10 h-10 bg-pink-50 dark:bg-pink-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-pink-600 dark:text-pink-400 text-xl">group</span>
                </div>
            </div>
        </div>
    </div>

    {{-- GRAFIK PENDAPATAN --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-200 dark:border-zinc-800">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Tren Pendapatan</h3>
            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Pendapatan harian sesuai rentang filter</p>
        </div>
        <div class="p-5">
            <canvas id="revenueChart" height="60"></canvas>
        </div>
    </div>

    {{-- BARIS 2: Produk (horizontal) | Kategori (pie) | Provinsi (bar) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- TOP 5 PRODUK — Bar Horizontal --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-zinc-800">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Top 5 Produk Terlaris</h3>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Berdasarkan jumlah terjual</p>
            </div>
            <div class="p-5">
                @if($topProducts->isNotEmpty())
                    <canvas id="productChart" height="220"></canvas>
                @else
                    <div class="flex flex-col items-center py-10 text-gray-400 dark:text-zinc-600">
                        <span class="material-symbols-outlined text-4xl mb-2">inventory_2</span>
                        <p class="text-sm">Belum ada data</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- TOP 5 KATEGORI — Pie --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-zinc-800">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Top 5 Kategori Terlaris</h3>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Distribusi penjualan per kategori</p>
            </div>
            <div class="p-5">
                @if($topCategories->isNotEmpty())
                    <div class="flex justify-center mb-4">
                        <div class="relative w-44 h-44">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                    <div class="space-y-2 mt-2" id="cat-legend"></div>
                @else
                    <div class="flex flex-col items-center py-10 text-gray-400 dark:text-zinc-600">
                        <span class="material-symbols-outlined text-4xl mb-2">category</span>
                        <p class="text-sm">Belum ada data</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- TOP 5 PROVINSI — Bar --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-zinc-800">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Top 5 Provinsi</h3>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Berdasarkan jumlah pesanan</p>
            </div>
            <div class="p-5">
                @if($topProvinces->isNotEmpty())
                    <canvas id="provinceChart" height="220"></canvas>
                @else
                    <div class="flex flex-col items-center py-10 text-gray-400 dark:text-zinc-600">
                        <span class="material-symbols-outlined text-4xl mb-2">location_on</span>
                        <p class="text-sm">Belum ada data</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- BARIS 3: Metode Bayar (pie) | Status (donut) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- METODE PEMBAYARAN — Pie Chart --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-zinc-800">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Metode Pembayaran</h3>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Distribusi metode pembayaran</p>
            </div>
            <div class="p-5">
                @if($paymentMethods->isNotEmpty())
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <div class="relative flex-shrink-0 w-44 h-44">
                            <canvas id="paymentMethodChart"></canvas>
                        </div>
                        <div class="flex-1 space-y-2 w-full" id="pm-legend"></div>
                    </div>
                @else
                    <div class="flex flex-col items-center py-10 text-gray-400 dark:text-zinc-600">
                        <span class="material-symbols-outlined text-4xl mb-2">payment</span>
                        <p class="text-sm">Belum ada data pembayaran</p>
                        <p class="text-xs mt-1 text-center">Pastikan transaksi Midtrans sudah tersimpan di tabel <code>payments</code></p>
                    </div>
                @endif
            </div>
        </div>

        {{-- STATUS PESANAN — Donut --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-zinc-800">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Status Pesanan</h3>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Distribusi status semua pesanan</p>
            </div>
            <div class="p-5">
                @if($paymentStatuses->isNotEmpty())
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <div class="relative flex-shrink-0 w-44 h-44">
                            <canvas id="paymentStatusChart"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($paymentStatuses->sum('value')) }}</p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400">Pesanan</p>
                            </div>
                        </div>
                        <div class="flex-1 space-y-2 w-full" id="status-legend"></div>
                    </div>
                @else
                    <div class="flex flex-col items-center py-10 text-gray-400 dark:text-zinc-600">
                        <span class="material-symbols-outlined text-4xl mb-2">donut_large</span>
                        <p class="text-sm">Belum ada data</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- BARIS 4: Kategori per Provinsi | Jam Tersibuk --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- KATEGORI PER PROVINSI — Grouped Bar Horizontal --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-zinc-800">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Kontribusi Kategori per Provinsi</h3>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Top 5 provinsi × top 5 kategori</p>
            </div>
            <div class="p-5">
                @if($cpProvince->isNotEmpty())
                    <canvas id="catProvinceChart" height="220"></canvas>
                @else
                    <div class="flex flex-col items-center py-10 text-gray-400 dark:text-zinc-600">
                        <span class="material-symbols-outlined text-4xl mb-2">bar_chart</span>
                        <p class="text-sm">Belum ada data</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- JAM TERSIBUK — Bar --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-zinc-800">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Jam Tersibuk Transaksi</h3>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Jumlah transaksi per jam dalam sehari</p>
            </div>
            <div class="p-5">
                <canvas id="busyHourChart" height="220"></canvas>
            </div>
        </div>

    </div>

    {{-- BARIS 5: Repeat vs New Customer --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- REPEAT VS NEW CUSTOMER --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-zinc-800">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Repeat vs New Customer</h3>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Loyalitas pelanggan dalam periode ini</p>
            </div>
            <div class="p-5">
                @if(($repeatCustomers + $newCustomers) > 0)
                    <div class="flex flex-col items-center gap-4">
                        <div class="relative w-44 h-44">
                            <canvas id="customerTypeChart"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($repeatCustomers + $newCustomers) }}</p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400">Pelanggan</p>
                            </div>
                        </div>
                        <div class="w-full space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-indigo-500"></div>
                                    <span class="text-xs text-gray-600 dark:text-zinc-400">Repeat Customer</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-500 dark:text-zinc-500">{{ number_format($repeatCustomers) }}</span>
                                    <span class="text-xs font-bold text-indigo-500">
                                        {{ ($repeatCustomers + $newCustomers) > 0 ? number_format($repeatCustomers / ($repeatCustomers + $newCustomers) * 100, 1) : 0 }}%
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-cyan-400"></div>
                                    <span class="text-xs text-gray-600 dark:text-zinc-400">New Customer</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-500 dark:text-zinc-500">{{ number_format($newCustomers) }}</span>
                                    <span class="text-xs font-bold text-cyan-400">
                                        {{ ($repeatCustomers + $newCustomers) > 0 ? number_format($newCustomers / ($repeatCustomers + $newCustomers) * 100, 1) : 0 }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center py-10 text-gray-400 dark:text-zinc-600">
                        <span class="material-symbols-outlined text-4xl mb-2">group</span>
                        <p class="text-sm">Belum ada data</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- INSIGHT CARD --}}
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-zinc-800">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Ringkasan Insight</h3>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Kesimpulan otomatis dari data periode ini</p>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Insight: Jam tersibuk --}}
                @php
                    $peakHour = $hourData->search($hourData->max());
                    $peakLabel = str_pad($peakHour, 2, '0', STR_PAD_LEFT) . ':00 - ' . str_pad($peakHour + 1, 2, '0', STR_PAD_LEFT) . ':00';
                @endphp
                <div class="bg-indigo-50 dark:bg-indigo-500/10 rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-lg">schedule</span>
                        <span class="text-xs font-semibold text-indigo-700 dark:text-indigo-400 uppercase">Jam Paling Ramai</span>
                    </div>
                    <p class="text-xl font-bold text-indigo-700 dark:text-indigo-300">{{ $peakLabel }}</p>
                    <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-1">{{ $hourData->max() }} transaksi</p>
                </div>

                {{-- Insight: Provinsi terlaris --}}
                <div class="bg-cyan-50 dark:bg-cyan-500/10 rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-cyan-600 dark:text-cyan-400 text-lg">location_on</span>
                        <span class="text-xs font-semibold text-cyan-700 dark:text-cyan-400 uppercase">Provinsi Terlaris</span>
                    </div>
                    <p class="text-xl font-bold text-cyan-700 dark:text-cyan-300">{{ optional($topProvinces->first())->province ?? '-' }}</p>
                    <p class="text-xs text-cyan-600 dark:text-cyan-400 mt-1">{{ number_format(optional($topProvinces->first())->total_orders ?? 0) }} pesanan</p>
                </div>

                {{-- Insight: Repeat customer rate --}}
                <div class="bg-emerald-50 dark:bg-emerald-500/10 rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-lg">loyalty</span>
                        <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 uppercase">Tingkat Loyalitas</span>
                    </div>
                    @php $loyalRate = ($repeatCustomers + $newCustomers) > 0 ? round($repeatCustomers / ($repeatCustomers + $newCustomers) * 100, 1) : 0; @endphp
                    <p class="text-xl font-bold text-emerald-700 dark:text-emerald-300">{{ $loyalRate }}%</p>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">pelanggan melakukan repeat order</p>
                </div>

                {{-- Insight: Metode bayar terpopuler --}}
                <div class="bg-amber-50 dark:bg-amber-500/10 rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-lg">credit_card</span>
                        <span class="text-xs font-semibold text-amber-700 dark:text-amber-400 uppercase">Metode Bayar Favorit</span>
                    </div>
                    <p class="text-xl font-bold text-amber-700 dark:text-amber-300">{{ optional($paymentMethods->first())['label'] ?? '-' }}</p>
                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">{{ number_format(optional($paymentMethods->first())['value'] ?? 0) }} transaksi</p>
                </div>
            </div>
        </div>

    </div>

    {{-- TABEL PENJUALAN --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-200 dark:border-zinc-800">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Tabel Penjualan</h3>
            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">
                Menampilkan {{ $salesTable->firstItem() ?? 0 }}–{{ $salesTable->lastItem() ?? 0 }}
                dari {{ $salesTable->total() }} transaksi
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-zinc-800/50 border-b border-gray-200 dark:border-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-zinc-400 uppercase whitespace-nowrap">No. Pesanan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-zinc-400 uppercase whitespace-nowrap">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-zinc-400 uppercase whitespace-nowrap">Provinsi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-zinc-400 uppercase whitespace-nowrap">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-zinc-400 uppercase whitespace-nowrap">Produk</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-zinc-400 uppercase whitespace-nowrap">Qty</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-zinc-400 uppercase whitespace-nowrap">Subtotal</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-zinc-400 uppercase whitespace-nowrap">Ongkir</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-zinc-400 uppercase whitespace-nowrap">Total</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-zinc-400 uppercase whitespace-nowrap">Metode Bayar</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-zinc-400 uppercase whitespace-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                    @forelse($salesTable as $order)
                        @php
                            $itemCount     = $order->items->sum('quantity');
                            $productNames  = $order->items->map(fn($i) => optional($i->product)->name ?? 'Dihapus')->implode(', ');
                            $categoryNames = $order->items->map(fn($i) => optional(optional($i->product)->category)->name ?? '-')->unique()->implode(', ');
                            $pmLabel = match(optional($order->payment)->payment_type) {
                                'bank_transfer' => 'Transfer Bank',
                                'echannel'      => 'Mandiri',
                                'cstore'        => 'Minimarket',
                                'gopay'         => 'GoPay',
                                'qris'          => 'QRIS',
                                'shopeepay'     => 'ShopeePay',
                                'credit_card'   => 'Kartu Kredit',
                                default         => $order->payment_method ? ucfirst(str_replace('_',' ',$order->payment_method)) : '-',
                            };
                            $statusColor = match($order->status) {
                                'pending'    => 'bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400',
                                'paid'       => 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400',
                                'processing' => 'bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400',
                                'shipped'    => 'bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400',
                                'completed'  => 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400',
                                'cancelled'  => 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400',
                                default      => 'bg-gray-100 dark:bg-gray-500/20 text-gray-700 dark:text-gray-400',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/40 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap font-semibold text-soft-green">#{{ $order->order_number }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-700 dark:text-zinc-300">
                                {{ $order->created_at->format('d M Y') }}<br>
                                <span class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-700 dark:text-zinc-300">{{ $order->province ?? '-' }}</td>
                            <td class="px-4 py-3 max-w-[110px]"><span class="truncate block text-gray-700 dark:text-zinc-300" title="{{ $categoryNames }}">{{ $categoryNames }}</span></td>
                            <td class="px-4 py-3 max-w-[150px]"><span class="truncate block text-gray-700 dark:text-zinc-300" title="{{ $productNames }}">{{ $productNames }}</span></td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">{{ $itemCount }}</td>
                            <td class="px-4 py-3 text-right whitespace-nowrap text-gray-700 dark:text-zinc-300">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right whitespace-nowrap text-gray-700 dark:text-zinc-300">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right whitespace-nowrap font-bold text-gray-900 dark:text-white">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap text-gray-600 dark:text-zinc-400 text-xs">{{ $pmLabel }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">{{ $order->status_label }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-5xl mb-3">search_off</span>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Tidak ada data transaksi</p>
                                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Coba ubah filter atau rentang tanggal</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($salesTable->hasPages())
            <div class="px-5 py-4 border-t border-gray-200 dark:border-zinc-800">
                {{ $salesTable->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const isDark    = document.documentElement.classList.contains('dark');
const gridColor = isDark ? 'rgba(113,113,122,0.12)' : 'rgba(156,163,175,0.15)';
const tickColor = isDark ? '#71717a' : '#9ca3af';
const tooltipBg = isDark ? 'rgba(24,24,27,0.96)' : 'rgba(15,15,15,0.88)';
const PALETTE   = ['#6366f1','#06b6d4','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6'];

const STATUS_COLORS = {
    pending: '#f59e0b', paid: '#3b82f6', processing: '#8b5cf6',
    shipped: '#6366f1', completed: '#10b981', cancelled: '#ef4444',
};

function fmtRp(v) {
    if (v >= 1e9) return 'Rp ' + (v/1e9).toFixed(1) + 'M';
    if (v >= 1e6) return 'Rp ' + (v/1e6).toFixed(1) + 'jt';
    if (v >= 1e3) return 'Rp ' + (v/1e3).toFixed(0) + 'k';
    return 'Rp ' + v;
}

const percentagePlugin = {
    id: 'percentageLabels',
    afterDatasetsDraw(chart) {
        const { ctx } = chart;
        const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
        if (!total) return;
        chart.data.datasets.forEach((dataset, di) => {
            chart.getDatasetMeta(di).data.forEach((arc, i) => {
                const val = dataset.data[i];
                const pct = ((val / total) * 100).toFixed(1);
                if (parseFloat(pct) < 5) return;
                const { x, y } = arc.tooltipPosition();
                ctx.save();
                ctx.fillStyle = '#fff';
                ctx.font = 'bold 11px Inter, sans-serif';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(pct + '%', x, y);
                ctx.restore();
            });
        });
    }
};

// ═══════════════════════════════════════════════
// 1. TREN PENDAPATAN — Line
// ═══════════════════════════════════════════════
const revCtx = document.getElementById('revenueChart');
if (revCtx) {
    const ctx2d = revCtx.getContext('2d');
    const grad  = ctx2d.createLinearGradient(0, 0, 0, 280);
    grad.addColorStop(0, 'rgba(99,102,241,0.28)');
    grad.addColorStop(1, 'rgba(99,102,241,0.02)');
    new Chart(ctx2d, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartDates) !!},
            datasets: [{
                label: 'Pendapatan', data: {!! json_encode($chartRevenues) !!},
                borderColor: '#6366f1', backgroundColor: grad,
                borderWidth: 2.5, fill: true, tension: 0.4,
                pointRadius: 3, pointHoverRadius: 6,
                pointBackgroundColor: '#6366f1', pointBorderColor: '#fff', pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: tooltipBg, padding: 12, borderRadius: 10,
                    displayColors: false,
                    callbacks: { label: c => 'Rp ' + c.parsed.y.toLocaleString('id-ID') }
                }
            },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { color: tickColor, font: { size: 11 }, maxTicksLimit: 14, maxRotation: 45 } },
                y: { beginAtZero: true, grid: { color: gridColor }, border: { display: false }, ticks: { color: tickColor, font: { size: 11 }, padding: 8, maxTicksLimit: 6, callback: fmtRp } }
            }
        }
    });
}

// ═══════════════════════════════════════════════
// 2. TOP 5 PRODUK — Bar Horizontal
// ═══════════════════════════════════════════════
const prodCtx = document.getElementById('productChart');
if (prodCtx) {
    // Pecah nama panjang jadi multi-line (maks 20 karakter per baris)
    function wrapLabel(str, maxLen) {
        const words = str.split(' ');
        const lines = [];
        let current = '';
        words.forEach(word => {
            if ((current + ' ' + word).trim().length > maxLen) {
                if (current) lines.push(current.trim());
                current = word;
            } else {
                current = (current + ' ' + word).trim();
            }
        });
        if (current) lines.push(current.trim());
        return lines;
    }

    const prodRawLabels = {!! json_encode($topProducts->pluck('name')) !!};
    const prodLabels    = prodRawLabels.map(n => wrapLabel(n, 20));
    const prodData      = {!! json_encode($topProducts->pluck('total_sold')->map(fn($v) => (int)$v)) !!};

    new Chart(prodCtx, {
        type: 'bar',
        data: {
            labels: prodLabels,
            datasets: [{
                label: 'Terjual',
                data: prodData,
                backgroundColor: PALETTE.slice(0, prodData.length),
                borderRadius: 5,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            layout: { padding: { right: 10 } },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: tooltipBg, padding: 10, borderRadius: 8,
                    callbacks: {
                        // Tooltip tampilkan nama asli (flat), bukan array
                        title: (items) => prodRawLabels[items[0].dataIndex],
                        label: c => '  ' + c.parsed.x.toLocaleString('id-ID') + ' item',
                    }
                },
                // Label nilai di ujung bar
                datalabels: false,
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: gridColor },
                    border: { display: false },
                    ticks: { color: tickColor, font: { size: 11 }, precision: 0 }
                },
                y: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: {
                        color: tickColor,
                        font: { size: 11 },
                        // Pastikan multi-line label tidak terpotong
                        crossAlign: 'far',
                    }
                }
            }
        }
    });
}

// ═══════════════════════════════════════════════
// 3. TOP 5 KATEGORI — Pie
// ═══════════════════════════════════════════════
const catCtx = document.getElementById('categoryChart');
if (catCtx) {
    const catLabels = {!! json_encode($topCategories->pluck('name')) !!};
    const catData   = {!! json_encode($topCategories->pluck('total_sold')->map(fn($v) => (int)$v)) !!};
    const catColors = PALETTE.slice(0, catData.length);
    const catTotal  = catData.reduce((a, b) => a + b, 0);
    new Chart(catCtx, {
        type: 'pie',
        data: { labels: catLabels, datasets: [{ data: catData, backgroundColor: catColors, borderWidth: 2, borderColor: isDark ? '#18181b' : '#fff', hoverOffset: 6 }] },
        options: {
            responsive: true, maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: tooltipBg, padding: 10, borderRadius: 8, callbacks: { label: c => { const pct = catTotal ? ((c.parsed / catTotal) * 100).toFixed(1) : 0; return ` ${c.label}: ${c.parsed.toLocaleString('id-ID')} (${pct}%)`; } } }
            }
        },
        plugins: [percentagePlugin]
    });
    const legend = document.getElementById('cat-legend');
    if (legend) {
        catLabels.forEach((lbl, i) => {
            const pct = catTotal ? ((catData[i] / catTotal) * 100).toFixed(1) : '0.0';
            legend.innerHTML += `<div class="flex items-center justify-between"><div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full flex-shrink-0" style="background:${catColors[i]}"></div><span class="text-xs text-gray-600 dark:text-zinc-400 truncate max-w-[140px]">${lbl}</span></div><span class="text-xs font-bold text-gray-900 dark:text-white ml-2">${pct}%</span></div>`;
        });
    }
}

// ═══════════════════════════════════════════════
// 4. TOP 5 PROVINSI — Bar Vertikal
// ═══════════════════════════════════════════════
const provCtx = document.getElementById('provinceChart');
if (provCtx) {
    const provLabels = {!! json_encode($topProvinces->pluck('province')->map(fn($n) => strlen($n) > 16 ? substr($n,0,14).'…' : $n)) !!};
    const provData   = {!! json_encode($topProvinces->pluck('total_orders')->map(fn($v) => (int)$v)) !!};
    new Chart(provCtx, {
        type: 'bar',
        data: { labels: provLabels, datasets: [{ label: 'Pesanan', data: provData, backgroundColor: PALETTE.slice(0,5), borderRadius: 6, borderSkipped: false }] },
        options: {
            responsive: true, maintainAspectRatio: true,
            plugins: { legend: { display: false }, tooltip: { backgroundColor: tooltipBg, padding: 10, borderRadius: 8, callbacks: { label: c => ' ' + c.parsed.y.toLocaleString('id-ID') + ' pesanan' } } },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { color: tickColor, font: { size: 11 } } },
                y: { beginAtZero: true, grid: { color: gridColor }, border: { display: false }, ticks: { color: tickColor, font: { size: 11 }, precision: 0 } }
            }
        }
    });
}

// ═══════════════════════════════════════════════
// 5. METODE PEMBAYARAN — Pie Chart
// ═══════════════════════════════════════════════
const pmCtx = document.getElementById('paymentMethodChart');
if (pmCtx) {
    const pmRaw    = {!! json_encode($paymentMethods->values()) !!};
    const pmLabels = pmRaw.map(x => x.label);
    const pmData   = pmRaw.map(x => x.value);
    const pmColors = PALETTE.slice(0, pmData.length);
    const pmTotal  = pmData.reduce((a, b) => a + b, 0);
    new Chart(pmCtx, {
        type: 'pie',
        data: { labels: pmLabels, datasets: [{ data: pmData, backgroundColor: pmColors, borderWidth: 2, borderColor: isDark ? '#18181b' : '#fff', hoverOffset: 6 }] },
        options: {
            responsive: true, maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: tooltipBg, padding: 10, borderRadius: 8, callbacks: { label: c => { const pct = pmTotal ? ((c.parsed / pmTotal) * 100).toFixed(1) : 0; return ` ${c.label}: ${c.parsed.toLocaleString('id-ID')} transaksi (${pct}%)`; } } }
            }
        },
        plugins: [percentagePlugin]
    });
    const pmLegend = document.getElementById('pm-legend');
    if (pmLegend) {
        pmRaw.forEach((item, i) => {
            const pct = pmTotal ? ((item.value / pmTotal) * 100).toFixed(1) : '0.0';
            pmLegend.innerHTML += `<div class="flex items-center justify-between"><div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full flex-shrink-0" style="background:${pmColors[i]}"></div><span class="text-xs text-gray-600 dark:text-zinc-400">${item.label}</span></div><div class="flex items-center gap-2 ml-2"><span class="text-xs text-gray-500 dark:text-zinc-500">${item.value.toLocaleString('id-ID')}</span><span class="text-xs font-bold" style="color:${pmColors[i]}">${pct}%</span></div></div>`;
        });
    }
}

// ═══════════════════════════════════════════════
// 6. STATUS PESANAN — Donut
// ═══════════════════════════════════════════════
const psCtx = document.getElementById('paymentStatusChart');
if (psCtx) {
    const psRaw    = {!! json_encode($paymentStatuses->values()) !!};
    const psLabels = psRaw.map(x => x.label);
    const psData   = psRaw.map(x => x.value);
    const psColors = psRaw.map(x => STATUS_COLORS[x.key] ?? '#9ca3af');
    const psTotal  = psData.reduce((a, b) => a + b, 0);
    new Chart(psCtx, {
        type: 'doughnut',
        data: { labels: psLabels, datasets: [{ data: psData, backgroundColor: psColors, borderWidth: 3, borderColor: isDark ? '#18181b' : '#fff', hoverOffset: 6 }] },
        options: {
            responsive: true, maintainAspectRatio: true, cutout: '55%',
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: tooltipBg, padding: 10, borderRadius: 8, callbacks: { label: c => { const pct = psTotal ? ((c.parsed / psTotal) * 100).toFixed(1) : 0; return ` ${c.label}: ${c.parsed.toLocaleString('id-ID')} (${pct}%)`; } } }
            }
        },
        plugins: [percentagePlugin]
    });
    const statusLegend = document.getElementById('status-legend');
    if (statusLegend) {
        psRaw.forEach((item, i) => {
            const pct = psTotal ? ((item.value / psTotal) * 100).toFixed(1) : '0.0';
            statusLegend.innerHTML += `<div class="flex items-center justify-between"><div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full flex-shrink-0" style="background:${psColors[i]}"></div><span class="text-xs text-gray-600 dark:text-zinc-400">${item.label}</span></div><div class="flex items-center gap-2 ml-2"><span class="text-xs text-gray-500 dark:text-zinc-500">${item.value.toLocaleString('id-ID')}</span><span class="text-xs font-bold" style="color:${psColors[i]}">${pct}%</span></div></div>`;
        });
    }
}

// ═══════════════════════════════════════════════
// 7. KATEGORI PER PROVINSI — Grouped Bar Horizontal
// ═══════════════════════════════════════════════
const cpCtx = document.getElementById('catProvinceChart');
if (cpCtx) {
    const cpDatasets = {!! json_encode($cpDatasets->values()) !!};
    const cpLabels   = {!! json_encode($cpProvince->values()) !!};
    new Chart(cpCtx, {
        type: 'bar',
        data: { labels: cpLabels, datasets: cpDatasets },
        options: {
            indexAxis: 'y',
            responsive: true, maintainAspectRatio: true,
            plugins: {
                legend: { display: true, position: 'bottom', labels: { color: tickColor, font: { size: 11 }, boxWidth: 12, padding: 10 } },
                tooltip: { backgroundColor: tooltipBg, padding: 10, borderRadius: 8, callbacks: { label: c => ` ${c.dataset.label}: ${c.parsed.x.toLocaleString('id-ID')} item` } }
            },
            scales: {
                x: { beginAtZero: true, stacked: false, grid: { color: gridColor }, border: { display: false }, ticks: { color: tickColor, font: { size: 11 }, precision: 0 } },
                y: { stacked: false, grid: { display: false }, border: { display: false }, ticks: { color: tickColor, font: { size: 11 } } }
            }
        }
    });
}

// ═══════════════════════════════════════════════
// 8. JAM TERSIBUK — Bar
// ═══════════════════════════════════════════════
const bhCtx = document.getElementById('busyHourChart');
if (bhCtx) {
    const hourLabels = {!! json_encode($hourLabels) !!};
    const hourData   = {!! json_encode($hourData) !!};
    const maxVal     = Math.max(...hourData);
    const bgColors   = hourData.map(v => v === maxVal && maxVal > 0 ? '#6366f1' : 'rgba(99,102,241,0.35)');
    new Chart(bhCtx, {
        type: 'bar',
        data: { labels: hourLabels, datasets: [{ label: 'Transaksi', data: hourData, backgroundColor: bgColors, borderRadius: 3, borderSkipped: false }] },
        options: {
            responsive: true, maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: tooltipBg, padding: 10, borderRadius: 8, callbacks: { label: c => ' ' + c.parsed.y.toLocaleString('id-ID') + ' transaksi' } }
            },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { color: tickColor, font: { size: 10 }, maxRotation: 45 } },
                y: { beginAtZero: true, grid: { color: gridColor }, border: { display: false }, ticks: { color: tickColor, font: { size: 11 }, precision: 0 } }
            }
        }
    });
}

// ═══════════════════════════════════════════════
// 9. REPEAT VS NEW CUSTOMER — Donut
// ═══════════════════════════════════════════════
const ctCtx = document.getElementById('customerTypeChart');
if (ctCtx) {
    new Chart(ctCtx, {
        type: 'doughnut',
        data: {
            labels: ['Repeat Customer', 'New Customer'],
            datasets: [{
                data: [{{ $repeatCustomers }}, {{ $newCustomers }}],
                backgroundColor: ['#6366f1', '#06b6d4'],
                borderWidth: 3, borderColor: isDark ? '#18181b' : '#fff', hoverOffset: 6,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: true, cutout: '55%',
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: tooltipBg, padding: 10, borderRadius: 8 }
            }
        }
    });
}

// ═══════════════════════════════════════════════
// SHORTCUT TANGGAL
// ═══════════════════════════════════════════════
function fmt(d) { return d.toISOString().split('T')[0]; }
function setRange(days) {
    const today = new Date(), from = new Date();
    from.setDate(today.getDate() - (days - 1));
    document.querySelector('[name=date_from]').value = fmt(from);
    document.querySelector('[name=date_to]').value   = fmt(today);
    document.getElementById('filterForm').submit();
}
function setThisMonth() {
    const now = new Date();
    document.querySelector('[name=date_from]').value = fmt(new Date(now.getFullYear(), now.getMonth(), 1));
    document.querySelector('[name=date_to]').value   = fmt(now);
    document.getElementById('filterForm').submit();
}
function setThisYear() {
    const now = new Date();
    document.querySelector('[name=date_from]').value = fmt(new Date(now.getFullYear(), 0, 1));
    document.querySelector('[name=date_to]').value   = fmt(now);
    document.getElementById('filterForm').submit();
}
</script>
@endpush
@endsection