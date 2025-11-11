{{-- resources/views/pembeli/produk/partials/products-grid.blade.php --}}

@if($products->count() > 0)
    <!-- Products Grid - Konsisten & Minimalis -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 md:gap-4">
        @foreach($products as $product)
            <div class="group bg-white dark:bg-zinc-900 rounded-lg border border-gray-200 dark:border-zinc-800 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden">
                
                <!-- Product Image - Aspect Ratio Lebih Landscape -->
                <a href="{{ route('pembeli.produk.show', $product->slug) }}" 
                   class="block relative overflow-hidden bg-gray-100 dark:bg-zinc-800 aspect-[4/3]">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                             loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-3xl md:text-4xl">image</span>
                        </div>
                    @endif

                    <!-- Stock Badge -->
                    @if($product->stock <= 5 && $product->stock > 0)
                        <div class="absolute top-1.5 right-1.5 px-1.5 py-0.5 bg-yellow-500 text-white text-xs font-semibold rounded shadow-sm">
                            {{ $product->stock }}
                        </div>
                    @elseif($product->stock == 0)
                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center backdrop-blur-sm">
                            <span class="px-2.5 py-0.5 bg-red-500 text-white text-xs font-bold rounded shadow-lg">
                                Habis
                            </span>
                        </div>
                    @endif
                </a>

                <!-- Product Info - Compact -->
                <div class="p-2.5 flex flex-col flex-grow">
                    
                    <!-- Category Badge -->
                    <div class="mb-1.5">
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 text-[10px] font-medium rounded">
                            <span class="material-symbols-outlined text-[10px]">category</span>
                            <span class="truncate max-w-[100px]">{{ $product->category->name ?? 'Uncategorized' }}</span>
                        </span>
                    </div>

                    <!-- Product Name - Compact -->
                    <a href="{{ route('pembeli.produk.show', $product->slug) }}" class="block mb-1.5">
                        <h3 class="text-xs md:text-sm font-semibold text-gray-900 dark:text-white line-clamp-2 min-h-[2rem] group-hover:text-soft-green transition-colors leading-tight">
                            {{ $product->name }}
                        </h3>
                    </a>

                    <!-- Spacer untuk Push Price & Actions ke Bawah -->
                    <div class="flex-grow"></div>

                    <!-- Price - Compact -->
                    <div class="mb-2">
                        <p class="text-sm md:text-base font-bold text-soft-green dark:text-soft-green">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- Actions - Compact -->
                    <div class="flex gap-1.5">
                        @if($product->stock > 0)
                            <button onclick="addToCart({{ $product->id }})"
                                    class="flex-1 flex items-center justify-center gap-1 px-2 py-1.5 bg-gradient-to-r from-soft-green to-primary text-white rounded text-[10px] md:text-xs font-medium hover:shadow-md active:scale-95 transition-all">
                                <span class="material-symbols-outlined text-sm">shopping_cart</span>
                                <span class="hidden sm:inline">Keranjang</span>
                            </button>
                            <a href="{{ route('pembeli.produk.show', $product->slug) }}"
                               class="flex items-center justify-center px-2 py-1.5 bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-700 rounded active:scale-95 transition-all">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                            </a>
                        @else
                            <button disabled
                                    class="flex-1 px-2 py-1.5 bg-gray-200 dark:bg-zinc-800 text-gray-400 dark:text-zinc-500 rounded text-[10px] md:text-xs font-medium cursor-not-allowed">
                                <span class="hidden sm:inline">Stok Habis</span>
                                <span class="sm:hidden">Habis</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-4 mt-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs md:text-sm text-gray-600 dark:text-zinc-400">
                    Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk
                </div>
                <div class="flex gap-2">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    @endif

@else
    <!-- Empty State - Minimalis -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
        <div class="text-center py-12 md:py-16 px-4">
            <div class="inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-gray-100 dark:bg-zinc-800 rounded-full mb-4">
                <span class="material-symbols-outlined text-gray-400 dark:text-zinc-600 text-4xl md:text-5xl">search_off</span>
            </div>
            <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white mb-2">
                Produk Tidak Ditemukan
            </h3>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mb-6 max-w-md mx-auto">
                Tidak ada produk yang sesuai dengan pencarian atau filter Anda. Coba kata kunci lain atau hapus filter.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('pembeli.produk.index') }}" 
                   class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gradient-to-r from-soft-green to-primary text-white font-medium rounded-lg hover:shadow-lg active:scale-95 transition-all text-sm">
                    <span class="material-symbols-outlined text-lg">refresh</span>
                    Reset Filter
                </a>
                <a href="{{ route('pembeli.dashboard') }}" 
                   class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-700 active:scale-95 transition-all text-sm">
                    <span class="material-symbols-outlined text-lg">home</span>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
@endif