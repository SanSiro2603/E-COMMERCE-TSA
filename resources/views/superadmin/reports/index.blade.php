<!-- resources/views/superadmin/reports/index.blade.php -->
@extends('layouts.superadmin')

@section('page-title', 'Laporan & Analisis')
@section('page-subtitle', 'Laporan penjualan dan analisis bisnis komprehensif')

@section('content')
<div class="space-y-6">
    
    <!-- Filter & Export Section -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 shadow-soft border border-gray-100 dark:border-zinc-800">
        <form method="GET" action="{{ route('superadmin.reports.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Start Date -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-zinc-300 mb-2">Tanggal Mulai</label>
                    <input type="date" 
                           name="start_date" 
                           value="{{ $startDate }}" 
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-transparent">
                </div>
                
                <!-- End Date -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-zinc-300 mb-2">Tanggal Akhir</label>
                    <input type="date" 
                           name="end_date" 
                           value="{{ $endDate }}" 
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-transparent">
                </div>
                
                <!-- Action Button -->
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full px-4 py-2.5 bg-gradient-to-r from-soft-green to-primary hover:from-primary hover:to-soft-green text-white rounded-lg font-medium text-sm shadow-lg shadow-soft-green/30 transition-all">
                        <span class="flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                            Filter Data
                        </span>
                    </button>
                </div>
            </div>
            
            <!-- Export Buttons -->
            <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-100 dark:border-zinc-800">
                <a href="{{ route('superadmin.reports.exportPdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                   target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 rounded-lg text-sm font-medium transition-colors">
                    <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                    Export PDF
                </a>
                <a href="{{ route('superadmin.reports.exportExcel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-500/20 rounded-lg text-sm font-medium transition-colors">
                    <span class="material-symbols-outlined text-[18px]">table_chart</span>
                    Export Excel
                </a>
            </div>
        </form>
    </div>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 shadow-soft border border-gray-100 dark:border-zinc-800">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-lg shadow-green-500/30">
                    <span class="material-symbols-outlined text-white text-[20px]">payments</span>
                </div>
                <p class="text-[10px] font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wide">Total Revenue</p>
            </div>
            <p class="text-xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
        </div>
        
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 shadow-soft border border-gray-100 dark:border-zinc-800">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <span class="material-symbols-outlined text-white text-[20px]">shopping_cart</span>
                </div>
                <p class="text-[10px] font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wide">Total Orders</p>
            </div>
            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_orders']) }}</p>
        </div>
        
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 shadow-soft border border-gray-100 dark:border-zinc-800">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg shadow-purple-500/30">
                <span class="material-symbols-outlined text-white text-[20px]">analytics</span>
                </div>
                <p class="text-[10px] font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wide">Avg Order</p>
            </div>
            <p class="text-xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($stats['avg_order_value'], 0, ',', '.') }}</p>
        </div>
        
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 shadow-soft border border-gray-100 dark:border-zinc-800">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center shadow-lg shadow-orange-500/30">
                    <span class="material-symbols-outlined text-white text-[20px]">person</span>
                </div>
                <p class="text-[10px] font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wide">Customers</p>
            </div>
            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_customers']) }}</p>
        </div>
        
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 shadow-soft border border-gray-100 dark:border-zinc-800">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <span class="material-symbols-outlined text-white text-[20px]">inventory_2</span>
                </div>
                <p class="text-[10px] font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wide">Items Sold</p>
            </div>
            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_items_sold']) }}</p>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 shadow-soft border border-gray-100 dark:border-zinc-800">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-6">Pendapatan Harian</h3>
            <canvas id="revenueChart" height="250"></canvas>
        </div>
        
        <!-- Orders Chart -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 shadow-soft border border-gray-100 dark:border-zinc-800">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-6">Jumlah Pesanan Harian</h3>
            <canvas id="ordersChart" height="250"></canvas>
        </div>
    </div>
    
    <!-- Top Products & Customers -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Products -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-soft border border-gray-100 dark:border-zinc-800">
            <div class="p-6 border-b border-gray-100 dark:border-zinc-800">
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Top 10 Produk Terlaris</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($topProducts as $index => $product)
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-soft-green to-primary rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-lg shadow-soft-green/30">
                            {{ $index + 1 }}
                        </div>
                        
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded-lg border border-gray-100 dark:border-zinc-800">
                        @else
                        <div class="w-12 h-12 bg-gray-100 dark:bg-zinc-800 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-gray-400 dark:text-zinc-600">image</span>
                        </div>
                        @endif
                        
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $product->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-zinc-500">Terjual: {{ number_format($product->total_sold ?? 0) }} unit</p>
                        </div>
                        
                        <div class="text-right">
                            <p class="text-xs font-bold text-green-600 dark:text-green-400">Rp {{ number_format($product->total_revenue ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-gray-300 dark:text-zinc-700 text-5xl">inventory_2</span>
                        <p class="text-xs text-gray-500 dark:text-zinc-500 mt-2">Belum ada data produk</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Top Customers -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-soft border border-gray-100 dark:border-zinc-800">
            <div class="p-6 border-b border-gray-100 dark:border-zinc-800">
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Top 10 Pelanggan</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($topCustomers as $index => $customer)
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-lg shadow-blue-500/30">
                            {{ $index + 1 }}
                        </div>
                        
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $customer->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-zinc-500">{{ $customer->orders_count }} pesanan</p>
                        </div>
                        
                        <div class="text-right">
                            <p class="text-xs font-bold text-blue-600 dark:text-blue-400">Rp {{ number_format($customer->total_spent ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-gray-300 dark:text-zinc-700 text-5xl">person</span>
                        <p class="text-xs text-gray-500 dark:text-zinc-500 mt-2">Belum ada data pelanggan</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-soft border border-gray-100 dark:border-zinc-800">
        <div class="p-6 border-b border-gray-100 dark:border-zinc-800">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Detail Pesanan</h3>
                <span class="text-xs text-gray-500 dark:text-zinc-400">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-zinc-800/50 border-b border-gray-100 dark:border-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">No. Pesanan</th>
                        <th class="px-6 py-3 text-left text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Pembeli</th>
                        <th class="px-6 py-3 text-center text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-right text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Subtotal</th>
                        <th class="px-6 py-3 text-right text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Ongkir</th>
                        <th class="px-6 py-3 text-right text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">#{{ $order->order_number }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xs">
                                    {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-900 dark:text-white">{{ $order->user->name }}</p>
                                    <p class="text-[10px] text-gray-500 dark:text-zinc-500">{{ $order->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs text-gray-900 dark:text-white">{{ $order->items->sum('quantity') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-xs text-gray-900 dark:text-white">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-xs text-gray-900 dark:text-white">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-xs font-bold text-green-600 dark:text-green-400">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs text-gray-900 dark:text-white">{{ $order->created_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-gray-500 dark:text-zinc-500">{{ $order->created_at->format('H:i') }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <span class="material-symbols-outlined text-gray-300 dark:text-zinc-700 text-5xl">shopping_bag</span>
                            <p class="text-xs text-gray-500 dark:text-zinc-500 mt-2">Tidak ada data pesanan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-zinc-800">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
    
</div>
@endsection

@push('scripts')
<script>
const isDark = document.documentElement.classList.contains('dark');

// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($dates) !!},
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: {!! json_encode($revenues) !!},
            borderColor: '#7BB661',
            backgroundColor: 'rgba(123, 182, 97, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointBackgroundColor: '#7BB661',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: isDark ? 'rgba(24, 24, 27, 0.95)' : 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: { size: 13, weight: 'bold' },
                bodyFont: { size: 12 },
                callbacks: {
                    label: function(context) {
                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: isDark ? '#71717a' : '#6b7280',
                    font: { size: 11 },
                    callback: function(value) {
                        return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                    }
                },
                grid: {
                    color: isDark ? 'rgba(63, 63, 70, 0.3)' : 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                ticks: {
                    color: isDark ? '#71717a' : '#6b7280',
                    font: { size: 10 }
                },
                grid: { display: false }
            }
        }
    }
});

// Orders Chart
const ordersCtx = document.getElementById('ordersChart').getContext('2d');
new Chart(ordersCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($dates) !!},
        datasets: [{
            label: 'Jumlah Pesanan',
            data: {!! json_encode($orderCounts) !!},
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderColor: '#3B82F6',
            borderWidth: 2,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: isDark ? 'rgba(24, 24, 27, 0.95)' : 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: { size: 13, weight: 'bold' },
                bodyFont: { size: 12 }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: isDark ? '#71717a' : '#6b7280',
                    font: { size: 11 },
                    stepSize: 1
                },
                grid: {
                    color: isDark ? 'rgba(63, 63, 70, 0.3)' : 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                ticks: {
                    color: isDark ? '#71717a' : '#6b7280',
                    font: { size: 10 }
                },
                grid: { display: false }
            }
        }
    }
});
</script>
@endpush