{{-- resources/views/admin/orders/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Pesanan - Lembah Hijau')

@section('content')
<div class="space-y-6">

    <!-- Success/Error Alerts -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-lg p-4 animate-fade-in">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">check_circle</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-green-900 dark:text-green-300">Berhasil!</h3>
                    <p class="text-sm text-green-800 dark:text-green-400 mt-1">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="flex-shrink-0 text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-lg p-4 animate-fade-in">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-2xl">error</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-red-900 dark:text-red-300">Gagal!</h3>
                    <p class="text-sm text-red-800 dark:text-red-400 mt-1">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="flex-shrink-0 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>
    @endif

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-soft-green transition-colors">Dashboard</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-gray-900 dark:text-white font-medium">Pesanan</span>
    </nav>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">Kelola Pesanan</h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Kelola semua pesanan dari pelanggan</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-6 gap-4">
        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg border border-gray-200 dark:border-zinc-800">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-gray-500 dark:text-zinc-400 uppercase font-medium">Semua</span>
                    <div class="w-8 h-8 bg-gray-100 dark:bg-gray-500/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-gray-600 dark:text-gray-400 text-lg">shopping_bag</span>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $orders->total() }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg border border-gray-200 dark:border-zinc-800">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-yellow-600 dark:text-yellow-400 uppercase font-medium">Pending</span>
                    <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-500/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-lg">hourglass_top</span>
                    </div>
                </div>
                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $orders->where('status', 'pending')->count() }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg border border-gray-200 dark:border-zinc-800">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-blue-600 dark:text-blue-400 uppercase font-medium">Paid</span>
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-500/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-lg">payments</span>
                    </div>
                </div>
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $orders->where('status', 'paid')->count() }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg border border-gray-200 dark:border-zinc-800">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-purple-600 dark:text-purple-400 uppercase font-medium">Processing</span>
                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-500/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-lg">inventory</span>
                    </div>
                </div>
                <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $orders->where('status', 'processing')->count() }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg border border-gray-200 dark:border-zinc-800">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-indigo-600 dark:text-indigo-400 uppercase font-medium">Shipped</span>
                    <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-500/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-lg">local_shipping</span>
                    </div>
                </div>
                <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $orders->where('status', 'shipped')->count() }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg border border-gray-200 dark:border-zinc-800">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-green-600 dark:text-green-400 uppercase font-medium">Completed</span>
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-500/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-lg">check_circle</span>
                    </div>
                </div>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $orders->where('status', 'completed')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-4">
        <form method="GET" class="flex flex-col md:flex-row gap-3">
            <!-- Search Input -->
            <div class="flex-1">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500">search</span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari nomor pesanan / nama pembeli..."
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                </div>
            </div>

            <!-- Status Filter -->
            <div class="w-full md:w-48">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500">filter_list</span>
                    <select name="status" 
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors appearance-none">
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ request('status', 'all') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Filter Button -->
            <button type="submit" 
                    class="flex items-center justify-center gap-2 px-6 py-2.5 bg-soft-green hover:bg-primary text-white font-medium rounded-lg transition-colors">
                <span class="material-symbols-outlined text-lg">search</span>
                Filter
            </button>

            <!-- Reset Button -->
            @if(request('search') || request('status') != 'all')
                <a href="{{ route('admin.orders.index') }}" 
                   class="flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-gray-700 dark:text-zinc-300 font-medium rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-lg">close</span>
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-zinc-800/50 border-b border-gray-200 dark:border-zinc-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider">No. Pesanan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider">Pembeli</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-800">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <!-- Order Number -->
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="text-sm font-semibold text-soft-green hover:text-primary transition-colors">
                                    #{{ $order->order_number }}
                                </a>
                            </td>

                            <!-- Buyer -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-soft-green to-primary rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-zinc-400">{{ $order->user->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Total -->
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-gray-900 dark:text-white">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400">{{ $order->orderItems->count() }} item</p>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full
                                    @switch($order->status)
                                        @case('pending') bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400 @break
                                        @case('paid') bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 @break
                                        @case('processing') bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400 @break
                                        @case('shipped') bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400 @break
                                        @case('completed') bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 @break
                                        @case('cancelled') bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 @break
                                    @endswitch">
                                    <span class="w-1.5 h-1.5 rounded-full
                                        @switch($order->status)
                                            @case('pending') bg-yellow-500 @break
                                            @case('paid') bg-blue-500 @break
                                            @case('processing') bg-purple-500 @break
                                            @case('shipped') bg-indigo-500 @break
                                            @case('completed') bg-green-500 @break
                                            @case('cancelled') bg-red-500 @break
                                        @endswitch"></span>
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>

                            <!-- Date -->
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900 dark:text-white">{{ $order->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400">{{ $order->created_at->format('H:i') }}</p>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 rounded-lg text-xs font-medium transition-colors">
                                    <span class="material-symbols-outlined text-base">visibility</span>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-6xl mb-3">shopping_bag</span>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Belum ada pesanan</p>
                                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Pesanan dari pelanggan akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-800">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
