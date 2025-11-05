{{-- resources/views/pembeli/pesanan/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Pesanan - Lembah Hijau')

@section('content')
<div class="space-y-6">

    <!-- Alerts -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">check_circle</span>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-green-900 dark:text-green-300">Sukses!</h3>
                    <p class="text-sm text-green-800 dark:text-green-400 mt-1">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400">
        <a href="{{ route('pembeli.dashboard') }}" class="hover:text-soft-green transition-colors">Beranda</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <a href="{{ route('pembeli.pesanan.index') }}" class="hover:text-soft-green transition-colors">Pesanan</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-gray-900 dark:text-white font-medium">Detail Pesanan</span>
    </nav>

    <!-- Page Header -->
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">
            Pesanan #{{ $order->order_number }}
        </h1>
        <p class="text-sm text-gray-600 dark:text-zinc-400 mt-1">
            Status: <span class="font-medium">{{ ucfirst($order->status) }}</span>
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column (2/3) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Shipping Information -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-soft-green">local_shipping</span>
                        Informasi Pengiriman
                    </h2>
                </div>
                <div class="p-6 space-y-2">
                    <p class="text-sm text-gray-900 dark:text-white">
                        <span class="font-semibold">Alamat:</span> {{ $order->shipping_address }}
                    </p>
                    <p class="text-sm text-gray-900 dark:text-white">
                        <span class="font-semibold">Kurir:</span> {{ $order->courier ?? '-' }}
                    </p>
                    @if($order->paid_at)
                        <p class="text-sm text-gray-900 dark:text-white">
                            <span class="font-semibold">Dibayar pada:</span> {{ $order->paid_at->format('d M Y H:i') }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-soft-green">shopping_bag</span>
                        Produk yang Dibeli ({{ $order->items->count() }} item)
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex gap-4 p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg">
                            <div class="w-16 h-16 bg-gray-200 dark:bg-zinc-700 rounded-lg overflow-hidden flex-shrink-0">
                                @if($item->product && $item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                         alt="{{ $item->product->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-gray-400">image</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-1">
                                    {{ $item->product->name ?? 'Produk dihapus' }}
                                </h3>
                                <p class="text-xs text-gray-600 dark:text-zinc-400 mt-1">
                                    {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900 dark:text-white">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Right Column (1/3) - Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm sticky top-20">
                <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Ringkasan Pesanan</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-zinc-400">Subtotal Produk</span>
                        <span class="font-semibold text-gray-900 dark:text-white">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-zinc-400">Biaya Pengiriman</span>
                        <span class="font-semibold text-gray-900 dark:text-white">
                            Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="pt-4 border-t border-gray-200 dark:border-zinc-800">
                        <div class="flex justify-between mb-2">
                            <span class="text-base font-semibold text-gray-900 dark:text-white">Total Bayar</span>
                            <span class="text-xl font-bold text-soft-green">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Cancel / Complete Buttons -->
                    <div class="p-6 space-y-2">
                        @if($order->canBeCancelled())
                            <form action="{{ route('pembeli.pesanan.cancel', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full px-6 py-3 bg-red-600 text-white font-semibold rounded-xl hover:shadow-lg transition-all">
                                    Batalkan Pesanan
                                </button>
                            </form>
                        @endif

                        @if($order->canBeCompleted())
                            <form action="{{ route('pembeli.pesanan.complete', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full px-6 py-3 bg-soft-green text-white font-semibold rounded-xl hover:shadow-lg transition-all">
                                    Tandai Selesai
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
