{{-- resources/views/pembeli/pesanan/checkout.blade.php --}}
@extends('layouts.app')

@section('title', 'Checkout - Lembah Hijau')

@section('content')
<div class="space-y-6">

    <!-- Alerts -->
    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-2xl">error</span>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-red-900 dark:text-red-300">Error!</h3>
                    <p class="text-sm text-red-800 dark:text-red-400 mt-1">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400">
        <a href="{{ route('pembeli.dashboard') }}" class="hover:text-soft-green transition-colors">Beranda</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <a href="{{ route('pembeli.keranjang.index') }}" class="hover:text-soft-green transition-colors">Keranjang</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-gray-900 dark:text-white font-medium">Checkout</span>
    </nav>

    <!-- Page Header -->
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">
            Checkout
        </h1>
        <p class="text-sm text-gray-600 dark:text-zinc-400 mt-1">
            Lengkapi data pengiriman untuk menyelesaikan pesanan
        </p>
    </div>

    <form action="{{ route('pembeli.pesanan.store') }}" method="POST">
        @csrf
        
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
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea name="shipping_address" 
                                      rows="4" 
                                      required
                                      placeholder="Masukkan alamat lengkap dengan detail (jalan, RT/RW, kelurahan, kecamatan, kota, provinsi, kode pos)"
                                      class="w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green resize-none">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Kurir Pengiriman
                            </label>
                            <select name="courier"
                                    class="w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-soft-green">
                                <option value="JNE">JNE</option>
                                <option value="JNT">JNT</option>
                                <option value="SiCepat">SiCepat</option>
                                <option value="Anteraja">Anteraja</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-soft-green">shopping_bag</span>
                            Produk yang Dibeli ({{ $carts->count() }} item)
                        </h2>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        @foreach($carts as $cart)
                            <div class="flex gap-4 p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg">
                                <div class="w-16 h-16 bg-gray-200 dark:bg-zinc-700 rounded-lg overflow-hidden flex-shrink-0">
                                    @if($cart->product->image)
                                        <img src="{{ asset('storage/' . $cart->product->image) }}" 
                                             alt="{{ $cart->product->name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <span class="material-symbols-outlined text-gray-400">image</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-1">
                                        {{ $cart->product->name }}
                                    </h3>
                                    <p class="text-xs text-gray-600 dark:text-zinc-400 mt-1">
                                        {{ $cart->quantity }} x Rp {{ number_format($cart->product->price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                                        Rp {{ number_format($cart->subtotal, 0, ',', '.') }}
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
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-zinc-400">Biaya Pengiriman</span>
                            <span class="font-semibold text-gray-900 dark:text-white">
                                Rp {{ number_format($shippingCost, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="pt-4 border-t border-gray-200 dark:border-zinc-800">
                            <div class="flex justify-between mb-2">
                                <span class="text-base font-semibold text-gray-900 dark:text-white">Total Bayar</span>
                                <span class="text-xl font-bold text-soft-green">
                                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200 dark:border-zinc-800 space-y-3">
                        <button type="submit"
                                class="w-full px-6 py-3 bg-gradient-to-r from-soft-green to-primary text-white font-semibold rounded-xl hover:shadow-lg transition-all">
                            <span class="flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">check_circle</span>
                                Buat Pesanan
                            </span>
                        </button>
                        <a href="{{ route('pembeli.keranjang.index') }}"
                           class="block w-full px-6 py-3 bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 font-medium rounded-xl text-center hover:bg-gray-200 dark:hover:bg-zinc-700 transition-colors">
                            <span class="flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">arrow_back</span>
                                Kembali ke Keranjang
                            </span>
                        </a>
                    </div>

                    <!-- Info Box -->
                    <div class="p-6 bg-blue-50 dark:bg-blue-500/10 border-t border-blue-200 dark:border-blue-500/20">
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-lg">info</span>
                            <p class="text-xs text-blue-800 dark:text-blue-400">
                                Setelah pesanan dibuat, silakan lakukan pembayaran untuk melanjutkan proses pengiriman
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection