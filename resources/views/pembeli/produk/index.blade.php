{{-- resources/views/pembeli/produk/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Katalog Produk - Tunas Sejahtera Adi Perkasa')

@section('content')

<!-- Page Header -->
<div class="mt-6 bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm p-4 md:p-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-[#0d1b13] dark:text-white tracking-tight">
                Semua Hewan
            </h1>
            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mt-1">
                Menampilkan <span id="results-count">{{ $products->count() }}</span> hasil untuk Anda
            </p>
        </div>
        
        @if(request('search'))
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 rounded-lg text-sm">
                <span class="material-symbols-outlined text-base">search</span>
                <span>Hasil pencarian: "<strong>{{ request('search') }}</strong>"</span>
                <a href="{{ route('pembeli.produk.index') }}" class="hover:text-blue-900 dark:hover:text-blue-300">
                    <span class="material-symbols-outlined text-base">close</span>
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Filter & Sort Bar -->
<div class="mt-6 bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm p-4">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        
        <!-- Filter Buttons -->
        <div class="flex items-center gap-3 flex-wrap w-full md:w-auto">
            <div class="flex items-center gap-2 overflow-x-auto pb-2" id="categoryFilters">
                <a href="javascript:void(0)" 
                   data-category=""
                   class="category-filter inline-flex items-center gap-1 px-3 md:px-4 py-2 border-2 rounded-lg text-xs md:text-sm font-medium transition-all whitespace-nowrap
                       {{ !request('category') ? 'border-primary bg-primary text-white shadow-sm' : 'border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-[#0d1b13] dark:text-white hover:border-primary hover:bg-primary/10' }}">
                    <span class="material-symbols-outlined text-base">grid_view</span>
                    Semua
                </a>
                @foreach($categories as $category)
                    <a href="javascript:void(0)"
                       data-category="{{ $category->id }}"
                       class="category-filter inline-flex items-center gap-1 px-3 md:px-4 py-2 border-2 rounded-lg text-xs md:text-sm font-medium transition-all whitespace-nowrap
                           {{ request('category') == $category->id ? 'border-primary bg-primary text-white shadow-sm' : 'border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-[#0d1b13] dark:text-white hover:border-primary hover:bg-primary/10' }}">
                        <span class="material-symbols-outlined text-base">category</span>
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Sort Dropdown -->
        <div class="relative w-full md:w-auto">
            <select id="sortSelect" 
                    class="appearance-none w-full md:w-auto px-4 py-2 pr-10 border-2 border-gray-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-900 text-sm font-medium text-[#0d1b13] dark:text-white cursor-pointer hover:border-primary transition-all">
                <option value="popular">Terpopuler</option>
                <option value="cheapest">Termurah</option>
                <option value="expensive">Termahal</option>
                <option value="newest">Terbaru</option>
            </select>
            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-lg">
                expand_more
            </span>
        </div>
    </div>
</div>

<!-- Active Filters -->
<div id="activeFilters" class="mt-4 flex items-center gap-2 flex-wrap" style="{{ request('search') || request('category') ? '' : 'display: none;' }}">
    <span class="text-xs md:text-sm text-gray-600 dark:text-gray-400">Filter Aktif:</span>
    
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
<div id="productsContainer" class="mt-6">
    @include('pembeli.produk.partials.products-grid', ['products' => $products])
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productsContainer = document.getElementById('productsContainer');
    const resultsCount = document.getElementById('results-count');
    const activeFiltersContainer = document.getElementById('activeFilters');
    const categoryFilters = document.querySelectorAll('.category-filter');

    let currentCategory = '{{ request("category", "") }}';
    let currentSearch = '{{ request("search", "") }}';

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
                resultsCount.textContent = data.count;
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
            <div class="bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm">
                <div class="text-center py-12 md:py-16 px-4">
                    <div class="w-12 h-12 md:w-16 md:h-16 border-4 border-primary border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                    <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">Memuat produk...</p>
                </div>
            </div>
        `;
    }

    function showErrorState() {
        productsContainer.innerHTML = `
            <div class="bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm">
                <div class="text-center py-12 md:py-16 px-4">
                    <span class="material-symbols-outlined text-red-500 text-5xl md:text-6xl mb-3">error</span>
                    <p class="text-sm font-medium text-red-600 dark:text-red-400">Gagal memuat produk</p>
                    <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-primary text-white rounded-lg text-xs md:text-sm hover:shadow-lg transition-all">
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
        
        let html = '<span class="text-xs md:text-sm text-gray-600 dark:text-gray-400">Filter Aktif:</span>';

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

    // Category Filters
    categoryFilters.forEach(filter => {
        filter.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Update active state
            categoryFilters.forEach(f => {
                f.classList.remove('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
                f.classList.add('border-gray-300', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
            });
            
            this.classList.remove('border-gray-300', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
            this.classList.add('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
            
            currentCategory = this.dataset.category;
            fetchProducts();
        });
    });

    // ===========================
    // Clear Functions
    // ===========================
    window.clearSearch = function() {
        window.location.href = '{{ route("pembeli.produk.index") }}';
    }

    window.clearCategory = function() {
        currentCategory = '';
        const allFilter = document.querySelector('.category-filter[data-category=""]');
        if (allFilter) allFilter.click();
    }

    window.clearAllFilters = function() {
        currentSearch = '';
        currentCategory = '';
        
        categoryFilters.forEach(f => {
            f.classList.remove('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
            f.classList.add('border-gray-300', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
        });
        
        const allFilter = document.querySelector('.category-filter[data-category=""]');
        if (allFilter) {
            allFilter.classList.remove('border-gray-300', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
            allFilter.classList.add('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
        }
        
        window.location.href = '{{ route("pembeli.produk.index") }}';
    }
});
</script>
@endpush