{{-- resources/views/pembeli/produk/partials/products-grid.blade.php --}}

@if($products->count() > 0)
    <!-- Products Grid -->
    <div class="bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm p-4 md:p-6">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($products as $product)
                <div class="group cursor-pointer">

                    <!-- Product Image -->
                    <div class="relative aspect-square rounded-xl overflow-hidden mb-3 bg-gray-100 dark:bg-background-dark border border-[#cfe7d9] dark:border-primary/20">
                        <a href="{{ route('pembeli.produk.show', $product->slug) }}">
                            @if($product->image)
                                <img class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                     src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-300 dark:text-gray-600 text-4xl">image</span>
                                </div>
                            @endif
                        </a>

                        {{-- =====================================================
                             BADGE STOK — tampil di pojok gambar
                             ===================================================== --}}
                        @if($product->stock == 0)
                            {{-- Overlay stok habis --}}
                            <div class="absolute inset-0 bg-black/60 flex items-center justify-center backdrop-blur-sm">
                                <span class="px-2.5 py-1 bg-red-500 text-white text-xs font-bold rounded shadow-lg tracking-wide">
                                    Stok Habis
                                </span>
                            </div>
                        @elseif($product->stock <= 3)
                            {{-- Stok kritis: merah --}}
                            <div class="absolute bottom-2 left-2 inline-flex items-center gap-1 px-2 py-0.5 bg-red-500 text-white rounded text-[10px] font-bold shadow">
                                <span class="material-symbols-outlined text-[11px]">warning</span>
                                Sisa {{ $product->stock }}
                            </div>
                        @elseif($product->stock <= 10)
                            {{-- Stok terbatas: kuning --}}
                            <div class="absolute bottom-2 left-2 inline-flex items-center gap-1 px-2 py-0.5 bg-yellow-500 text-white rounded text-[10px] font-bold shadow">
                                <span class="material-symbols-outlined text-[11px]">inventory_2</span>
                                Terbatas
                            </div>
                        @endif

                        {{-- Badge "Baru" — produk dibuat dalam 7 hari terakhir --}}
                        @if($product->created_at && $product->created_at->diffInDays(now()) <= 7 && $product->stock > 0)
                            <div class="absolute top-2 right-2 px-2 py-0.5 bg-primary text-white rounded text-[10px] font-bold shadow">
                                Baru
                            </div>
                        @endif

                        <!-- Category Badge -->
                        @if($product->category)
                            <div class="absolute top-2 left-2 flex flex-col gap-1">
                                @if($product->category->parent)
                                    <span class="px-2 py-0.5 bg-primary/90 backdrop-blur-sm text-white text-[10px] font-bold rounded">
                                        {{ Str::limit($product->category->parent->name, 12) }}
                                    </span>
                                    <span class="px-2 py-0.5 bg-purple-600/90 backdrop-blur-sm text-white text-[10px] font-bold rounded">
                                        {{ Str::limit($product->category->name, 12) }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 bg-primary/90 backdrop-blur-sm text-white text-[10px] font-bold rounded">
                                        {{ Str::limit($product->category->name, 15) }}
                                    </span>
                                @endif
                            </div>
                        @endif

                    </div>

                    <!-- Product Info -->
                    <a href="{{ route('pembeli.produk.show', $product->slug) }}">
                        <h4 class="font-bold text-sm text-[#0d1b13] dark:text-white line-clamp-2 mb-1 group-hover:text-primary transition-colors">
                            {{ $product->name }}
                        </h4>
                    </a>

                    <p class="text-primary font-bold text-sm mb-2">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    {{-- Info satuan & stok tersedia --}}
                    <div class="flex items-center justify-between text-[10px] text-gray-400 dark:text-gray-500 mb-3">
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">store</span>
                            <span>{{ $product->unit ?? 'ekor' }}</span>
                        </div>
                        @if($product->stock > 0)
                            <span class="text-gray-400 dark:text-zinc-500">Stok: {{ $product->stock }}</span>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        @if($product->stock > 0)
                            <button onclick="addToCart({{ $product->id }})"
                                    class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-primary text-white rounded-lg text-xs font-medium transition-all hover:bg-primary/90 hover:shadow-md active:scale-95">
                                <span class="material-symbols-outlined text-sm">add_shopping_cart</span>
                                <span class="hidden sm:inline">Keranjang</span>
                            </button>
                        @else
                            <button disabled
                                    class="flex-1 px-3 py-2 bg-gray-200 dark:bg-zinc-800 text-gray-400 dark:text-gray-500 rounded-lg text-xs font-medium cursor-not-allowed">
                                Habis
                            </button>
                        @endif

                        <a href="{{ route('pembeli.produk.show', $product->slug) }}"
                           class="flex items-center justify-center px-3 py-2 bg-gray-200 dark:bg-zinc-800 text-[#0d1b13] dark:text-white hover:bg-gray-300 dark:hover:bg-zinc-700 rounded-lg transition-all active:scale-95">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-12 flex justify-center items-center gap-2">
                @if($products->onFirstPage())
                    <button disabled class="w-10 h-10 flex items-center justify-center rounded-lg bg-white dark:bg-primary/10 border border-[#cfe7d9] dark:border-primary/20 text-gray-400 cursor-not-allowed">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-lg bg-white dark:bg-primary/10 border border-[#cfe7d9] dark:border-primary/20 hover:border-primary text-[#0d1b13] dark:text-white transition-colors">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </a>
                @endif

                @foreach(range(1, $products->lastPage()) as $page)
                    @if($page == 1 || $page == $products->lastPage() || abs($page - $products->currentPage()) <= 1)
                        <a href="{{ $products->url($page) }}"
                           class="w-10 h-10 flex items-center justify-center rounded-lg font-bold {{ $page == $products->currentPage() ? 'bg-primary text-white' : 'bg-white dark:bg-primary/10 border border-[#cfe7d9] dark:border-primary/20 hover:border-primary text-[#0d1b13] dark:text-white' }} transition-colors">
                            {{ $page }}
                        </a>
                    @elseif(abs($page - $products->currentPage()) == 2)
                        <span class="px-2 text-gray-400">...</span>
                    @endif
                @endforeach

                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-lg bg-white dark:bg-primary/10 border border-[#cfe7d9] dark:border-primary/20 hover:border-primary text-[#0d1b13] dark:text-white transition-colors">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                @else
                    <button disabled class="w-10 h-10 flex items-center justify-center rounded-lg bg-white dark:bg-primary/10 border border-[#cfe7d9] dark:border-primary/20 text-gray-400 cursor-not-allowed">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                @endif
            </div>
        @endif
    </div>

@else
    <!-- Empty State -->
    <div class="bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm">
        <div class="text-center py-12 md:py-16 px-4">
            <div class="inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-gray-100 dark:bg-primary/10 rounded-full mb-4">
                <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 text-4xl md:text-5xl">search_off</span>
            </div>
            <h3 class="text-lg md:text-xl font-bold text-[#0d1b13] dark:text-white mb-2">
                Produk Tidak Ditemukan
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">
                Tidak ada produk yang sesuai dengan pencarian atau filter Anda. Coba kata kunci lain atau hapus filter.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('pembeli.produk.index') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary text-white font-medium rounded-lg hover:shadow-lg active:scale-95 transition-all text-sm">
                    <span class="material-symbols-outlined text-lg">refresh</span>
                    Reset Filter
                </a>
                <a href="{{ route('pembeli.dashboard') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-100 dark:bg-primary/10 text-[#0d1b13] dark:text-white font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-primary/20 active:scale-95 transition-all text-sm">
                    <span class="material-symbols-outlined text-lg">home</span>
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