{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard Admin - Lembah Hijau')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Selamat datang kembali, {{ Auth::user()->name }}! 👋</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-zinc-200 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">download</span>
                Export
            </button>
            
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <!-- Total Orders Card -->
        <div class="card-hover bg-white dark:bg-zinc-900 p-6 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-zinc-400">Total Pesanan</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($totalOrders) }}</p>
                    <div class="flex items-center gap-1 mt-3">
                        <span class="text-xs text-gray-500 dark:text-zinc-400">
                            Selesai: <span class="font-semibold text-green-600 dark:text-green-400">{{ $completedOrders }}</span>
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-500/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-2xl">shopping_cart</span>
                </div>
            </div>
        </div>

        <!-- Pending Orders Card -->
        <div class="card-hover bg-white dark:bg-zinc-900 p-6 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-zinc-400">Menunggu Konfirmasi</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $pendingOrders }}</p>
                    <div class="mt-3">
                        @if($pendingOrders > 0)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-yellow-50 dark:bg-yellow-500/10 text-yellow-700 dark:text-yellow-400 text-xs font-medium rounded-full">
                                <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full animate-pulse"></span>
                                Perlu tindakan
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 text-xs font-medium rounded-full">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                Semua terproses
                            </span>
                        @endif
                    </div>
                </div>
                <div class="w-12 h-12 bg-yellow-50 dark:bg-yellow-500/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-2xl">hourglass_top</span>
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="card-hover bg-white dark:bg-zinc-900 p-6 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-zinc-400">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <div class="flex items-center gap-1 mt-3">
                        <span class="text-xs text-gray-500 dark:text-zinc-400">
                            Hari ini: <span class="font-semibold text-green-600 dark:text-green-400">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</span>
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-green-50 dark:bg-green-500/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">attach_money</span>
                </div>
            </div>
        </div>

        <!-- Low Stock Card -->
        <div class="card-hover bg-white dark:bg-zinc-900 p-6 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-zinc-400">Stok Rendah</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $lowStockProducts }}</p>
                    <div class="mt-3">
                        @if($lowStockProducts > 0)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 text-xs font-medium rounded-full">
                                <span class="material-symbols-outlined text-sm">warning</span>
                                Segera restock
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 text-xs font-medium rounded-full">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                Stok aman
                            </span>
                        @endif
                    </div>
                </div>
                <div class="w-12 h-12 bg-red-50 dark:bg-red-500/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-2xl">inventory</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Tables Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Chart - Takes 2 columns -->
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Grafik Pendapatan</h3>
                        <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Performa 7 hari terakhir</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="px-3 py-1.5 text-xs font-medium text-white dark:text-white bg-soft-green dark:bg-soft-green rounded-lg">
                            7 Hari
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <canvas id="revenueChart" height="80"></canvas>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Statistik Cepat</h3>
                <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Ringkasan sistem</p>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-500/10 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-xl">person</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Total Pelanggan</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($totalCustomers) }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-500/10 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-orange-600 dark:text-orange-400 text-xl">inventory_2</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Total Produk</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($totalProducts) }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-cyan-100 dark:bg-cyan-500/10 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-cyan-600 dark:text-cyan-400 text-xl">pending</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Diproses</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($processingOrders) }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-pink-100 dark:bg-pink-500/10 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-pink-600 dark:text-pink-400 text-xl">check_circle</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Selesai</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($completedOrders) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products & Recent Orders -->
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
                                    <span class="material-symbols-outlined text-sm">category</span>
                                    {{ $product->category->name ?? 'Uncategorized' }}
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

        <!-- Summary Info -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ringkasan Pesanan</h3>
                        <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Status pesanan saat ini</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Pending Orders -->
                    <div class="flex items-start gap-3 p-4 bg-yellow-50 dark:bg-yellow-500/10 rounded-lg border border-yellow-200 dark:border-yellow-500/20">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 dark:bg-yellow-500/20 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-xl">hourglass_top</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Menunggu Konfirmasi</p>
                            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ number_format($pendingOrders) }}</p>
                            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Pesanan perlu dikonfirmasi</p>
                        </div>
                    </div>

                    <!-- Processing Orders -->
                    <div class="flex items-start gap-3 p-4 bg-blue-50 dark:bg-blue-500/10 rounded-lg border border-blue-200 dark:border-blue-500/20">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-500/20 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-xl">local_shipping</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Sedang Diproses</p>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ number_format($processingOrders) }}</p>
                            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Pesanan dalam pengiriman</p>
                        </div>
                    </div>

                    <!-- Completed Orders -->
                    <div class="flex items-start gap-3 p-4 bg-green-50 dark:bg-green-500/10 rounded-lg border border-green-200 dark:border-green-500/20">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-500/20 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-xl">check_circle</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Selesai</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ number_format($completedOrders) }}</p>
                            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Pesanan berhasil diselesaikan</p>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-soft-green to-primary text-white rounded-lg hover:shadow-lg transition-all">
                        <span class="material-symbols-outlined text-lg">visibility</span>
                        <span class="text-sm font-medium">Lihat Semua Pesanan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Detect dark mode
    const isDarkMode = document.documentElement.classList.contains('dark');
    
    // Create gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(123, 182, 97, 0.3)');
    gradient.addColorStop(1, 'rgba(123, 182, 97, 0.01)');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($dates->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
            datasets: [{
                label: 'Pendapatan',
                data: @json($revenues),
                borderColor: '#7BB661',
                backgroundColor: gradient,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointBackgroundColor: '#7BB661',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 7,
                pointHoverBackgroundColor: '#7BB661',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 3
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
                    backgroundColor: isDarkMode ? 'rgba(24, 24, 27, 0.95)' : 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    borderRadius: 8,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: isDarkMode ? '#a1a1aa' : '#9ca3af',
                        font: {
                            size: 12
                        }
                    }
                },
                y: { 
                    beginAtZero: true,
                    grid: {
                        color: isDarkMode ? 'rgba(161, 161, 170, 0.1)' : 'rgba(156, 163, 175, 0.1)',
                        drawBorder: false
                    },
                    ticks: { 
                        color: isDarkMode ? '#a1a1aa' : '#9ca3af',
                        font: {
                            size: 12
                        },
                        callback: value => 'Rp ' + (value / 1000) + 'k'
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection