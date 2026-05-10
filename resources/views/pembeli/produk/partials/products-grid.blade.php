{{-- resources/views/pembeli/produk/partials/products-grid.blade.php --}}

@if($products->count() > 0)
    <div class="bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm p-4 md:p-6">

        {{-- Grid: 2 kolom default, 3 di md, 4 di xl --}}
        {{-- Dikurangi dari lg:grid-cols-4 karena sekarang ada sidebar kiri --}}
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-4">
            @foreach($products as $product)

                @php
                    // Tentukan kondisi stok sekali pakai di semua tempat
                    $stockEmpty    = $product->stock == 0;
                    $stockCritical = $product->stock > 0 && $product->stock <= 3;
                    $stockLow      = $product->stock > 3 && $product->stock <= 10;
                    $stockOk       = $product->stock > 10;

                    // Persentase stock bar — max visual 50 supaya tidak terlalu cepat penuh
                    $stockPct = $stockEmpty ? 0 : min(100, round(($product->stock / 50) * 100));
                @endphp

                <div class="group flex flex-col bg-white dark:bg-zinc-900/50 rounded-xl border border-gray-100 dark:border-zinc-800
                            hover:border-primary/40 dark:hover:border-primary/40 hover:shadow-md
                            transition-all duration-200 overflow-hidden cursor-pointer">

                    {{-- ======================================================
                         GAMBAR
                         ====================================================== --}}
                    <div class="relative aspect-square overflow-hidden bg-gray-100 dark:bg-zinc-800">
                        <a href="{{ route('pembeli.produk.show', $product->slug) }}" class="block w-full h-full">
                            @if($product->image)
                                <img class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                     src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center gap-1">
                                    <span class="material-symbols-outlined text-gray-300 dark:text-gray-600 text-4xl">image</span>
                                    <span class="text-[10px] text-gray-300 dark:text-gray-600">Tidak ada foto</span>
                                </div>
                            @endif
                        </a>

                        {{-- Overlay stok habis --}}
                        @if($stockEmpty)
                            <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px] flex items-center justify-center">
                                <span class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded-full shadow-lg tracking-wide">
                                    Stok Habis
                                </span>
                            </div>
                        @endif

                        {{-- Badge Baru (pojok kanan atas) --}}
                        @if($product->created_at && $product->created_at->diffInDays(now()) <= 7 && !$stockEmpty)
                            <div class="absolute top-2 right-2 px-2 py-0.5 bg-primary text-white text-[10px] font-bold rounded-full shadow">
                                Baru
                            </div>
                        @endif

                        {{-- Badge Kategori (pojok kiri atas) --}}
                        @if($product->category)
                            <div class="absolute top-2 left-2 flex flex-col gap-1">
                                @if($product->category->parent)
                                    <span class="px-2 py-0.5 bg-[#0d1b13]/70 backdrop-blur-sm text-primary text-[10px] font-bold rounded-full border border-primary/30">
                                        {{ Str::limit($product->category->parent->name, 12) }}
                                    </span>
                                    <span class="px-2 py-0.5 bg-purple-900/70 backdrop-blur-sm text-purple-300 text-[10px] font-bold rounded-full border border-purple-500/30">
                                        {{ Str::limit($product->category->name, 12) }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 bg-[#0d1b13]/70 backdrop-blur-sm text-primary text-[10px] font-bold rounded-full border border-primary/30">
                                        {{ Str::limit($product->category->name, 15) }}
                                    </span>
                                @endif
                            </div>
                        @endif

                        {{-- Badge stok kritis di atas gambar (hanya jika ada stok) --}}
                        @if($stockCritical)
                            <div class="absolute bottom-2 left-2 inline-flex items-center gap-1 px-2 py-0.5 bg-red-500/90 backdrop-blur-sm text-white rounded-full text-[10px] font-bold shadow">
                                <span class="material-symbols-outlined text-[11px]">warning</span>
                                Sisa {{ $product->stock }}
                            </div>
                        @elseif($stockLow)
                            <div class="absolute bottom-2 left-2 inline-flex items-center gap-1 px-2 py-0.5 bg-amber-500/90 backdrop-blur-sm text-white rounded-full text-[10px] font-bold shadow">
                                <span class="material-symbols-outlined text-[11px]">inventory_2</span>
                                Terbatas
                            </div>
                        @endif
                    </div>

                    {{-- ======================================================
                         INFO PRODUK
                         ====================================================== --}}
                    <div class="flex flex-col flex-1 p-3">

                        {{-- Nama produk --}}
                        <a href="{{ route('pembeli.produk.show', $product->slug) }}">
                            <h4 class="font-bold text-xs md:text-sm text-[#0d1b13] dark:text-white line-clamp-2 mb-2
                                       group-hover:text-primary transition-colors leading-snug">
                                {{ $product->name }}
                            </h4>
                        </a>

                        {{-- Harga + satuan dalam satu baris --}}
                        <div class="flex items-baseline gap-1 mb-2">
                            <span class="text-primary font-bold text-sm md:text-base leading-none">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] text-gray-400 dark:text-zinc-500 leading-none">
                                / {{ $product->unit ?? 'ekor' }}
                            </span>
                        </div>

                        {{-- Stock bar --}}
                        <div class="mb-3">
                            @if(!$stockEmpty)
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[10px] text-gray-400 dark:text-zinc-500">Stok tersedia</span>
                                    <span class="text-[10px] font-semibold
                                        {{ $stockCritical ? 'text-red-500' : ($stockLow ? 'text-amber-500' : 'text-green-600 dark:text-green-400') }}">
                                        {{ $product->stock }}
                                    </span>
                                </div>
                                <div class="w-full h-1.5 bg-gray-100 dark:bg-zinc-700 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-300
                                        {{ $stockCritical ? 'bg-red-500' : ($stockLow ? 'bg-amber-400' : 'bg-primary') }}"
                                         style="width: {{ $stockPct }}%">
                                    </div>
                                </div>
                            @else
                                {{-- Bar kosong jika habis --}}
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[10px] text-gray-400 dark:text-zinc-500">Stok tersedia</span>
                                    <span class="text-[10px] font-semibold text-red-500">0</span>
                                </div>
                                <div class="w-full h-1.5 bg-gray-100 dark:bg-zinc-700 rounded-full overflow-hidden">
                                    <div class="h-full w-0 rounded-full bg-red-500"></div>
                                </div>
                            @endif
                        </div>

                        {{-- Spacer supaya tombol selalu di bawah --}}
                        <div class="flex-1"></div>

                        {{-- Tombol Aksi --}}
                        <div class="flex gap-2 mt-auto">
                            @if(!$stockEmpty)
                                <button onclick="addToCart({{ $product->id }})"
                                        class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2
                                               bg-primary hover:bg-primary/90 active:scale-95
                                               text-white rounded-lg text-xs font-medium
                                               transition-all hover:shadow-md">
                                    <span class="material-symbols-outlined text-sm">add_shopping_cart</span>
                                    <span class="hidden sm:inline">Keranjang</span>
                                </button>
                            @else
                                <button disabled
                                        class="flex-1 px-3 py-2 bg-gray-100 dark:bg-zinc-800
                                               text-gray-400 dark:text-gray-500
                                               rounded-lg text-xs font-medium cursor-not-allowed">
                                    Stok Habis
                                </button>
                            @endif

                            <a href="{{ route('pembeli.produk.show', $product->slug) }}"
                               title="Lihat Detail"
                               class="flex items-center justify-center w-9 h-9 flex-shrink-0
                                      bg-gray-100 dark:bg-zinc-800
                                      hover:bg-gray-200 dark:hover:bg-zinc-700
                                      text-[#0d1b13] dark:text-white
                                      rounded-lg transition-all active:scale-95">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                            </a>
                        </div>

                    </div>
                    {{-- akhir info produk --}}

                </div>
                {{-- akhir card --}}

            @endforeach
        </div>

        {{-- ======================================================
             PAGINATION
             ====================================================== --}}
        @if($products->hasPages())
            <div class="mt-10 flex justify-center items-center gap-1.5">

                {{-- Prev --}}
                @if($products->onFirstPage())
                    <button disabled
                            class="w-9 h-9 flex items-center justify-center rounded-lg
                                   bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700
                                   text-gray-300 cursor-not-allowed">
                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                    </button>
                @else
                    <a href="{{ $products->previousPageUrl() }}"
                       class="w-9 h-9 flex items-center justify-center rounded-lg
                              bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700
                              hover:border-primary text-[#0d1b13] dark:text-white transition-colors">
                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                    </a>
                @endif

                {{-- Halaman --}}
                @foreach(range(1, $products->lastPage()) as $page)
                    @if($page == 1 || $page == $products->lastPage() || abs($page - $products->currentPage()) <= 1)
                        <a href="{{ $products->url($page) }}"
                           class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-bold transition-colors
                               {{ $page == $products->currentPage()
                                    ? 'bg-primary text-white shadow-sm'
                                    : 'bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 hover:border-primary text-[#0d1b13] dark:text-white' }}">
                            {{ $page }}
                        </a>
                    @elseif(abs($page - $products->currentPage()) == 2)
                        <span class="w-9 h-9 flex items-center justify-center text-gray-400 text-sm">…</span>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}"
                       class="w-9 h-9 flex items-center justify-center rounded-lg
                              bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700
                              hover:border-primary text-[#0d1b13] dark:text-white transition-colors">
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                @else
                    <button disabled
                            class="w-9 h-9 flex items-center justify-center rounded-lg
                                   bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700
                                   text-gray-300 cursor-not-allowed">
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </button>
                @endif

            </div>
        @endif

    </div>

@else

    {{-- ======================================================
         EMPTY STATE
         ====================================================== --}}
    <div class="bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm">
        <div class="text-center py-14 md:py-20 px-4">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 dark:bg-primary/10 rounded-full mb-5">
                <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 text-5xl">search_off</span>
            </div>
            <h3 class="text-lg md:text-xl font-bold text-[#0d1b13] dark:text-white mb-2">
                Produk Tidak Ditemukan
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 max-w-sm mx-auto leading-relaxed">
                Tidak ada produk yang sesuai dengan filter kamu. Coba ubah kategori atau hapus filter harga.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('pembeli.produk.index') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-2.5
                          bg-primary text-white font-medium rounded-lg
                          hover:bg-primary/90 hover:shadow-md active:scale-95 transition-all text-sm">
                    <span class="material-symbols-outlined text-base">refresh</span>
                    Reset Filter
                </a>
                <a href="{{ route('pembeli.dashboard') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-2.5
                          bg-gray-100 dark:bg-zinc-800 text-[#0d1b13] dark:text-white font-medium rounded-lg
                          hover:bg-gray-200 dark:hover:bg-zinc-700 active:scale-95 transition-all text-sm">
                    <span class="material-symbols-outlined text-base">home</span>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

@endif

<script>
window.addToCart = function(productId) {
    fetch(`/pembeli/keranjang/tambah/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ quantity: 1 })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (typeof updateCartCount === 'function') updateCartCount(data.cart_count);
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message || 'Produk berhasil ditambahkan ke keranjang!',
                toast: true,
                position: 'top-end',
                timer: 2000,
                showConfirmButton: false,
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message || 'Gagal menambahkan produk ke keranjang',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });
        }
    })
    .catch(() => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Terjadi kesalahan jaringan.',
            toast: true,
            position: 'top-end',
            timer: 3000,
            showConfirmButton: false,
        });
    });
}
</script>

<style>
@keyframes slide-in {
    from { transform: translateX(100%); opacity: 0; }
    to   { transform: translateX(0);    opacity: 1; }
}
.animate-slide-in { animation: slide-in 0.3s ease-out; }
</style>