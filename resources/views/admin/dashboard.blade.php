@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ringkasan bisnis Lembah Hijau hari ini</p>
        </div>
        <div class="flex items-center gap-2">
            <button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <span class="material-symbols-outlined text-lg">download</span>
                Export
            </button>
            {{-- <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition shadow-sm">
                <span class="material-symbols-outlined text-lg">add</span>
                Tambah Produk
            </a> --}}
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Pendapatan -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-primary-100 dark:bg-primary-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-primary-600 dark:text-primary-400 text-xl">payments</span>
                </div>
                @php
                    $revenueChange = $totalRevenue > 0 ? (($todayRevenue / $totalRevenue) * 100) : 0;
                @endphp
                <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                    <span class="material-symbols-outlined text-sm">trending_up</span>
                    {{ number_format($revenueChange, 1) }}%
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Pendapatan</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalRevenue / 1000000, 1) }} Juta</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Dari pesanan selesai</p>
            </div>
        </div>

        <!-- Total Pesanan -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-xl">shopping_cart</span>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 dark:text-blue-400">
                    <span class="material-symbols-outlined text-sm">pending</span>
                    {{ $pendingOrders }} pending
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Pesanan</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalOrders) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $completedOrders }} selesai, {{ $processingOrders }} diproses</p>
            </div>
        </div>

        <!-- Total Produk -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-orange-600 dark:text-orange-400 text-xl">inventory_2</span>
                </div>
                @if($lowStockProducts > 0)
                <span class="inline-flex items-center gap-1 text-xs font-semibold text-orange-600 dark:text-orange-400">
                    <span class="material-symbols-outlined text-sm">warning</span>
                    {{ $lowStockProducts }} stok rendah
                </span>
                @endif
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Produk</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalProducts) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Produk aktif</p>
            </div>
        </div>

        <!-- Total Pelanggan -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-xl">people</span>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Pelanggan</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalCustomers) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Pengguna terdaftar</p>
            </div>
        </div>
    </div>

    <!-- Chart & Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pendapatan Mingguan</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">7 hari terakhir</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Hari Ini</p>
                    <p class="text-lg font-bold text-primary-600 dark:text-primary-400">Rp {{ number_format($todayRevenue) }}</p>
                </div>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Produk Terlaris</h2>
            <div class="space-y-4">
                @forelse($topProducts as $index => $product)
                <div class="flex items-center gap-3 p-3 {{ $index === 0 ? 'bg-gray-50 dark:bg-gray-900/50' : 'hover:bg-gray-50 dark:hover:bg-gray-900/50' }} rounded-lg transition">
                    @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                    @else
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-white text-xl">spa</span>
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $product->total_sold ?? 0 }} terjual</p>
                    </div>
                    <p class="text-sm font-bold text-primary-600 dark:text-primary-400">{{ number_format($product->price / 1000) }}K</p>
                </div>
                @empty
                <div class="text-center py-8">
                    <span class="material-symbols-outlined text-gray-400 text-5xl mb-2">inventory_2</span>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada produk terjual</p>
                </div>
                @endforelse
            </div>
            {{-- <a href="{{ route('admin.products.index') }}" class="block mt-4 text-center text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                Lihat Semua Produk →
            </a> --}}
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pesanan Terbaru</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola dan pantau pesanan pelanggan</p>
            </div>
            {{-- <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                Lihat Semua
            </a> --}}
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Invoice</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pelanggan</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Items</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="text-right py-3 px-6 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @php
                        $recentOrders = \App\Models\Order::with(['user', 'items'])
                            ->latest()
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition">
                        <td class="py-4 px-6">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->invoice_number }}</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-xs font-semibold">
                                    {{ strtoupper(substr($order->user->name ?? 'U', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->user->name ?? 'Guest' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $order->items->count() }} item</p>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Rp {{ number_format($order->grand_total) }}</span>
                        </td>
                        <td class="py-4 px-6">
                            @php
                                $statusConfig = [
                                    'pending' => ['color' => 'amber', 'label' => 'Pending'],
                                    'paid' => ['color' => 'blue', 'label' => 'Dibayar'],
                                    'processing' => ['color' => 'blue', 'label' => 'Diproses'],
                                    'shipped' => ['color' => 'indigo', 'label' => 'Dikirim'],
                                    'completed' => ['color' => 'emerald', 'label' => 'Selesai'],
                                    'cancelled' => ['color' => 'red', 'label' => 'Dibatalkan'],
                                ];
                                $status = $statusConfig[$order->status] ?? ['color' => 'gray', 'label' => ucfirst($order->status)];
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold bg-{{ $status['color'] }}-100 text-{{ $status['color'] }}-700 dark:bg-{{ $status['color'] }}-900/30 dark:text-{{ $status['color'] }}-400 rounded-full">
                                <span class="w-1.5 h-1.5 bg-{{ $status['color'] }}-500 rounded-full"></span>
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            {{-- <a href="{{ route('admin.orders.show', $order->id) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </a> --}}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <span class="material-symbols-outlined text-gray-400 text-5xl mb-2">shopping_cart</span>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada pesanan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Revenue Chart with real data
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const isDark = document.documentElement.classList.contains('dark');
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(34, 197, 94, 0.2)');
    gradient.addColorStop(1, 'rgba(34, 197, 94, 0.0)');

    // Data dari controller
    const dates = @json($dates);
    const revenues = @json($revenues);

    // Format labels (Hari)
    const labels = dates.map(date => {
        const d = new Date(date);
        const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        return days[d.getDay()];
    });

    // Convert revenues to millions
    const data = revenues.map(rev => (rev / 1000000).toFixed(2));

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan',
                data: data,
                borderColor: '#22c55e',
                backgroundColor: gradient,
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: '#22c55e',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: isDark ? 'rgba(31, 41, 55, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                    titleColor: isDark ? '#fff' : '#111827',
                    bodyColor: isDark ? '#d1d5db' : '#6b7280',
                    borderColor: isDark ? '#374151' : '#e5e7eb',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + parseFloat(context.parsed.y).toFixed(2) + ' Juta';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    border: {
                        display: false
                    },
                    grid: {
                        color: isDark ? 'rgba(75, 85, 99, 0.3)' : 'rgba(229, 231, 235, 0.8)',
                        drawBorder: false
                    },
                    ticks: {
                        color: isDark ? '#9ca3af' : '#6b7280',
                        callback: function(value) {
                            return 'Rp ' + value.toFixed(1) + 'Jt';
                        },
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    border: {
                        display: false
                    },
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: isDark ? '#9ca3af' : '#6b7280',
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
</script>
@endpush