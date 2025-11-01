{{-- resources/views/pembeli/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - Lembah Hijau')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-charcoal dark:text-white font-be-vietnam">
                Halo, {{ auth()->user()->name }}!
            </h1>
            <p class="text-sm text-charcoal/70 dark:text-zinc-400">Selamat datang kembali di Lembah Hijau</p>
        </div>
        <div class="bg-gradient-to-r from-soft-green to-[#8fcf72] text-white p-4 rounded-xl shadow-lg">
            <p class="text-xs">Total Belanja</p>
            <p class="text-xl font-bold">{{ $totalOrders }} Pesanan</p>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-charcoal/70 dark:text-zinc-400">Menunggu Pembayaran</p>
                    <p class="text-2xl font-bold text-warm-yellow">{{ $pendingOrders }}</p>
                </div>
                <span class="material-symbols-outlined text-warm-yellow text-3xl">hourglass_top</span>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-charcoal/70 dark:text-zinc-400">Sedang Dikirim</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ $shippedOrders }}</p>
                </div>
                <span class="material-symbols-outlined text-indigo-600 text-3xl">local_shipping</span>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-charcoal/70 dark:text-zinc-400">Selesai</p>
                    <p class="text-2xl font-bold text-soft-green">{{ $completedOrders }}</p>
                </div>
                <span class="material-symbols-outlined text-soft-green text-3xl">check_circle</span>
            </div>
        </div>
    </div>

    <!-- Pesanan Terbaru -->
    <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-charcoal dark:text-white">Pesanan Terbaru</h3>
            {{-- <a href="{{ route('pembeli.pesanan') }}" class="text-sm text-soft-green hover:underline">Lihat Semua</a> --}}
        </div>
        <div class="space-y-4">
            @forelse($recentOrders as $order)
                @include('pembeli.components.pesanan-card', ['order' => $order])
            @empty
                <p class="text-center text-gray-500 py-8">Belum ada pesanan</p>
            @endforelse
        </div>
    </div>

    <!-- Produk Terlaris -->
    <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-charcoal dark:text-white">Produk Terlaris</h3>
            <a href="{{ route('pembeli.produk.index') }}" class="text-sm text-soft-green hover:underline">Lihat Semua</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($topProducts as $product)
                <a href="{{ route('pembeli.produk.show', $product->slug) }}" class="group">
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl h-32 mb-2 group-hover:border-soft-green transition"></div>
                    <p class="text-sm font-medium text-charcoal dark:text-white group-hover:text-soft-green">
                        {{ Str::limit($product->name, 30) }}
                    </p>
                    <p class="text-xs text-gray-600 dark:text-zinc-400">Rp {{ number_format($product->price) }}</p>
                    <p class="text-xs text-soft-green">{{ $product->total_sold ?? 0 }} terjual</p>
                </a>
            @endforeach
        </div>
    </div>

</div>
@endsection