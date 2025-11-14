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
                        <span class="px-3 py-1.5 text-xs font-medium text-soft-green dark:text-soft-green bg-soft-green/10 dark:bg-soft-green/10 rounded-lg border border-soft-green/20">
                            7 Hari
                        </span>
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

        <!-- Recent Orders -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pesanan Terbaru</h3>
                        <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">5 pesanan terakhir</p>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-soft-green hover:text-primary transition-colors flex items-center gap-1">
                        Lihat Semua
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($recentOrders ?? [] as $order)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors group">
                            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-soft-green/20 to-primary/20 rounded-lg flex items-center justify-center">
                                <span class="material-symbols-outlined text-soft-green text-xl">receipt_long</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 dark:text-white truncate">
                                    {{ $order->order_number ?? 'ORD-' . $order->id }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">
                                    {{ $order->user->name ?? 'Customer' }} • {{ $order->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <p class="text-sm font-bold text-gray-900 dark:text-white">Rp {{ number_format($order->grand_total ?? 0, 0, ',', '.') }}</p>
                                @switch($order->status)
                                    @case('pending')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                            bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400 mt-1">
                                            Pending
                                        </span>
                                        @break

                                    @case('paid')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                            bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 mt-1">
                                            Sudah Dibayar
                                        </span>
                                        @break

                                    @case('processing')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                            bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400 mt-1">
                                            Diproses
                                        </span>
                                        @break

                                    @case('shipped')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                            bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400 mt-1">
                                            Dikirim
                                        </span>
                                        @break

                                    @case('completed')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                            bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 mt-1">
                                            Selesai
                                        </span>
                                        @break

                                    @case('cancelled')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                            bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 mt-1">
                                            Dibatalkan
                                        </span>
                                        @break

                                    @default
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                            bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-400 mt-1">
                                            Unknown
                                        </span>
                                @endswitch

                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-5xl">receipt_long</span>
                            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-2">Belum ada pesanan</p>
                        </div>
                    @endforelse
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
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($dates->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
            datasets: [{
                label: 'Pendapatan',
                data: @json($revenues),
                backgroundColor: function(context) {
                    const chart = context.chart;
                    const {ctx, chartArea} = chart;
                    
                    if (!chartArea) {
                        return '#7BB661';
                    }
                    
                    const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                    gradient.addColorStop(0, 'rgba(123, 182, 97, 0.6)');
                    gradient.addColorStop(1, 'rgba(123, 182, 97, 1)');
                    return gradient;
                },
                borderRadius: 8,
                borderSkipped: false,
                barPercentage: 0.7,
                categoryPercentage: 0.8,
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