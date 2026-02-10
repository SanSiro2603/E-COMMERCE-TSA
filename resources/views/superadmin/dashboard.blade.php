{{-- resources/views/superadmin/dashboard.blade.php --}}
@extends('layouts.superadmin')

@section('title', 'Dashboard Super Admin - E-Commerce TSA')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-display">Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Selamat datang kembali, {{ Auth::user()->name }}! 👋</p>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <!-- Total Revenue Card -->
        <div class="card-hover bg-white dark:bg-zinc-900 p-6 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-zinc-400">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <div class="flex items-center gap-1 mt-3">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 {{ $revenueGrowth >= 0 ? 'bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400' : 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400' }} text-xs font-medium rounded-full">
                            <span class="material-symbols-outlined text-sm">{{ $revenueGrowth >= 0 ? 'trending_up' : 'trending_down' }}</span>
                            {{ $revenueGrowth >= 0 ? '+' : '' }}{{ number_format($revenueGrowth, 1) }}%
                        </span>
                        <span class="text-xs text-gray-500 dark:text-zinc-400">vs bulan lalu</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-green-50 dark:bg-green-500/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">payments</span>
                </div>
            </div>
        </div>

        <!-- Total Orders Card -->
        <div class="card-hover bg-white dark:bg-zinc-900 p-6 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-zinc-400">Total Pesanan</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($totalOrders) }}</p>
                    <div class="flex items-center gap-1 mt-3">
                        <span class="text-xs text-gray-500 dark:text-zinc-400">Semua waktu</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-500/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-2xl">shopping_cart</span>
                </div>
            </div>
        </div>

        <!-- Total Customers Card -->
        <div class="card-hover bg-white dark:bg-zinc-900 p-6 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-zinc-400">Total Pelanggan</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($totalCustomers) }}</p>
                    <div class="flex items-center gap-1 mt-3">
                        <span class="text-xs text-gray-500 dark:text-zinc-400">Pengguna terdaftar</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-purple-50 dark:bg-purple-500/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-2xl">person</span>
                </div>
            </div>
        </div>

        <!-- Total Products Card -->
        <div class="card-hover bg-white dark:bg-zinc-900 p-6 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-zinc-400">Total Produk</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($totalProducts) }}</p>
                    <div class="flex items-center gap-1 mt-3">
                        <span class="text-xs text-gray-500 dark:text-zinc-400">{{ $totalAdmins }} Admin mengelola</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-orange-50 dark:bg-orange-500/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-orange-600 dark:text-orange-400 text-2xl">inventory_2</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Order Stats Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Chart - Takes 2 columns -->
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Grafik Pendapatan</h3>
                        <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">30 hari terakhir</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Hari Ini</p>
                            <p class="text-lg font-bold text-green-600 dark:text-green-400">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Bulan Ini</p>
                            <p class="text-lg font-bold text-blue-600 dark:text-blue-400">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <canvas id="revenueChart" height="80"></canvas>
            </div>
        </div>

        <!-- Order Status Stats -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Status Pesanan</h3>
                <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Ringkasan status</p>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-500/10 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-xl">hourglass_top</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Pending</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($orderStats['pending']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-500/10 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-xl">paid</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Dibayar</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($orderStats['paid']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-500/10 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-xl">pending</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Diproses</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($orderStats['processing']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-500/10 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-xl">local_shipping</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Dikirim</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($orderStats['shipped']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-500/10 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-xl">check_circle</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Selesai</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($orderStats['completed']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-500/10 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-xl">cancel</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Dibatalkan</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($orderStats['cancelled']) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products & Admin Performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Products -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Produk Terlaris</h3>
                        <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Top 5 produk bulan ini</p>
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-soft-green hover:text-primary transition-colors flex items-center gap-1">
                        Lihat Semua
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($topProducts as $index => $product)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors group">
                            <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-soft-green to-primary rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-md">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-shrink-0 w-14 h-14 bg-gray-200 dark:bg-zinc-700 rounded-lg overflow-hidden border border-gray-300 dark:border-zinc-600">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-2xl">inventory_2</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 dark:text-white truncate group-hover:text-soft-green transition-colors">
                                    {{ Str::limit($product->name, 25) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400 flex items-center gap-1 mt-0.5">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <p class="text-lg font-bold text-soft-green dark:text-soft-green">{{ $product->total_sold ?? 0 }}</p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400">terjual</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-5xl">inventory_2</span>
                            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-2">Belum ada data produk terjual bulan ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Admin Performance -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Performa Admin</h3>
                        <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Admin terbaik bulan ini</p>
                    </div>
                    <a href="{{ route('superadmin.admins.index') }}" class="text-sm font-medium text-soft-green hover:text-primary transition-colors flex items-center gap-1">
                        Lihat Semua
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($adminPerformance as $index => $admin)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors group">
                            <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-soft-green to-primary rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-md">
                                {{ $index + 1 }}
                            </div>
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 dark:text-white truncate group-hover:text-soft-green transition-colors">
                                    {{ $admin->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400 truncate mt-0.5">
                                    {{ $admin->email }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <p class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ number_format($admin->orders_handled ?? 0) }}</p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400">pesanan</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-5xl">group</span>
                            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-2">Belum ada data admin</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pesanan Terbaru</h3>
                    <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">10 pesanan terakhir</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-soft-green hover:text-primary transition-colors flex items-center gap-1">
                    Lihat Semua
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-zinc-800/50 border-b border-gray-200 dark:border-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">No. Pesanan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Pembeli</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-800">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">#{{ $order->order_number }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-md">
                                    {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-zinc-400">{{ $order->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-gray-900 dark:text-white">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @switch($order->status)
                                @case('pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400">
                                        Pending
                                    </span>
                                    @break

                                @case('paid')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400">
                                        Sudah Dibayar
                                    </span>
                                    @break

                                @case('processing')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400">
                                        Diproses
                                    </span>
                                    @break

                                @case('shipped')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400">
                                        Dikirim
                                    </span>
                                    @break

                                @case('completed')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400">
                                        Selesai
                                    </span>
                                    @break

                                @case('cancelled')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400">
                                        Dibatalkan
                                    </span>
                                    @break

                                @default
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-400">
                                        Unknown
                                    </span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-900 dark:text-white">{{ $order->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">{{ $order->created_at->format('H:i') }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-5xl">shopping_bag</span>
                            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-2">Belum ada pesanan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Detect dark mode
    const isDarkMode = document.documentElement.classList.contains('dark');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dates) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode($revenues) !!},
                borderColor: '#667eea',
                backgroundColor: function(context) {
                    const chart = context.chart;
                    const {ctx, chartArea} = chart;
                    
                    if (!chartArea) {
                        return 'rgba(102, 126, 234, 0.1)';
                    }
                    
                    const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                    gradient.addColorStop(0, 'rgba(102, 126, 234, 0.05)');
                    gradient.addColorStop(1, 'rgba(102, 126, 234, 0.3)');
                    return gradient;
                },
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: isDarkMode ? 'rgba(24, 24, 27, 0.95)' : 'rgba(0, 0, 0, 0.85)',
                    padding: 16,
                    borderRadius: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    titleFont: {
                        size: 13,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 14,
                        weight: '700'
                    },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    },
                    yAlign: 'bottom',
                    caretSize: 0,
                    caretPadding: 10
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    border: {
                        display: false
                    },
                    ticks: {
                        color: isDarkMode ? '#71717a' : '#9ca3af',
                        font: {
                            size: 11,
                            weight: '500'
                        },
                        padding: 8
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: isDarkMode ? 'rgba(113, 113, 122, 0.1)' : 'rgba(156, 163, 175, 0.1)',
                        drawBorder: false,
                        lineWidth: 1
                    },
                    border: {
                        display: false,
                        dash: [5, 5]
                    },
                    ticks: {
                        color: isDarkMode ? '#71717a' : '#9ca3af',
                        font: {
                            size: 11,
                            weight: '500'
                        },
                        padding: 12,
                        callback: function(value) {
                            if (value === 0) return '0';
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000) + 'jt';
                            }
                            return 'Rp ' + (value / 1000) + 'k';
                        },
                        maxTicksLimit: 6
                    }
                }
            },
            layout: {
                padding: {
                    top: 10,
                    right: 0,
                    bottom: 0,
                    left: 0
                }
            }
        }
    });
</script>
@endpush
@endsection