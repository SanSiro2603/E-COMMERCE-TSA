{{-- resources/views/pembeli/produk/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Katalog Produk - E-Commerce TSA')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-4 md:p-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">
                    Katalog Produk
                </h1>
                <p class="text-xs md:text-sm text-gray-600 dark:text-zinc-400 mt-1">
                    Temukan hewan terbaik untuk kebutuhan Anda
                </p>
            </div>

            <!-- Search Form -->
            <form method="GET" id="searchForm" class="flex gap-2 w-full lg:w-auto">
                <div class="relative flex-1 lg:w-80">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500 text-lg">search</span>
                    <input type="text" 
                           name="search" 
                           id="searchInput"
                           value="{{ request('search') }}" 
                           placeholder="Cari produk..."
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                </div>
                <button type="submit" 
                        class="flex items-center gap-2 px-4 md:px-6 py-2.5 bg-gradient-to-r from-soft-green to-primary text-white font-medium rounded-lg hover:shadow-lg transition-all">
                    <span class="material-symbols-outlined text-lg">search</span>
                    <span class="hidden sm:inline text-sm">Cari</span>
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
                    <span class="text-xs md:text-sm font-medium text-gray-900 dark:text-white">Filter Kategori:</span>
                </div>
                <div class="flex flex-wrap gap-2" id="categoryFilters">
                    <a href="javascript:void(0)" 
                       data-category=""
                       class="category-filter inline-flex items-center gap-1 px-3 md:px-4 py-2 rounded-lg text-xs md:text-sm font-medium transition-all
                           {{ !request('category') ? 'bg-gradient-to-r from-soft-green to-primary text-white shadow-md' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-700' }}">
                        <span class="material-symbols-outlined text-base">grid_view</span>
                        Semua
                    </a>
                    @foreach($categories as $category)
                        <a href="javascript:void(0)"
                           data-category="{{ $category->id }}"
                           class="category-filter inline-flex items-center gap-1 px-3 md:px-4 py-2 rounded-lg text-xs md:text-sm font-medium transition-all
                               {{ request('category') == $category->id ? 'bg-gradient-to-r from-soft-green to-primary text-white shadow-md' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-700' }}">
                            <span class="material-symbols-outlined text-base">category</span>
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Results Count -->
            <div class="flex items-center gap-2 text-xs md:text-sm text-gray-600 dark:text-zinc-400 border-t md:border-t-0 md:border-l border-gray-200 dark:border-zinc-800 pt-4 md:pt-0 md:pl-4">
                <span class="material-symbols-outlined">inventory_2</span>
                <span id="resultsCount">Menampilkan <strong class="text-gray-900 dark:text-white">{{ $products->count() }}</strong> dari <strong class="text-gray-900 dark:text-white">{{ $products->total() }}</strong> produk</span>
            </div>
        </div>
    </div>

    <!-- Active Filters -->
    <div id="activeFilters" class="flex items-center gap-2 flex-wrap" style="{{ request('search') || request('category') ? '' : 'display: none;' }}">
        <span class="text-xs md:text-sm text-gray-600 dark:text-zinc-400">Filter Aktif:</span>
        
        @if(request('search'))
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 rounded-lg text-xs md:text-sm">
                <span class="material-symbols-outlined text-base">search</span>
                <span>"{{ request('search') }}"</span>
                <a href="javascript:void(0)" onclick="clearSearch()" class="hover:text-blue-900 dark:hover:text-blue-300">
                    <span class="material-symbols-outlined text-base">close</span>
                </a>
            </div>
        @endif

        @if(request('category'))
            @php
                $activeCategory = $categories->firstWhere('id', request('category'));
            @endphp
            @if($activeCategory)
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 rounded-lg text-xs md:text-sm">
                    <span class="material-symbols-outlined text-base">category</span>
                    <span>{{ $activeCategory->name }}</span>
                    <a href="javascript:void(0)" onclick="clearCategory()" class="hover:text-green-900 dark:hover:text-green-300">
                        <span class="material-symbols-outlined text-base">close</span>
                    </a>
                </div>
            @endif
        @endif

        <a href="javascript:void(0)" 
           onclick="clearAllFilters()"
           class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-500/30 rounded-lg text-xs md:text-sm font-medium transition-colors">
            <span class="material-symbols-outlined text-base">refresh</span>
            Reset Semua
        </a>
    </div>

    <!-- Products Grid -->
    <div id="productsContainer">
        @include('pembeli.produk.partials.products-grid', ['products' => $products])
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    const productsContainer = document.getElementById('productsContainer');
    const resultsCount = document.getElementById('resultsCount');
    const activeFiltersContainer = document.getElementById('activeFilters');
    const categoryFilters = document.querySelectorAll('.category-filter');

    let currentCategory = '{{ request("category", "") }}';
    let currentSearch = '{{ request("search", "") }}';
    let searchTimeout = null;

    // ===========================
    // AJAX Fetch Products
    // ===========================
    function fetchProducts(showLoading = true) {
        if (showLoading) {
            showLoadingState();
        }

        const params = new URLSearchParams();
        if (currentSearch) params.append('search', currentSearch);
        if (currentCategory) params.append('category', currentCategory);
        params.append('ajax', '1');

        fetch(`{{ route('pembeli.produk.index') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                productsContainer.innerHTML = data.html;
            }
            
            if (data.count !== undefined) {
                resultsCount.innerHTML = `Menampilkan <strong class="text-gray-900 dark:text-white">${data.count}</strong> dari <strong class="text-gray-900 dark:text-white">${data.total}</strong> produk`;
            }

            updateActiveFilters();
            updateURL();
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorState();
        });
    }

    // ===========================
    // Loading & Error States
    // ===========================
    function showLoadingState() {
        productsContainer.innerHTML = `
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
                <div class="text-center py-12 md:py-16 px-4">
                    <div class="w-12 h-12 md:w-16 md:h-16 border-4 border-soft-green border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                    <p class="text-xs md:text-sm text-gray-500 dark:text-zinc-400">Memuat produk...</p>
                </div>
            </div>
        `;
    }

    function showErrorState() {
        productsContainer.innerHTML = `
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
                <div class="text-center py-12 md:py-16 px-4">
                    <span class="material-symbols-outlined text-red-500 text-5xl md:text-6xl mb-3">error</span>
                    <p class="text-sm font-medium text-red-600 dark:text-red-400">Gagal memuat produk</p>
                    <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-soft-green text-white rounded-lg text-xs md:text-sm hover:shadow-lg transition-all">
                        Muat Ulang
                    </button>
                </div>
            </div>
        `;
    }

    // ===========================
    // Update Active Filters
    // ===========================
    function updateActiveFilters() {
        if (!currentSearch && !currentCategory) {
            activeFiltersContainer.style.display = 'none';
            return;
        }

        activeFiltersContainer.style.display = 'flex';
        
        let html = '<span class="text-xs md:text-sm text-gray-600 dark:text-zinc-400">Filter Aktif:</span>';

        if (currentSearch) {
            html += `
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 rounded-lg text-xs md:text-sm">
                    <span class="material-symbols-outlined text-base">search</span>
                    <span>"${currentSearch}"</span>
                    <a href="javascript:void(0)" onclick="clearSearch()" class="hover:text-blue-900 dark:hover:text-blue-300">
                        <span class="material-symbols-outlined text-base">close</span>
                    </a>
                </div>
            `;
        }

        if (currentCategory) {
            const activeFilter = document.querySelector(`.category-filter[data-category="${currentCategory}"]`);
            const categoryName = activeFilter ? activeFilter.textContent.trim() : 'Kategori';
            
            html += `
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 rounded-lg text-xs md:text-sm">
                    <span class="material-symbols-outlined text-base">category</span>
                    <span>${categoryName}</span>
                    <a href="javascript:void(0)" onclick="clearCategory()" class="hover:text-green-900 dark:hover:text-green-300">
                        <span class="material-symbols-outlined text-base">close</span>
                    </a>
                </div>
            `;
        }

        html += `
            <a href="javascript:void(0)" 
               onclick="clearAllFilters()"
               class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-500/30 rounded-lg text-xs md:text-sm font-medium transition-colors">
                <span class="material-symbols-outlined text-base">refresh</span>
                Reset Semua
            </a>
        `;

        activeFiltersContainer.innerHTML = html;
    }

    // ===========================
    // Update URL
    // ===========================
    function updateURL() {
        const params = new URLSearchParams();
        if (currentSearch) params.append('search', currentSearch);
        if (currentCategory) params.append('category', currentCategory);
        
        const newURL = params.toString() ? `?${params.toString()}` : window.location.pathname;
        window.history.pushState({}, '', newURL);
    }

    // ===========================
    // Event Listeners
    // ===========================
    
    // Search Input with Debounce
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentSearch = this.value.trim();
            fetchProducts();
        }, 500);
    });

    // Search Form Submit
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        currentSearch = searchInput.value.trim();
        fetchProducts();
    });

    // Category Filters
    categoryFilters.forEach(filter => {
        filter.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Update active state
            categoryFilters.forEach(f => {
                f.classList.remove('bg-gradient-to-r', 'from-soft-green', 'to-primary', 'text-white', 'shadow-md');
                f.classList.add('bg-gray-100', 'dark:bg-zinc-800', 'text-gray-700', 'dark:text-zinc-300');
            });
            
            this.classList.remove('bg-gray-100', 'dark:bg-zinc-800', 'text-gray-700', 'dark:text-zinc-300');
            this.classList.add('bg-gradient-to-r', 'from-soft-green', 'to-primary', 'text-white', 'shadow-md');
            
            currentCategory = this.dataset.category;
            fetchProducts();
        });
    });

    // ===========================
    // Clear Functions
    // ===========================
    window.clearSearch = function() {
        searchInput.value = '';
        currentSearch = '';
        fetchProducts();
    }

    window.clearCategory = function() {
        currentCategory = '';
        const allFilter = document.querySelector('.category-filter[data-category=""]');
        if (allFilter) allFilter.click();
    }

    window.clearAllFilters = function() {
        searchInput.value = '';
        currentSearch = '';
        currentCategory = '';
        
        categoryFilters.forEach(f => {
            f.classList.remove('bg-gradient-to-r', 'from-soft-green', 'to-primary', 'text-white', 'shadow-md');
            f.classList.add('bg-gray-100', 'dark:bg-zinc-800', 'text-gray-700', 'dark:text-zinc-300');
        });
        
        const allFilter = document.querySelector('.category-filter[data-category=""]');
        if (allFilter) {
            allFilter.classList.remove('bg-gray-100', 'dark:bg-zinc-800', 'text-gray-700', 'dark:text-zinc-300');
            allFilter.classList.add('bg-gradient-to-r', 'from-soft-green', 'to-primary', 'text-white', 'shadow-md');
        }
        
        fetchProducts();
    }

    // ===========================
    // Add to Cart
    // ===========================
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
                showToast(data.message || 'Produk berhasil ditambahkan ke keranjang!', 'success');
                updateCartCount(data.cart_count);
            } else {
                showToast(data.message || 'Gagal menambahkan produk ke keranjang', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Terjadi kesalahan saat menambahkan ke keranjang', 'error');
        });
    }

    // ===========================
    // Toast Notification
    // ===========================
    function showToast(message, type = 'success') {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed top-5 right-5 z-50 flex flex-col gap-2';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `flex items-center gap-2 px-4 py-3 rounded-lg shadow-lg text-white text-sm md:text-base animate-slide-in ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        
        toast.innerHTML = `
            <span class="material-symbols-outlined">${type === 'success' ? 'check_circle' : 'error'}</span>
            <span>${message}</span>
        `;
        
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slide-out 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // ===========================
    // Update Cart Count
    // ===========================
    function updateCartCount(count = null) {
        const countElem = document.getElementById('cart-count') || document.getElementById('cart-count-badge');
        if (!countElem) return;

        if (count !== null) {
            if (count > 0) {
                countElem.textContent = count;
                countElem.classList.remove('hidden');
            } else {
                countElem.classList.add('hidden');
            }
        } else {
            fetch('/pembeli/keranjang/count')
                .then(res => res.json())
                .then(data => {
                    if (data.count > 0) {
                        countElem.textContent = data.count;
                        countElem.classList.remove('hidden');
                    } else {
                        countElem.classList.add('hidden');
                    }
                })
                .catch(err => console.error('Error fetching cart count:', err));
        }
    }

    // Initialize cart count
    updateCartCount();
});

// Animation CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slide-in {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slide-out {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .animate-slide-in {
        animation: slide-in 0.3s ease-out;
    }
`;
document.head.appendChild(style);
</script>

@endsection