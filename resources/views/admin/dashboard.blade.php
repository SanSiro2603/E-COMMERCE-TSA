{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard Admin - Lembah Hijau')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-charcoal dark:text-white font-be-vietnam">Dashboard Admin</h1>
        <p class="text-sm text-charcoal/70 dark:text-zinc-400">Terakhir update: {{ now()->format('d M Y, H:i') }}</p>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-charcoal/70 dark:text-zinc-400">Total Pesanan</p>
                    <p class="text-2xl font-bold text-charcoal dark:text-white">{{ number_format($totalOrders) }}</p>
                </div>
                <span class="material-symbols-outlined text-soft-green text-3xl">shopping_cart</span>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-charcoal/70 dark:text-zinc-400">Menunggu Konfirmasi</p>
                    <p class="text-2xl font-bold text-warm-yellow">{{ $pendingOrders }}</p>
                </div>
                <span class="material-symbols-outlined text-warm-yellow text-3xl">hourglass_top</span>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-charcoal/70 dark:text-zinc-400">Pendapatan Hari Ini</p>
                    <p class="text-2xl font-bold text-soft-green">Rp {{ number_format($todayRevenue) }}</p>
                </div>
                <span class="material-symbols-outlined text-soft-green text-3xl">trending_up</span>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-charcoal/70 dark:text-zinc-400">Stok Rendah</p>
                    <p class="text-2xl font-bold text-red-500">{{ $lowStockProducts }}</p>
                </div>
                <span class="material-symbols-outlined text-red-500 text-3xl">warning</span>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold text-charcoal dark:text-white mb-4">Pendapatan 7 Hari Terakhir</h3>
            <canvas id="revenueChart" height="100"></canvas>
        </div>

        <!-- Top Products -->
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold text-charcoal dark:text-white mb-4">Produk Terlaris</h3>
            <div class="space-y-3">
                @foreach($topProducts as $product)
                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-zinc-700 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gray-200 dark:bg-zinc-600 rounded-lg border-2 border-dashed border-gray-300"></div>
                            <div>
                                <p class="font-medium text-charcoal dark:text-white">{{ Str::limit($product->name, 25) }}</p>
                                <p class="text-xs text-charcoal/60 dark:text-zinc-400">{{ $product->category->name ?? 'Uncategorized' }}</p>
                            </div>
                        </div>
                        <p class="font-bold text-soft-green">{{ $product->total_sold ?? 0 }} terjual</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($dates->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json($revenues),
                borderColor: '#7BB661',
                backgroundColor: 'rgba(123, 182, 97, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: value => 'Rp ' + value.toLocaleString() } }
            }
        }
    });
</script>
@endpush
@endsection