</style>

<script>
    // Auto dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.animate-fade-in');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });

        // AJAX Search & Filter
        const searchForm = document.querySelector('form[method="GET"]');
        const searchInput = document.querySelector('input[name="search"]');
        const statusSelect = document.querySelector('select[name="status"]');
        const tableBody = document.querySelector('tbody');
        const statsCards = document.querySelectorAll('.grid.grid-cols-2 > div p.text-2xl');
        
        let debounceTimer;

        // Debounce function for search input
        function debounce(func, delay) {
            return function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(func, delay);
            };
        }

        // Function to fetch and update orders
        async function fetchOrders() {
            const search = searchInput.value;
            const status = statusSelect.value;
            
            // Show loading state
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-12 h-12 border-4 border-soft-green border-t-transparent rounded-full animate-spin mb-3"></div>
                            <p class="text-sm text-gray-500 dark:text-zinc-400">Memuat data...</p>
                        </div>
                    </td>
                </tr>
            `;

            try {
                const url = new URL(window.location.href);
                url.searchParams.set('search', search);
                url.searchParams.set('status', status);
                url.searchParams.set('ajax', '1');

                const response = await fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();
                
                // Update table
                if (data.html) {
                    tableBody.innerHTML = data.html;
                }

                // Update stats cards
                if (data.stats) {
                    updateStats(data.stats);
                }

                // Update URL without reload
                window.history.pushState({}, '', url.toString().replace('&ajax=1', ''));

            } catch (error) {
                console.error('Error fetching orders:', error);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <span class="material-symbols-outlined text-red-500 text-6xl mb-3">error</span>
                                <p class="text-sm font-medium text-red-600 dark:text-red-400">Gagal memuat data</p>
                                <button onclick="location.reload()" class="mt-3 px-4 py-2 bg-soft-green text-white rounded-lg text-sm">
                                    Muat Ulang
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }
        }

        // Update stats cards
        function updateStats(stats) {
            const cards = document.querySelectorAll('.grid.grid-cols-2 > div');
            if (cards[0]) cards[0].querySelector('p.text-2xl').textContent = stats.all || '0';
            if (cards[1]) cards[1].querySelector('p.text-2xl').textContent = stats.pending || '0';
            if (cards[2]) cards[2].querySelector('p.text-2xl').textContent = stats.paid || '0';
            if (cards[3]) cards[3].querySelector('p.text-2xl').textContent = stats.processing || '0';
            if (cards[4]) cards[4].querySelector('p.text-2xl').textContent = stats.shipped || '0';
            if (cards[5]) cards[5].querySelector('p.text-2xl').textContent = stats.completed || '0';
        }

        // Event listeners
        searchInput.addEventListener('input', debounce(fetchOrders, 500));
        statusSelect.addEventListener('change', fetchOrders);

        // Prevent form submission
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchOrders();
        });
    });
</script>