{{-- resources/views/pembeli/produk/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Katalog Produk - Lembah Hijau')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">
                    Katalog Produk
                </h1>
                <p class="text-sm text-gray-600 dark:text-zinc-400 mt-1">
                    Temukan hewan terbaik untuk kebutuhan Anda
                </p>
            </div>

            <!-- Search Form -->
            <form method="GET" class="flex gap-2 w-full lg:w-auto">
                <div class="relative flex-1 lg:w-80">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500">search</span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari produk..."
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                </div>
                <button type="submit" 
                        class="flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-soft-green to-primary text-white font-medium rounded-lg hover:shadow-lg transition-all">
                    <span class="material-symbols-outlined text-lg">search</span>
                    <span class="hidden sm:inline">Cari</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Filter & Sort Bar -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Category Filter -->
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-gray-600 dark:text-zinc-400 text-lg">filter_list</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Filter Kategori:</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('pembeli.produk.index') }}" 
                       class="inline-flex items-center gap-1 px-4 py-2 rounded-lg text-sm font-medium transition-all
                           {{ !request('category') ? 'bg-gradient-to-r from-soft-green to-primary text-white shadow-md' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-700' }}">
                        <span class="material-symbols-outlined text-base">grid_view</span>
                        Semua
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('pembeli.produk.index', ['category' => $category->id, 'search' => request('search')]) }}"
                           class="inline-flex items-center gap-1 px-4 py-2 rounded-lg text-sm font-medium transition-all
                               {{ request('category') == $category->id ? 'bg-gradient-to-r from-soft-green to-primary text-white shadow-md' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-700' }}">
                            <span class="material-symbols-outlined text-base">category</span>
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Results Count -->
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-zinc-400 border-t md:border-t-0 md:border-l border-gray-200 dark:border-zinc-800 pt-4 md:pt-0 md:pl-4">
                <span class="material-symbols-outlined">inventory_2</span>
                <span>Menampilkan <strong class="text-gray-900 dark:text-white">{{ $products->count() }}</strong> dari <strong class="text-gray-900 dark:text-white">{{ $products->total() }}</strong> produk</span>
            </div>
        </div>
    </div>

    <!-- Active Filters -->
    @if(request('search') || request('category'))
        <div class="flex items-center gap-2 flex-wrap">
            <span class="text-sm text-gray-600 dark:text-zinc-400">Filter Aktif:</span>
            
            @if(request('search'))
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 rounded-lg text-sm">
                    <span class="material-symbols-outlined text-base">search</span>
                    <span>"{{ request('search') }}"</span>
                    <a href="{{ route('pembeli.produk.index', ['category' => request('category')]) }}" 
                       class="hover:text-blue-900 dark:hover:text-blue-300">
                        <span class="material-symbols-outlined text-base">close</span>
                    </a>
                </div>
            @endif

            @if(request('category'))
                @php
                    $activeCategory = $categories->firstWhere('id', request('category'));
                @endphp
                @if($activeCategory)
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 rounded-lg text-sm">
                        <span class="material-symbols-outlined text-base">category</span>
                        <span>{{ $activeCategory->name }}</span>
                        <a href="{{ route('pembeli.produk.index', ['search' => request('search')]) }}" 
                           class="hover:text-green-900 dark:hover:text-green-300">
                            <span class="material-symbols-outlined text-base">close</span>
                        </a>
                    </div>
                @endif
            @endif

            <a href="{{ route('pembeli.produk.index') }}" 
               class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-500/30 rounded-lg text-sm font-medium transition-colors">
                <span class="material-symbols-outlined text-base">refresh</span>
                Reset Semua
            </a>
        </div>
    @endif

    <!-- Products Grid -->
    @if($products->count() > 0)
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="group bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm hover:shadow-lg transition-all overflow-hidden">
                    <!-- Product Image -->
                    <a href="{{ route('pembeli.produk.show', $product->slug) }}" 
                class="block relative overflow-hidden bg-gray-100 dark:bg-zinc-800 rounded-t-lg">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                            alt="{{ $product->name }}"
                            class="block w-[110%] max-w-none -ml-[5%] object-cover transition-transform duration-300 hover:scale-110 rounded-t-lg product-image">
                    @else
                        <div class="w-[110%] max-w-none -ml-[5%] flex items-center justify-center product-image">
                            <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-6xl">image</span>
                        </div>
                    @endif

                    @if($product->stock <= 5 && $product->stock > 0)
                        <div class="absolute top-2 right-2 px-2 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full">
                            Stok {{ $product->stock }}
                        </div>
                    @elseif($product->stock == 0)
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                            <span class="px-3 py-1.5 bg-red-500 text-white text-sm font-bold rounded-full">
                                Habis
                            </span>
                        </div>
                    @endif
                </a>

                    <!-- Product Info -->
                    <div class="p-4">
                        <!-- Category Badge -->
                        <div class="mb-2">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400 text-xs font-medium rounded-full">
                                <span class="material-symbols-outlined text-xs">category</span>
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                        </div>

                        <!-- Product Name -->
                        <a href="{{ route('pembeli.produk.show', $product->slug) }}" 
                           class="block">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-2 group-hover:text-soft-green transition-colors mb-2">
                                {{ $product->name }}
                            </h3>
                        </a>

                        <!-- Description -->
                        @if($product->description)
                            <p class="text-xs text-gray-600 dark:text-zinc-400 line-clamp-2 mb-3">
                                {{ $product->description }}
                            </p>
                        @endif

                        <!-- Price -->
                        <div class="mb-3">
                            <p class="text-lg font-bold text-soft-green dark:text-soft-green">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            @if($product->stock > 0)
                                <button onclick="addToCart({{ $product->id }})"
                                        class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-gradient-to-r from-soft-green to-primary text-white rounded-lg text-xs font-medium hover:shadow-md transition-all">
                                    <span class="material-symbols-outlined text-base">shopping_cart</span>
                                    <span class="hidden sm:inline">Keranjang</span>
                                </button>
                                <a href="{{ route('pembeli.produk.show', $product->slug) }}"
                                   class="px-3 py-2 bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-700 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-base">visibility</span>
                                </a>
                            @else
                                <button disabled
                                        class="flex-1 px-3 py-2 bg-gray-200 dark:bg-zinc-800 text-gray-400 dark:text-zinc-500 rounded-lg text-xs font-medium cursor-not-allowed">
                                    Stok Habis
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="text-center py-16 px-4">
                <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-8xl mb-4">search_off</span>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    Produk Tidak Ditemukan
                </h3>
                <p class="text-gray-600 dark:text-zinc-400 mb-6">
                    Maaf, tidak ada produk yang sesuai dengan pencarian Anda
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('pembeli.produk.index') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-soft-green to-primary text-white font-medium rounded-lg hover:shadow-lg transition-all">
                        <span class="material-symbols-outlined">refresh</span>
                        Reset Filter
                    </a>
                    <a href="{{ route('pembeli.dashboard') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-700 transition-colors">
                        <span class="material-symbols-outlined">home</span>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    @endif

</div>

<script>
    // Add to Cart Function
    function addToCart(productId) {
        fetch(`/pembeli/keranjang/tambah/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ quantity: 1 })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Produk berhasil ditambahkan ke keranjang!');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menambahkan produk ke keranjang');
        });
    }

    // AJAX Search & Filter
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.querySelector('form[method="GET"]');
        const searchInput = document.querySelector('input[name="search"]');
        const categoryLinks = document.querySelectorAll('a[href*="category"]');
        const productsContainer = document.querySelector('.grid.grid-cols-2');
        const resultsCount = document.querySelector('.flex.items-center.gap-2.text-sm span:last-child');
        const activeFiltersContainer = document.querySelector('.flex.items-center.gap-2.flex-wrap');
        
        let debounceTimer;
        let currentCategory = '{{ request("category") }}';
        let currentSearch = '{{ request("search") }}';

        // Debounce function
        function debounce(func, delay) {
            return function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(func, delay);
            };
        }

        // Fetch products with AJAX
        async function fetchProducts() {
            const search = searchInput.value;
            const url = new URL(window.location.href);
            
            url.searchParams.set('search', search);
            if (currentCategory) {
                url.searchParams.set('category', currentCategory);
            } else {
                url.searchParams.delete('category');
            }
            url.searchParams.set('ajax', '1');

            // Show loading state
            showLoading();

            try {
                const response = await fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();
                
                // Update products grid
                if (data.html) {
                    productsContainer.innerHTML = data.html;
                }

                // Update results count
                if (data.count !== undefined) {
                    updateResultsCount(data.count, data.total);
                }

                // Update active filters
                if (data.filters !== undefined) {
                    updateActiveFilters(data.filters);
                }

                // Update URL without reload
                const newUrl = url.toString().replace('&ajax=1', '');
                window.history.pushState({}, '', newUrl);

                // Update current values
                currentSearch = search;

            } catch (error) {
                console.error('Error fetching products:', error);
                showError();
            }
        }

        // Show loading state
        function showLoading() {
            productsContainer.innerHTML = `
                <div class="col-span-full py-16 text-center">
                    <div class="w-16 h-16 border-4 border-soft-green border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                    <p class="text-sm text-gray-500 dark:text-zinc-400">Memuat produk...</p>
                </div>
            `;
        }

        // Show error state
        function showError() {
            productsContainer.innerHTML = `
                <div class="col-span-full py-16 text-center">
                    <span class="material-symbols-outlined text-red-500 text-6xl mb-3">error</span>
                    <p class="text-sm font-medium text-red-600 dark:text-red-400">Gagal memuat produk</p>
                    <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-soft-green text-white rounded-lg text-sm">
                        Muat Ulang
                    </button>
                </div>
            `;
        }

        // Update results count
        function updateResultsCount(count, total) {
            resultsCount.innerHTML = `Menampilkan <strong class="text-gray-900 dark:text-white">${count}</strong> dari <strong class="text-gray-900 dark:text-white">${total}</strong> produk`;
        }

        // Update active filters display
        function updateActiveFilters(filters) {
            if (!activeFiltersContainer) return;

            let html = '<span class="text-sm text-gray-600 dark:text-zinc-400">Filter Aktif:</span>';

            if (filters.search) {
                html += `
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 rounded-lg text-sm">
                        <span class="material-symbols-outlined text-base">search</span>
                        <span>"${filters.search}"</span>
                        <a href="#" onclick="clearSearch(event)" class="hover:text-blue-900 dark:hover:text-blue-300">
                            <span class="material-symbols-outlined text-base">close</span>
                        </a>
                    </div>
                `;
            }

            if (filters.category) {
                html += `
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 rounded-lg text-sm">
                        <span class="material-symbols-outlined text-base">category</span>
                        <span>${filters.categoryName}</span>
                        <a href="#" onclick="clearCategory(event)" class="hover:text-green-900 dark:hover:text-green-300">
                            <span class="material-symbols-outlined text-base">close</span>
                        </a>
                    </div>
                `;
            }

            if (filters.search || filters.category) {
                html += `
                    <a href="#" onclick="clearAllFilters(event)" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-500/30 rounded-lg text-sm font-medium transition-colors">
                        <span class="material-symbols-outlined text-base">refresh</span>
                        Reset Semua
                    </a>
                `;
            }

            activeFiltersContainer.innerHTML = html;
        }

        // Event listener for search input
        searchInput.addEventListener('input', debounce(fetchProducts, 500));

        // Event listener for search form submit
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchProducts();
        });

        // Event listener for category links
        categoryLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Update active state
                categoryLinks.forEach(l => {
                    l.classList.remove('bg-gradient-to-r', 'from-soft-green', 'to-primary', 'text-white', 'shadow-md');
                    l.classList.add('bg-gray-100', 'dark:bg-zinc-800', 'text-gray-700', 'dark:text-zinc-300');
                });
                this.classList.remove('bg-gray-100', 'dark:bg-zinc-800', 'text-gray-700', 'dark:text-zinc-300');
                this.classList.add('bg-gradient-to-r', 'from-soft-green', 'to-primary', 'text-white', 'shadow-md');

                // Get category from URL
                const url = new URL(this.href);
                currentCategory = url.searchParams.get('category') || '';
                
                fetchProducts();
            });
        });
    });

    // Clear search filter
    function clearSearch(e) {
        e.preventDefault();
        document.querySelector('input[name="search"]').value = '';
        document.querySelector('input[name="search"]').dispatchEvent(new Event('input'));
    }

    // Clear category filter
    function clearCategory(e) {
        e.preventDefault();
        currentCategory = '';
        document.querySelector('a[href*="Semua"]').click();
    }

    // Clear all filters
    function clearAllFilters(e) {
        e.preventDefault();
        document.querySelector('input[name="search"]').value = '';
        currentCategory = '';
        window.location.href = '{{ route("pembeli.produk.index") }}';
    }
</script>
@endsection