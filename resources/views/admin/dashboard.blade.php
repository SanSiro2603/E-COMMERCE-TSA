@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="glass rounded-2xl p-8 hover-lift">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">
                    Selamat Datang Kembali, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-gray-600 dark:text-gray-300">
                    Berikut ringkasan bisnis Lembah Hijau hari ini
                </p>
            </div>
            <div class="hidden lg:flex items-center gap-3">
                <button class="px-4 py-2 glass rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">download</span>
                    <span class="text-sm font-medium">Unduh Laporan</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Pendapatan -->
        <div class="glass rounded-2xl p-6 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white">payments</span>
                </div>
                <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full font-semibold">+12.5%</span>
            </div>
            <h3 class="text-gray-600 dark:text-gray-400 text-sm mb-1">Total Pendapatan</h3>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">Rp 45.8 Juta</p>
            <p class="text-xs text-gray-500 mt-2">Bulan ini</p>
        </div>

        <!-- Total Pesanan -->
        <div class="glass rounded-2xl p-6 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white">shopping_cart</span>
                </div>
                <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full font-semibold">+8.2%</span>
            </div>
            <h3 class="text-gray-600 dark:text-gray-400 text-sm mb-1">Total Pesanan</h3>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">1,245</p>
            <p class="text-xs text-gray-500 mt-2">Pesanan bulan ini</p>
        </div>

        <!-- Produk Terjual -->
        <div class="glass rounded-2xl p-6 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white">inventory_2</span>
                </div>
                <span class="text-xs px-2 py-1 bg-orange-100 text-orange-700 rounded-full font-semibold">+15.3%</span>
            </div>
            <h3 class="text-gray-600 dark:text-gray-400 text-sm mb-1">Produk Terjual</h3>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">3,672</p>
            <p class="text-xs text-gray-500 mt-2">Unit terjual</p>
        </div>

        <!-- Pelanggan Baru -->
        <div class="glass rounded-2xl p-6 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white">people</span>
                </div>
                <span class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded-full font-semibold">+24.7%</span>
            </div>
            <h3 class="text-gray-600 dark:text-gray-400 text-sm mb-1">Pelanggan Baru</h3>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">892</p>
            <p class="text-xs text-gray-500 mt-2">Registrasi baru</p>
        </div>
    </div>

    <!-- Top Products -->
    <div class="glass rounded-2xl p-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-6">Produk Terlaris</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="flex flex-col gap-3 p-4 bg-green-50 dark:bg-green-900/20 rounded-xl hover-lift">
                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-sm">spa</span>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-white text-sm">Selada Organik</h4>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">245 terjual</p>
                    <p class="font-bold text-green-600 mt-2">Rp 89K</p>
                </div>
            </div>

            <div class="flex flex-col gap-3 p-4 bg-white dark:bg-zinc-800/50 rounded-xl hover-lift">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-sm">nutrition</span>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-white text-sm">Tomat Cherry</h4>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">198 terjual</p>
                    <p class="font-bold text-green-600 mt-2">Rp 75K</p>
                </div>
            </div>

            <div class="flex flex-col gap-3 p-4 bg-white dark:bg-zinc-800/50 rounded-xl hover-lift">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-sm">local_florist</span>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-white text-sm">Bayam Hijau</h4>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">187 terjual</p>
                    <p class="font-bold text-green-600 mt-2">Rp 45K</p>
                </div>
            </div>

            <div class="flex flex-col gap-3 p-4 bg-white dark:bg-zinc-800/50 rounded-xl hover-lift">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-sm">eco</span>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-white text-sm">Kangkung Fresh</h4>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">156 terjual</p>
                    <p class="font-bold text-green-600 mt-2">Rp 38K</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Orders -->
        <div class="lg:col-span-2 glass rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Pesanan Terbaru</h2>
                <a href="#" class="text-sm text-green-600 hover:text-green-700 font-semibold">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">ID Pesanan</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Pelanggan</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Total</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-green-50/50 dark:hover:bg-green-900/10 transition">
                            <td class="py-3 px-4 text-sm font-medium text-gray-800 dark:text-white">#ORD-2847</td>
                            <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Budi Santoso</td>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-800 dark:text-white">Rp 245.000</td>
                            <td class="py-3 px-4"><span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700 font-semibold">Selesai</span></td>
                        </tr>
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-green-50/50 dark:hover:bg-green-900/10 transition">
                            <td class="py-3 px-4 text-sm font-medium text-gray-800 dark:text-white">#ORD-2846</td>
                            <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Siti Nurhaliza</td>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-800 dark:text-white">Rp 189.000</td>
                            <td class="py-3 px-4"><span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700 font-semibold">Proses</span></td>
                        </tr>
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-green-50/50 dark:hover:bg-green-900/10 transition">
                            <td class="py-3 px-4 text-sm font-medium text-gray-800 dark:text-white">#ORD-2845</td>
                            <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Ahmad Rifai</td>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-800 dark:text-white">Rp 156.000</td>
                            <td class="py-3 px-4"><span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 font-semibold">Pending</span></td>
                        </tr>
                        <tr class="hover:bg-green-50/50 dark:hover:bg-green-900/10 transition">
                            <td class="py-3 px-4 text-sm font-medium text-gray-800 dark:text-white">#ORD-2844</td>
                            <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Dewi Kusuma</td>
                            <td class="py-3 px-4 text-sm font-semibold text-gray-800 dark:text-white">Rp 312.000</td>
                            <td class="py-3 px-4"><span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700 font-semibold">Selesai</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="glass rounded-2xl p-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-6">Aksi Cepat</h2>
            <div class="space-y-3">
                <button class="w-full gradient-button text-white py-3 px-4 rounded-xl flex items-center justify-center gap-2 font-semibold">
                    <span class="material-symbols-outlined">add_circle</span>
                    Tambah Produk
                </button>
                <button class="w-full glass hover:bg-green-50 dark:hover:bg-green-900/20 py-3 px-4 rounded-xl flex items-center justify-center gap-2 font-semibold text-gray-700 dark:text-gray-300 transition">
                    <span class="material-symbols-outlined">shopping_bag</span>
                    Lihat Pesanan
                </button>
                <button class="w-full glass hover:bg-green-50 dark:hover:bg-green-900/20 py-3 px-4 rounded-xl flex items-center justify-center gap-2 font-semibold text-gray-700 dark:text-gray-300 transition">
                    <span class="material-symbols-outlined">assignment</span>
                    Buat Laporan
                </button>
                <button class="w-full glass hover:bg-green-50 dark:hover:bg-green-900/20 py-3 px-4 rounded-xl flex items-center justify-center gap-2 font-semibold text-gray-700 dark:text-gray-300 transition">
                    <span class="material-symbols-outlined">campaign</span>
                    Promosi Baru
                </button>
            </div>

            <!-- System Status -->
            <div class="mt-8 p-4 bg-green-50 dark:bg-green-900/20 rounded-xl">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-sm font-semibold text-gray-800 dark:text-white">Status Sistem</span>
                </div>
                <p class="text-xs text-gray-600 dark:text-gray-400">Semua sistem berjalan normal</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Revenue Chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(34, 197, 94, 0.3)');
    gradient.addColorStop(1, 'rgba(34, 197, 94, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                label: 'Pendapatan (Juta Rp)',
                data: [5.2, 6.8, 5.5, 7.2, 8.1, 6.9, 7.5],
                borderColor: '#22c55e',
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#22c55e',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#333',
                    bodyColor: '#666',
                    borderColor: '#22c55e',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y + ' Juta';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value + 'Jt';
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush