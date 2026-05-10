{{-- resources/views/pembeli/produk/show.blade.php --}}
@extends('layouts.app')

@section('title', $product->name . ' - E-Commerce TSA')

@section('content')
<div class="mt-6 space-y-4">

    {{-- Breadcrumb navigasi halaman --}}
    <nav class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-zinc-400">
        <a href="{{ route('pembeli.dashboard') }}" class="hover:text-primary transition-colors">Beranda</a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        <a href="{{ route('pembeli.produk.index') }}" class="hover:text-primary transition-colors">Katalog</a>
        @if($product->category)
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            @if($product->category->parent)
                <a href="{{ route('pembeli.produk.index', ['parent_category' => $product->category->parent_id]) }}"
                   class="hover:text-primary transition-colors">{{ $product->category->parent->name }}</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <a href="{{ route('pembeli.produk.index', ['category' => $product->category_id]) }}"
                   class="hover:text-primary transition-colors">{{ $product->category->name }}</a>
            @else
                <a href="{{ route('pembeli.produk.index', ['parent_category' => $product->category_id]) }}"
                   class="hover:text-primary transition-colors">{{ $product->category->name }}</a>
            @endif
        @endif
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        <span class="text-[#0d1b13] dark:text-white font-medium truncate max-w-[160px]">{{ $product->name }}</span>
    </nav>

    {{-- ================================================================
         MAIN CARD: Gambar kiri + Detail kanan
         ================================================================ --}}
    <div class="bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm p-4 md:p-6">
        <div class="flex flex-col lg:flex-row gap-6 lg:gap-10">

            {{-- ============ KIRI: GALLERY ============ --}}
            <div class="lg:w-[45%] w-full flex flex-col gap-3">

                {{-- Gambar Utama --}}
                <div class="relative w-full aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-zinc-800 border border-gray-100 dark:border-zinc-700">
                    @if($product->image)
                        <img id="main-image"
                             src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             onclick="openZoom(currentImageIndex)"
                             class="w-full h-full object-cover cursor-zoom-in transition-opacity duration-300">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-7xl">image</span>
                            <span class="text-xs text-gray-400">Tidak ada foto</span>
                        </div>
                    @endif

                    {{-- Overlay stok habis --}}
                    @if($product->stock == 0)
                        <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px] flex items-center justify-center">
                            <span class="px-4 py-1.5 bg-red-600 text-white text-sm font-bold rounded-full shadow-lg">
                                Stok Habis
                            </span>
                        </div>
                    @endif

                    {{-- Badge stok kritis --}}
                    @if($product->stock > 0 && $product->stock <= 3)
                        <div class="absolute top-3 right-3 inline-flex items-center gap-1 px-2.5 py-1 bg-red-500 text-white text-xs font-bold rounded-full shadow">
                            <span class="material-symbols-outlined text-sm">warning</span>
                            Sisa {{ $product->stock }}
                        </div>
                    @elseif($product->stock > 3 && $product->stock <= 10)
                        <div class="absolute top-3 right-3 inline-flex items-center gap-1 px-2.5 py-1 bg-amber-500 text-white text-xs font-bold rounded-full shadow">
                            <span class="material-symbols-outlined text-sm">inventory_2</span>
                            Terbatas
                        </div>
                    @endif

                    {{-- Badge Baru --}}
                    @if($product->created_at && $product->created_at->diffInDays(now()) <= 7 && $product->stock > 0)
                        <div class="absolute top-3 left-3 px-2.5 py-1 bg-primary text-white text-xs font-bold rounded-full shadow">
                            Baru
                        </div>
                    @endif

                    {{-- Navigasi gambar (jika lebih dari 1) --}}
                    @if(isset($product->images) && count($product->images) > 1)
                        <button onclick="prevImage()"
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-9 h-9 bg-black/40 hover:bg-black/60 text-white rounded-full flex items-center justify-center transition backdrop-blur-sm">
                            <span class="material-symbols-outlined text-sm">chevron_left</span>
                        </button>
                        <button onclick="nextImage()"
                                class="absolute right-2 top-1/2 -translate-y-1/2 w-9 h-9 bg-black/40 hover:bg-black/60 text-white rounded-full flex items-center justify-center transition backdrop-blur-sm">
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </button>
                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 px-2.5 py-0.5 bg-black/50 text-white text-xs rounded-full backdrop-blur-sm">
                            <span id="current-image-index">1</span> / {{ count($product->images) }}
                        </div>
                    @endif

                    {{-- Hint zoom --}}
                    <div class="absolute bottom-3 right-3 w-7 h-7 bg-black/40 backdrop-blur-sm text-white rounded-full flex items-center justify-center pointer-events-none">
                        <span class="material-symbols-outlined text-sm">zoom_in</span>
                    </div>
                </div>

                {{-- Thumbnail Strip --}}
                @if(isset($product->images) && count($product->images) > 1)
                    <div id="thumbnail-container" class="flex gap-2 overflow-x-auto scrollbar-hide scroll-smooth pb-1">
                        @foreach($product->images as $index => $image)
                            <button onclick="selectImage({{ $index }})"
                                    class="thumbnail-btn flex-shrink-0 w-16 h-16 md:w-20 md:h-20 rounded-lg overflow-hidden border-2 transition-all
                                           {{ $index === 0 ? 'border-primary' : 'border-gray-200 dark:border-zinc-700' }}
                                           hover:border-primary">
                                <img src="{{ asset('storage/' . $image) }}"
                                     alt="Foto {{ $index + 1 }}"
                                     class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif

                {{-- Sertifikat Kesehatan --}}
                @if($product->health_certificate)
                    <div class="flex items-center justify-between p-3
                                bg-blue-50 dark:bg-blue-500/10
                                border border-blue-200 dark:border-blue-500/20
                                rounded-xl">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 bg-blue-100 dark:bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-xl">verified</span>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-900 dark:text-white">Sertifikat Kesehatan</p>
                                <p class="text-[11px] text-gray-500 dark:text-zinc-400">Bersertifikat resmi</p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $product->health_certificate) }}" target="_blank"
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-medium transition">
                            <span class="material-symbols-outlined text-sm">download</span>
                            Unduh
                        </a>
                    </div>
                @endif

            </div>
            {{-- akhir KIRI --}}

            {{-- ============ KANAN: DETAIL ============ --}}
            <div class="lg:w-[55%] w-full flex flex-col gap-4">

                {{-- Nama Produk --}}
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-[#0d1b13] dark:text-white leading-snug mb-2">
                        {{ $product->name }}
                    </h1>

                    {{-- Harga + Satuan --}}
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl md:text-3xl font-bold text-primary">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <span class="text-sm text-gray-400 dark:text-zinc-500">
                            / {{ $product->unit ?? 'ekor' }}
                        </span>
                    </div>
                </div>

                {{-- Info Cards: Stok + Berat + Satuan --}}
                <div class="grid grid-cols-3 gap-2">
                    {{-- Stok --}}
                    <div class="flex flex-col items-center justify-center gap-1 p-3 rounded-xl border
                        {{ $product->stock == 0
                            ? 'bg-red-50 dark:bg-red-500/10 border-red-200 dark:border-red-500/20'
                            : ($product->stock <= 3
                                ? 'bg-red-50 dark:bg-red-500/10 border-red-200 dark:border-red-500/20'
                                : ($product->stock <= 10
                                    ? 'bg-amber-50 dark:bg-amber-500/10 border-amber-200 dark:border-amber-500/20'
                                    : 'bg-green-50 dark:bg-green-500/10 border-green-200 dark:border-green-500/20')) }}">
                        <span class="material-symbols-outlined text-xl
                            {{ $product->stock == 0 || $product->stock <= 3
                                ? 'text-red-500'
                                : ($product->stock <= 10 ? 'text-amber-500' : 'text-green-600 dark:text-green-400') }}">
                            {{ $product->stock == 0 ? 'remove_shopping_cart' : 'inventory_2' }}
                        </span>
                        <span class="text-[11px] text-gray-500 dark:text-zinc-400">Stok</span>
                        <span class="text-sm font-bold
                            {{ $product->stock == 0 || $product->stock <= 3
                                ? 'text-red-600 dark:text-red-400'
                                : ($product->stock <= 10 ? 'text-amber-600 dark:text-amber-400' : 'text-green-700 dark:text-green-400') }}">
                            {{ $product->stock > 0 ? $product->stock : 'Habis' }}
                        </span>
                    </div>

                    {{-- Berat --}}
                    <div class="flex flex-col items-center justify-center gap-1 p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50">
                        <span class="material-symbols-outlined text-xl text-gray-400 dark:text-zinc-500">scale</span>
                        <span class="text-[11px] text-gray-500 dark:text-zinc-400">Berat</span>
                        <span class="text-sm font-bold text-[#0d1b13] dark:text-white">
                            {{ $product->weight ? number_format($product->weight) . 'g' : '—' }}
                        </span>
                    </div>

                    {{-- Satuan --}}
                    <div class="flex flex-col items-center justify-center gap-1 p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50">
                        <span class="material-symbols-outlined text-xl text-gray-400 dark:text-zinc-500">tag</span>
                        <span class="text-[11px] text-gray-500 dark:text-zinc-400">Satuan</span>
                        <span class="text-sm font-bold text-[#0d1b13] dark:text-white capitalize">
                            {{ $product->unit ?? 'ekor' }}
                        </span>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="rounded-xl border border-gray-100 dark:border-zinc-800 overflow-hidden">
                    <div class="flex items-center gap-2 px-4 py-2.5 bg-gray-50 dark:bg-zinc-800/60 border-b border-gray-100 dark:border-zinc-800">
                        <span class="material-symbols-outlined text-primary text-base">description</span>
                        <span class="text-xs font-bold text-[#0d1b13] dark:text-white uppercase tracking-wider">Deskripsi Produk</span>
                    </div>
                    <div class="px-4 py-3">
                        <p class="text-sm text-gray-600 dark:text-zinc-300 leading-relaxed">
                            {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                        </p>
                    </div>
                </div>

                {{-- Pilih Jumlah + Tombol Aksi --}}
                @if($product->stock > 0)
                    <div class="space-y-3">

                        {{-- Quantity --}}
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-zinc-400">Jumlah</span>
                            <div class="flex items-center gap-0 border border-gray-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                                <button onclick="decreaseQuantity()"
                                        class="w-10 h-10 flex items-center justify-center
                                               bg-gray-50 dark:bg-zinc-800 hover:bg-gray-100 dark:hover:bg-zinc-700
                                               text-[#0d1b13] dark:text-white transition-colors border-r border-gray-200 dark:border-zinc-700">
                                    <span class="material-symbols-outlined text-sm">remove</span>
                                </button>
                                <input id="quantity" type="number" value="1" min="1" max="{{ $product->stock }}"
                                       class="w-14 h-10 text-center border-none bg-white dark:bg-zinc-900
                                              font-bold text-sm text-[#0d1b13] dark:text-white
                                              focus:ring-0 focus:outline-none">
                                <button onclick="increaseQuantity()"
                                        class="w-10 h-10 flex items-center justify-center
                                               bg-gray-50 dark:bg-zinc-800 hover:bg-gray-100 dark:hover:bg-zinc-700
                                               text-[#0d1b13] dark:text-white transition-colors border-l border-gray-200 dark:border-zinc-700">
                                    <span class="material-symbols-outlined text-sm">add</span>
                                </button>
                            </div>
                        </div>

                        {{-- Tombol utama: Keranjang (prioritas 1) + Beli Sekarang (prioritas 2) --}}
                        <button onclick="addToCart({{ $product->id }})"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3
                                       bg-primary hover:bg-primary/90 active:scale-[0.98]
                                       text-white rounded-xl font-bold text-sm
                                       transition-all hover:shadow-lg hover:shadow-primary/20">
                            <span class="material-symbols-outlined">add_shopping_cart</span>
                            Tambah ke Keranjang
                        </button>

                        <button id="btn-buy-now"
                                onclick="buyNow({{ $product->id }})"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3
                                       bg-[#0d1b13] dark:bg-white hover:opacity-90 active:scale-[0.98]
                                       text-white dark:text-[#0d1b13] rounded-xl font-bold text-sm
                                       transition-all border border-transparent">
                            <span class="material-symbols-outlined">bolt</span>
                            Beli Sekarang
                        </button>

                    </div>
                @else
                    {{-- Stok habis state --}}
                    <div class="p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-xl text-center">
                        <span class="material-symbols-outlined text-red-500 text-3xl mb-1 block">remove_shopping_cart</span>
                        <p class="text-sm font-semibold text-red-600 dark:text-red-400">Produk ini sedang tidak tersedia</p>
                        <p class="text-xs text-red-400 dark:text-red-500 mt-0.5">Stok habis — coba cek produk serupa di bawah</p>
                    </div>
                @endif

                {{-- Footer baris: Kembali + Share --}}
                <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-zinc-800 gap-3 flex-wrap">

                    <a href="{{ route('pembeli.produk.index') }}"
                       class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-zinc-400
                              hover:text-primary dark:hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        Kembali ke Katalog
                    </a>

                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-gray-400 dark:text-zinc-500 uppercase tracking-wider font-medium">Bagikan:</span>

                        <a href="https://wa.me/?text={{ urlencode($product->name . ' - Rp ' . number_format($product->price, 0, ',', '.') . ' | ' . url()->current()) }}"
                           target="_blank"
                           title="Bagikan ke WhatsApp"
                           class="w-8 h-8 flex items-center justify-center bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-white" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </a>

                        <button onclick="copyLink()"
                                id="copy-link-btn"
                                title="Salin link"
                                class="w-8 h-8 flex items-center justify-center bg-gray-100 dark:bg-zinc-800
                                       hover:bg-gray-200 dark:hover:bg-zinc-700
                                       text-gray-600 dark:text-zinc-300 rounded-lg transition-colors">
                            <span class="material-symbols-outlined text-sm" id="copy-link-icon">link</span>
                        </button>
                    </div>

                </div>

            </div>
            {{-- akhir KANAN --}}

        </div>
    </div>

    {{-- ================================================================
         PRODUK TERKAIT — pakai card style baru
         ================================================================ --}}
    @if($relatedProducts->count() > 0)
        <div class="bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm p-4 md:p-6">

            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-base">recommend</span>
                </div>
                <h2 class="text-base font-bold text-[#0d1b13] dark:text-white">Produk Terkait</h2>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                @foreach($relatedProducts as $related)
                    @php
                        $rStockEmpty    = $related->stock == 0;
                        $rStockCritical = $related->stock > 0 && $related->stock <= 3;
                        $rStockLow      = $related->stock > 3 && $related->stock <= 10;
                        $rStockPct      = $rStockEmpty ? 0 : min(100, round(($related->stock / 50) * 100));
                    @endphp

                    <div class="group flex flex-col bg-white dark:bg-zinc-900/50 rounded-xl border border-gray-100 dark:border-zinc-800
                                hover:border-primary/40 hover:shadow-md transition-all duration-200 overflow-hidden cursor-pointer">

                        {{-- Gambar --}}
                        <div class="relative aspect-square overflow-hidden bg-gray-100 dark:bg-zinc-800">
                            <a href="{{ route('pembeli.produk.show', $related->slug) }}" class="block w-full h-full">
                                @if($related->image)
                                    <img src="{{ asset('storage/' . $related->image) }}"
                                         alt="{{ $related->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-3xl">image</span>
                                    </div>
                                @endif
                            </a>

                            @if($rStockEmpty)
                                <div class="absolute inset-0 bg-black/55 flex items-center justify-center">
                                    <span class="px-2 py-0.5 bg-red-600 text-white text-[10px] font-bold rounded-full">Habis</span>
                                </div>
                            @endif

                            @if($related->category)
                                <div class="absolute top-1.5 left-1.5">
                                    <span class="px-1.5 py-0.5 bg-[#0d1b13]/70 backdrop-blur-sm text-primary text-[9px] font-bold rounded-full border border-primary/30">
                                        {{ Str::limit($related->category->parent->name ?? $related->category->name, 10) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex flex-col flex-1 p-2.5">
                            <a href="{{ route('pembeli.produk.show', $related->slug) }}">
                                <p class="text-xs font-semibold text-[#0d1b13] dark:text-white line-clamp-2 mb-1.5
                                          group-hover:text-primary transition-colors leading-snug">
                                    {{ $related->name }}
                                </p>
                            </a>

                            <div class="flex items-baseline gap-1 mb-2">
                                <span class="text-xs font-bold text-primary">
                                    Rp {{ number_format($related->price, 0, ',', '.') }}
                                </span>
                                <span class="text-[10px] text-gray-400">/ {{ $related->unit ?? 'ekor' }}</span>
                            </div>

                            {{-- Stock bar mini --}}
                            @if(!$rStockEmpty)
                                <div class="w-full h-1 bg-gray-100 dark:bg-zinc-700 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full
                                        {{ $rStockCritical ? 'bg-red-500' : ($rStockLow ? 'bg-amber-400' : 'bg-primary') }}"
                                         style="width: {{ $rStockPct }}%"></div>
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    @endif

</div>
{{-- akhir space-y-4 --}}

{{-- ================================================================
     MODAL ZOOM GAMBAR
     ================================================================ --}}
<div id="zoom-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/92 backdrop-blur-sm"
     onclick="closeZoom()">
    <div class="relative w-full h-full flex items-center justify-center p-4">

        <button onclick="closeZoom()"
                class="absolute top-4 right-4 w-10 h-10 bg-white/15 hover:bg-white/25 text-white rounded-full flex items-center justify-center z-10 transition-all">
            <span class="material-symbols-outlined">close</span>
        </button>

        <button onclick="prevZoom(event)"
                class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/15 hover:bg-white/25 text-white rounded-full flex items-center justify-center z-10 transition-all">
            <span class="material-symbols-outlined">chevron_left</span>
        </button>

        <img id="zoom-image" src="" alt="Zoom"
             class="max-w-full max-h-[88vh] object-contain rounded-xl shadow-2xl">

        <button onclick="nextZoom(event)"
                class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/15 hover:bg-white/25 text-white rounded-full flex items-center justify-center z-10 transition-all">
            <span class="material-symbols-outlined">chevron_right</span>
        </button>

        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 px-3 py-1 bg-black/60 text-white text-xs rounded-full">
            <span id="zoom-counter">1</span> / {{ count($product->images ?? [$product->image]) }}
        </div>
    </div>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
let currentStock      = {{ $product->stock }};
const productId       = {{ $product->id }};
const productImages   = @json($product->images ?? [$product->image]);
let currentImageIndex = 0;

// ===================== GALLERY =====================
function selectImage(index) {
    currentImageIndex = index;
    updateMainImage();
    updateThumbnails();
}

function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % productImages.length;
    updateMainImage(); updateThumbnails();
}

function prevImage() {
    currentImageIndex = (currentImageIndex - 1 + productImages.length) % productImages.length;
    updateMainImage(); updateThumbnails();
}

function updateMainImage() {
    const img     = document.getElementById('main-image');
    const counter = document.getElementById('current-image-index');
    if (img && productImages[currentImageIndex]) {
        img.style.opacity = '0';
        setTimeout(() => {
            img.src = "{{ asset('storage') }}/" + productImages[currentImageIndex];
            img.style.opacity = '1';
        }, 80);
    }
    if (counter) counter.textContent = currentImageIndex + 1;
}

function updateThumbnails() {
    document.querySelectorAll('.thumbnail-btn').forEach((thumb, i) => {
        thumb.classList.toggle('border-primary', i === currentImageIndex);
        thumb.classList.toggle('border-gray-200', i !== currentImageIndex);
        thumb.classList.toggle('dark:border-zinc-700', i !== currentImageIndex);
    });
    const selected = document.querySelectorAll('.thumbnail-btn')[currentImageIndex];
    if (selected) selected.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
}

// ===================== ZOOM =====================
function openZoom(index) {
    const modal = document.getElementById('zoom-modal');
    currentImageIndex = index;
    document.getElementById('zoom-image').src = "{{ asset('storage') }}/" + productImages[index];
    document.getElementById('zoom-counter').textContent = index + 1;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeZoom() {
    const modal = document.getElementById('zoom-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

function prevZoom(e) {
    e.stopPropagation();
    currentImageIndex = (currentImageIndex - 1 + productImages.length) % productImages.length;
    document.getElementById('zoom-image').src = "{{ asset('storage') }}/" + productImages[currentImageIndex];
    document.getElementById('zoom-counter').textContent = currentImageIndex + 1;
    updateThumbnails();
}

function nextZoom(e) {
    e.stopPropagation();
    currentImageIndex = (currentImageIndex + 1) % productImages.length;
    document.getElementById('zoom-image').src = "{{ asset('storage') }}/" + productImages[currentImageIndex];
    document.getElementById('zoom-counter').textContent = currentImageIndex + 1;
    updateThumbnails();
}

document.addEventListener('keydown', e => {
    const zoomOpen = !document.getElementById('zoom-modal').classList.contains('hidden');
    if (e.key === 'Escape') closeZoom();
    else if (e.key === 'ArrowLeft')  zoomOpen ? prevZoom(e) : prevImage();
    else if (e.key === 'ArrowRight') zoomOpen ? nextZoom(e) : nextImage();
});

// Swipe support
let touchStartX = 0;
const mainImgEl = document.getElementById('main-image');
if (mainImgEl) {
    mainImgEl.addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].screenX; });
    mainImgEl.addEventListener('touchend',   e => {
        const diff = touchStartX - e.changedTouches[0].screenX;
        if (diff > 50) nextImage();
        else if (diff < -50) prevImage();
    });
}

// ===================== QUANTITY =====================
function increaseQuantity() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) < currentStock) input.value++;
}

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) input.value--;
}

// ===================== CART =====================
function addToCart(productId) {
    const qty = parseInt(document.getElementById('quantity').value) || 1;
    fetch(`/pembeli/keranjang/tambah/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ quantity: qty })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (typeof window.updateCartCount === 'function') window.updateCartCount(data.cart_count);
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: `${qty} produk ditambahkan ke keranjang!`,
                toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
        } else {
            Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message || 'Gagal menambahkan ke keranjang',
                toast: true, position: 'top-end', timer: 3000, showConfirmButton: false });
        }
    })
    .catch(() => Swal.fire({ icon: 'error', title: 'Error!', text: 'Terjadi kesalahan jaringan.',
        toast: true, position: 'top-end', timer: 3000, showConfirmButton: false }));
}

// ===================== BUY NOW =====================
// ===================== BUY NOW (TANPA KERANJANG) =====================
function buyNow(productId) {
    const qty = parseInt(document.getElementById('quantity').value) || 1;
    const btn = document.getElementById('btn-buy-now');
 
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span> Memproses...';
 
    fetch('{{ route("pembeli.pesanan.buy-now") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            product_id: productId,
            quantity:   qty,
        }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Langsung redirect ke checkout — tidak masuk keranjang
            window.location.href = data.redirect_url;
        } else {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined">bolt</span> Beli Sekarang';
            Swal.fire({
                icon: 'error', title: 'Gagal!',
                text: data.message || 'Gagal memproses pesanan',
                toast: true, position: 'top-end', timer: 3000, showConfirmButton: false,
            });
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined">bolt</span> Beli Sekarang';
        Swal.fire({
            icon: 'error', title: 'Error!',
            text: 'Terjadi kesalahan jaringan.',
            toast: true, position: 'top-end', timer: 3000, showConfirmButton: false,
        });
    });
}
 
// Reset tombol saat back button browser
window.addEventListener('pageshow', () => {
    const btn = document.getElementById('btn-buy-now');
    if (btn) {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined">bolt</span> Beli Sekarang';
    }
});

// ===================== COPY LINK =====================
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        const icon = document.getElementById('copy-link-icon');
        const btn  = document.getElementById('copy-link-btn');
        icon.textContent = 'check';
        btn.classList.add('bg-green-100', 'dark:bg-green-500/20', 'text-green-600');
        btn.classList.remove('bg-gray-100', 'dark:bg-zinc-800', 'text-gray-600', 'dark:text-zinc-300');
        setTimeout(() => {
            icon.textContent = 'link';
            btn.classList.remove('bg-green-100', 'dark:bg-green-500/20', 'text-green-600');
            btn.classList.add('bg-gray-100', 'dark:bg-zinc-800', 'text-gray-600', 'dark:text-zinc-300');
        }, 2000);
    });
}
</script>
@endsection