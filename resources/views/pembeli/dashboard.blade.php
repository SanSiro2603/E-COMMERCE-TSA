{{-- resources/views/pembeli/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - Lembah Hijau')

@section('content')
<div class="space-y-6">

    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-soft-green via-primary to-soft-green p-6 md:p-8 rounded-2xl shadow-lg text-white relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold font-be-vietnam mb-2">
                        Selamat Datang, {{ auth()->user()->name }}! 👋
                    </h1>
                    <p class="text-white/90 text-sm md:text-base">Temukan produk terbaik untuk kebutuhan Anda</p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm px-6 py-4 rounded-xl border border-white/30">
                    <p class="text-xs text-white/80 mb-1">Total Pesanan</p>
                    <p class="text-3xl font-bold">{{ $totalOrders }}</p>
                    <p class="text-xs text-white/80 mt-1">Pesanan</p>
                </div>
            </div>
        </div>
        <!-- Decorative Elements -->
        <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -left-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        <!-- Pending Payment -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm hover:shadow-md transition-all group">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-500/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-2xl">hourglass_top</span>
                    </div>
                    @if($pendingOrders > 0)
                        <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400 text-xs font-semibold rounded-full">
                            Perlu Aksi
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-600 dark:text-zinc-400 mb-1">Menunggu Pembayaran</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pendingOrders }}</p>
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-zinc-800">
                    <a href="{{ route('pembeli.pesanan.index') }}?status=pending" 
                       class="text-sm text-yellow-600 dark:text-yellow-400 hover:text-yellow-700 dark:hover:text-yellow-300 font-medium inline-flex items-center gap-1">
                        <span>Lihat Detail</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- In Delivery -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm hover:shadow-md transition-all group">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-500/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-2xl">local_shipping</span>
                    </div>
                    @if($shippedOrders > 0)
                        <span class="px-2 py-1 bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400 text-xs font-semibold rounded-full animate-pulse">
                            Dalam Perjalanan
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-600 dark:text-zinc-400 mb-1">Sedang Dikirim</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $shippedOrders }}</p>
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-zinc-800">
                    <a href="{{ route('pembeli.pesanan.index') }}?status=shipped" 
                       class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium inline-flex items-center gap-1">
                        <span>Lacak Paket</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm hover:shadow-md transition-all group sm:col-span-2 lg:col-span-1">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-500/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">check_circle</span>
                    </div>
                    @if($completedOrders > 0)
                        <span class="px-2 py-1 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 text-xs font-semibold rounded-full">
                            Sukses
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-600 dark:text-zinc-400 mb-1">Pesanan Selesai</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $completedOrders }}</p>
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-zinc-800">
                    <a href="{{ route('pembeli.pesanan.index') }}?status=completed" 
                       class="text-sm text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 font-medium inline-flex items-center gap-1">
                        <span>Riwayat</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Top Products Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Recent Orders (2/3 width on large screens) -->
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pesanan Terbaru</h3>
                        <p class="text-sm text-gray-500 dark:text-zinc-400 mt-0.5">Pantau status pesanan Anda</p>
                    </div>
                    <a href="{{ route('pembeli.pesanan.index') }}" 
                       class="text-sm font-medium text-soft-green hover:text-primary transition-colors inline-flex items-center gap-1">
                        <span>Lihat Semua</span>
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </a>
                </div>
            </div>
            
            <div class="p-6">
                @forelse($recentOrders as $order)
                    <div class="mb-4 last:mb-0 p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                        #{{ $order->order_number }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded-full
                                        @if($order->status === 'pending') bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400
                                        @elseif($order->status === 'paid') bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400
                                        @elseif($order->status === 'processing') bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400
                                        @elseif($order->status === 'shipped') bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400
                                        @elseif($order->status === 'completed') bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400
                                        @else bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400
                                        @endif">
                                        <span class="w-1.5 h-1.5 rounded-full
                                            @if($order->status === 'pending') bg-yellow-500
                                            @elseif($order->status === 'paid') bg-blue-500
                                            @elseif($order->status === 'processing') bg-purple-500
                                            @elseif($order->status === 'shipped') bg-indigo-500
                                            @elseif($order->status === 'completed') bg-green-500
                                            @else bg-red-500
                                            @endif"></span>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-zinc-400">
                                    {{ $order->items->count() }} item • 
                                    <span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-zinc-500 mt-1">
                                    {{ $order->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <a href="{{ route('pembeli.pesanan.index') }}" 
                               class="flex-shrink-0 px-3 py-1.5 bg-soft-green/10 text-soft-green hover:bg-soft-green hover:text-white rounded-lg text-xs font-medium transition-colors inline-flex items-center gap-1">
                                <span>Detail</span>
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-6xl mb-3">shopping_bag</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Belum ada pesanan</p>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1 mb-4">Mulai berbelanja sekarang!</p>
                        <a href="{{ route('pembeli.produk.index') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-soft-green to-primary text-white rounded-lg text-sm font-medium hover:shadow-lg transition-all">
                            <span class="material-symbols-outlined text-base">storefront</span>
                            Belanja Sekarang
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions (1/3 width on large screens) -->
        <div class="space-y-6">
            <!-- Top Products Section -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Produk Populer</h3>
                            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-0.5">Pilihan terbaik</p>
                        </div>
                        <a href="{{ route('pembeli.produk.index') }}" 
                           class="text-sm font-medium text-soft-green hover:text-primary">
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>
                </div>
                
                <div class="p-6 space-y-3">
                    @foreach($topProducts->take(3) as $index => $product)
                        <a href="{{ route('pembeli.produk.show', $product->slug) }}" 
                           class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-zinc-800/50 rounded-lg hover:bg-soft-green/10 dark:hover:bg-soft-green/20 transition-colors group">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-soft-green to-primary rounded-lg flex items-center justify-center text-white font-bold shadow-md">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate group-hover:text-soft-green transition-colors">
                                    {{ Str::limit($product->name, 25) }}
                                </p>
                                <p class="text-xs text-gray-600 dark:text-zinc-400">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                            </div>
                            <span class="material-symbols-outlined text-gray-400 group-hover:text-soft-green transition-colors">
                                arrow_forward
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-gradient-to-br from-soft-green/10 to-primary/10 dark:from-soft-green/5 dark:to-primary/5 rounded-xl border border-soft-green/20 dark:border-soft-green/10 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-soft-green">bolt</span>
                    Akses Cepat
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('pembeli.produk.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 bg-white/50 dark:bg-zinc-800/50 hover:bg-white dark:hover:bg-zinc-800 rounded-lg transition-colors group">
                        <span class="material-symbols-outlined text-soft-green">storefront</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Jelajahi Produk</span>
                        <span class="material-symbols-outlined text-gray-400 group-hover:text-soft-green ml-auto text-sm">arrow_forward</span>
                    </a>
                    <a href="{{ route('pembeli.keranjang.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 bg-white/50 dark:bg-zinc-800/50 hover:bg-white dark:hover:bg-zinc-800 rounded-lg transition-colors group">
                        <span class="material-symbols-outlined text-soft-green">shopping_cart</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Keranjang Saya</span>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                        <span class="material-symbols-outlined text-gray-400 group-hover:text-soft-green {{ session('cart') && count(session('cart')) > 0 ? '' : 'ml-auto' }} text-sm">arrow_forward</span>
                    </a>
                    <a href="{{ route('pembeli.profil.edit') }}" 
                       class="flex items-center gap-3 px-3 py-2 bg-white/50 dark:bg-zinc-800/50 hover:bg-white dark:hover:bg-zinc-800 rounded-lg transition-colors group">
                        <span class="material-symbols-outlined text-soft-green">person</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Edit Profil</span>
                        <span class="material-symbols-outlined text-gray-400 group-hover:text-soft-green ml-auto text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection