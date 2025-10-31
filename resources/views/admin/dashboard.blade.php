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
            <button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition shadow-sm">
                <span class="material-symbols-outlined text-lg">add</span>
                Tambah Produk
            </button>
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
                <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                    <span class="material-symbols-outlined text-sm">trending_up</span>
                    12.5%
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Pendapatan</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp 45.8 Juta</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Bulan ini</p>
            </div>
        </div>

        <!-- Total Pesanan -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-xl">shopping_cart</span>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                    <span class="material-symbols-outlined text-sm">trending_up</span>
                    8.2%
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Pesanan</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">1,245</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Pesanan bulan ini</p>
            </div>
        </div>

        <!-- Produk Terjual -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-orange-600 dark:text-orange-400 text-xl">inventory_2</span>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                    <span class="material-symbols-outlined text-sm">trending_up</span>
                    15.3%
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Produk Terjual</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">3,672</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Unit terjual</p>
            </div>
        </div>

        <!-- Pelanggan Baru -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-xl">people</span>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                    <span class="material-symbols-outlined text-sm">trending_up</span>
                    24.7%
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Pelanggan Baru</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">892</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Registrasi baru</p>
            </div>
        </div>
    </div>

    <!-- Chart & Quick Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pendapatan Mingguan</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">7 hari terakhir</p>
                </div>
                <select class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                    <option>Minggu Ini</option>
                    <option>Bulan Ini</option>
                    <option>Tahun Ini</option>
                </select>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Produk Terlaris</h2>
            <div class="space-y-4">
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-white text-xl">spa</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">Selada Organik</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">245 terjual</p>
                    </div>
                    <p class="text-sm font-bold text-primary-600 dark:text-primary-400">89K</p>
                </div>

                <div class="flex items-center gap-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-900/50 rounded-lg transition">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-white text-xl">nutrition</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">Tomat Cherry</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">198 terjual</p>
                    </div>
                    <p class="text-sm font-bold text-primary-600 dark:text-primary-400">75K</p>
                </div>

                <div class="flex items-center gap-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-900/50 rounded-lg transition">
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-white text-xl">local_florist</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">Bayam Hijau</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">187 terjual</p>
                    </div>
                    <p class="text-sm font-bold text-primary-600 dark:text-primary-400">45K</p>
                </div>

                <div class="flex items-center gap-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-900/50 rounded-lg transition">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-white text-xl">eco</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">Kangkung Fresh</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">156 terjual</p>
                    </div>
                    <p class="text-sm font-bold text-primary-600 dark:text-primary-400">38K</p>
                </div>
            </div>
            <a href="#" class="block mt-4 text-center text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                Lihat Semua Produk →
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pesanan Terbaru</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola dan pantau pesanan pelanggan</p>
            </div>
            <a href="#" class="text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                Lihat Semua
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID Pesanan</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pelanggan</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Produk</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="text-right py-3 px-6 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition">
                        <td class="py-4 px-6">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">#ORD-2847</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-xs font-semibold">
                                    BS
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Budi Santoso</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">budi@email.com</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-sm text-gray-700 dark:text-gray-300">Selada Organik, Tomat</p>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Rp 245.000</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                Selesai
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <span class="material-symbols-outlined text-xl">more_vert</span>
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition">
                        <td class="py-4 px-6">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">#ORD-2846</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-semibold">
                                    SN
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Siti Nurhaliza</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">siti@email.com</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-sm text-gray-700 dark:text-gray-300">Bayam, Kangkung</p>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Rp 189.000</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 rounded-full">
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                Proses
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <span class="material-symbols-outlined text-xl">more_vert</span>
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition">
                        <td class="py-4 px-6">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">#ORD-2845</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white text-xs font-semibold">
                                    AR
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Ahmad Rifai</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">ahmad@email.com</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-sm text-gray-700 dark:text-gray-300">Tomat Cherry</p>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Rp 156.000</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 rounded-full">
                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                Pending
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <span class="material-symbols-outlined text-xl">more_vert</span>
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition">
                        <td class="py-4 px-6">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">#ORD-2844</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white text-xs font-semibold">
                                    DK
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Dewi Kusuma</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">dewi@email.com</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-sm text-gray-700 dark:text-gray-300">Selada, Bayam, Tomat</p>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Rp 312.000</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                Selesai
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <span class="material-symbols-outlined text-xl">more_vert</span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Revenue Chart with Filament style
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const isDark = document.documentElement.classList.contains('dark');
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(34, 197, 94, 0.2)');
    gradient.addColorStop(1, 'rgba(34, 197, 94, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                label: 'Pendapatan',
                data: [5.2, 6.8, 5.5, 7.2, 8.1, 6.9, 7.5],
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
                            return 'Rp ' + context.parsed.y.toFixed(1) + ' Juta';
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
                            return 'Rp ' + value + 'Jt';
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