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
                Menampilkan <span id="results-count">{{ $products->total() }}</span> produk
            </p>
        </div>

        @if(request('search'))
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 rounded-lg text-sm">
                <span class="material-symbols-outlined text-base">search</span>
                <span>Hasil: "<strong>{{ request('search') }}</strong>"</span>
                <a href="{{ route('pembeli.produk.index') }}" class="hover:text-blue-900">
                    <span class="material-symbols-outlined text-base">close</span>
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Layout 2 Kolom: Sidebar Kiri + Konten Kanan -->
<div class="mt-4 flex gap-4 items-start">

    <!-- ============================================================
         SIDEBAR KIRI — Filter
         Sticky di desktop, collapsible di mobile
         ============================================================ -->
    <aside id="filter-sidebar"
           class="hidden lg:block w-64 flex-shrink-0 sticky top-20 self-start space-y-3">

        <!-- Panel Kategori -->
        <div class="bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm p-4">
            <div class="flex items-center gap-2 mb-3">
                <span class="material-symbols-outlined text-primary text-base">category</span>
                <p class="text-xs font-bold text-[#0d1b13] dark:text-white uppercase tracking-wider">Kategori</p>
            </div>

            <!-- Kategori Utama -->
            <div class="space-y-1" id="parentCategoryFilters">
                <button data-parent="" data-parentname="Semua"
                        class="parent-filter w-full flex items-center justify-between gap-2 px-3 py-2 border-2 rounded-lg text-xs font-medium transition-all
                            border-primary bg-primary text-white shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">grid_view</span>
                        <span>Semua</span>
                    </div>
                    <span class="px-1.5 py-0.5 bg-white/20 rounded text-[10px] font-bold">{{ $products->total() }}</span>
                </button>

                @foreach($parentCategories as $parent)
                    <button data-parent="{{ $parent->id }}"
                            data-parentname="{{ $parent->name }}"
                            data-children="{{ $parent->children->toJson() }}"
                            class="parent-filter w-full flex items-center justify-between gap-2 px-3 py-2 border-2 rounded-lg text-xs font-medium transition-all
                                border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-[#0d1b13] dark:text-white hover:border-primary hover:bg-primary/5">
                        <div class="flex items-center gap-2 min-w-0">
                            @if($parent->image)
                                <img src="{{ Storage::url($parent->image) }}" alt="{{ $parent->name }}"
                                     class="w-4 h-4 rounded-full object-cover flex-shrink-0">
                            @else
                                <span class="material-symbols-outlined text-sm text-gray-400">pets</span>
                            @endif
                            <span class="truncate">{{ $parent->name }}</span>
                        </div>
                        @if(isset($categoryCounts[$parent->id]))
                            <span class="px-1.5 py-0.5 bg-gray-100 dark:bg-zinc-700 text-gray-500 dark:text-zinc-400 rounded text-[10px] font-bold flex-shrink-0">
                                {{ $categoryCounts[$parent->id] }}
                            </span>
                        @endif
                    </button>
                @endforeach
            </div>

            <!-- Sub Kategori (muncul setelah pilih parent) -->
            <div id="subCategoryRow" class="hidden mt-3 pt-3 border-t border-gray-100 dark:border-zinc-800">
                <p class="text-[10px] font-semibold text-gray-400 dark:text-zinc-500 uppercase tracking-wider mb-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">subdirectory_arrow_right</span>
                    Sub Kategori
                </p>
                <div class="space-y-1" id="subCategoryFilters">
                    <!-- Diisi lewat JavaScript -->
                </div>
            </div>
        </div>

        <!-- Panel Filter Harga -->
        <div class="bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm p-4">
            <div class="flex items-center gap-2 mb-3">
                <span class="material-symbols-outlined text-primary text-base">payments</span>
                <p class="text-xs font-bold text-[#0d1b13] dark:text-white uppercase tracking-wider">Filter Harga</p>
            </div>

            <div class="space-y-3">
                <!-- Slider Range -->
                <div class="px-1 pt-2 pb-1">
                    <div id="slider-track"
                         class="relative h-1.5 bg-gray-200 dark:bg-zinc-700 rounded-full"
                         style="margin: 10px 0;">

                        <div id="price-range-fill"
                             class="absolute h-full bg-primary rounded-full pointer-events-none"
                             style="left:0%; width:100%;"></div>

                        <div id="thumb-min"
                             class="absolute w-5 h-5 bg-white border-2 border-primary rounded-full shadow-md cursor-grab select-none"
                             style="top:50%; transform:translate(-50%,-50%); left:0%; touch-action:none; z-index:3; user-select:none;">
                        </div>

                        <div id="thumb-max"
                             class="absolute w-5 h-5 bg-white border-2 border-primary rounded-full shadow-md cursor-grab select-none"
                             style="top:50%; transform:translate(-50%,-50%); left:100%; touch-action:none; z-index:3; user-select:none;">
                        </div>

                        <input type="range" id="price-min" min="0" max="10000000" step="50000" value="0" class="sr-only">
                        <input type="range" id="price-max" min="0" max="10000000" step="50000" value="10000000" class="sr-only">
                    </div>
                </div>

                <!-- Label min-max -->
                <div class="flex justify-between text-[11px] text-gray-500 dark:text-zinc-400">
                    <span id="slider-label-min">Rp 0</span>
                    <span id="slider-label-max">Rp 10.000.000</span>
                </div>

                <!-- Input Manual -->
                <div class="flex items-center gap-2">
                    <div class="flex-1 relative">
                        <span class="absolute left-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-500 dark:text-zinc-400 font-medium">Rp</span>
                        <input type="number" id="price-min-input" placeholder="0" min="0"
                               class="w-full pl-6 pr-2 py-1.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-[11px] text-[#0d1b13] dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <span class="text-xs text-gray-400 flex-shrink-0">—</span>
                    <div class="flex-1 relative">
                        <span class="absolute left-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-500 dark:text-zinc-400 font-medium">Rp</span>
                        <input type="number" id="price-max-input" placeholder="10jt" min="0"
                               class="w-full pl-6 pr-2 py-1.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-[11px] text-[#0d1b13] dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                </div>

                <!-- Tombol Terapkan + Reset -->
                <div class="flex gap-2">
                    <button onclick="applyPriceFilter()"
                            class="flex-1 py-2 bg-primary text-white rounded-lg text-xs font-medium hover:bg-primary/90 transition-colors">
                        Terapkan
                    </button>
                    <button onclick="resetPriceFilter()" id="price-reset-btn"
                            class="hidden px-3 py-2 bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-300 rounded-lg text-xs font-medium hover:bg-gray-200 transition-colors">
                        <span class="material-symbols-outlined text-sm">refresh</span>
                    </button>
                </div>

                <!-- Quick Price Chips -->
                <div class="grid grid-cols-2 gap-1.5">
                    <button onclick="setQuickPrice(0, 500000)"
                            class="quick-price px-2 py-1.5 border border-gray-200 dark:border-zinc-700 rounded-lg text-[10px] font-medium text-gray-600 dark:text-zinc-300 hover:border-primary hover:text-primary transition-colors text-center">
                        &lt; 500rb
                    </button>
                    <button onclick="setQuickPrice(500000, 1000000)"
                            class="quick-price px-2 py-1.5 border border-gray-200 dark:border-zinc-700 rounded-lg text-[10px] font-medium text-gray-600 dark:text-zinc-300 hover:border-primary hover:text-primary transition-colors text-center">
                        500rb–1jt
                    </button>
                    <button onclick="setQuickPrice(1000000, 5000000)"
                            class="quick-price px-2 py-1.5 border border-gray-200 dark:border-zinc-700 rounded-lg text-[10px] font-medium text-gray-600 dark:text-zinc-300 hover:border-primary hover:text-primary transition-colors text-center">
                        1jt–5jt
                    </button>
                    <button onclick="setQuickPrice(5000000, 10000000)"
                            class="quick-price px-2 py-1.5 border border-gray-200 dark:border-zinc-700 rounded-lg text-[10px] font-medium text-gray-600 dark:text-zinc-300 hover:border-primary hover:text-primary transition-colors text-center">
                        &gt; 5jt
                    </button>
                </div>
            </div>
        </div>

        <!-- Tombol Reset Semua -->
        <button onclick="clearAllFilters()"
                id="sidebar-reset-all"
                class="hidden w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 rounded-xl border border-red-200 dark:border-red-500/20 text-xs font-medium transition-colors">
            <span class="material-symbols-outlined text-sm">refresh</span>
            Reset Semua Filter
        </button>

    </aside>

    <!-- ============================================================
         KONTEN KANAN — Toolbar + Products Grid
         ============================================================ -->
    <div class="flex-1 min-w-0 space-y-3">

        <!-- Toolbar: Sort + Filter Toggle Mobile + Active Filters -->
        <div class="bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm p-3 md:p-4">

            <div class="flex items-center gap-2 flex-wrap">

                <!-- Tombol Filter Mobile (hanya muncul di < lg) -->
                <button onclick="toggleMobileSidebar()"
                        class="lg:hidden inline-flex items-center gap-1.5 px-3 py-2 border-2 border-gray-200 dark:border-zinc-700 rounded-lg text-xs font-medium text-[#0d1b13] dark:text-white hover:border-primary transition-all">
                    <span class="material-symbols-outlined text-sm">filter_list</span>
                    Filter
                    <span id="mobile-filter-badge" class="hidden px-1.5 py-0.5 bg-primary text-white rounded text-[10px] font-bold">0</span>
                </button>

                <!-- Sort -->
                <div class="relative">
                    <select id="sortSelect"
                            class="appearance-none pl-3 pr-8 py-2 border-2 border-gray-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-900 text-xs font-medium text-[#0d1b13] dark:text-white cursor-pointer hover:border-primary transition-all">
                        <option value="newest">Terbaru</option>
                        <option value="cheapest">Termurah</option>
                        <option value="expensive">Termahal</option>
                        <option value="popular">Terpopuler</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-sm">expand_more</span>
                </div>

                <!-- Spacer -->
                <div class="flex-1"></div>

                <!-- Active Filter Tags -->
                <div id="activeFilterTags" class="flex items-center gap-1.5 flex-wrap"></div>

                <!-- Tombol Reset (muncul jika ada filter aktif) -->
                <button onclick="clearAllFilters()"
                        id="topbar-reset-btn"
                        class="hidden inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 rounded-lg text-xs font-medium transition-colors">
                    <span class="material-symbols-outlined text-sm">refresh</span>
                    Reset
                </button>
            </div>
        </div>

        <!-- Products Grid -->
        <div id="productsContainer">
            @include('pembeli.produk.partials.products-grid', ['products' => $products])
        </div>

    </div>
    <!-- akhir konten kanan -->

</div>
<!-- akhir layout 2 kolom -->

<!-- ============================================================
     MOBILE SIDEBAR DRAWER — muncul dari kiri di layar kecil
     ============================================================ -->
<div id="mobile-sidebar-overlay"
     class="lg:hidden fixed inset-0 bg-black/50 z-40 hidden"
     onclick="toggleMobileSidebar()"></div>

<div id="mobile-sidebar-drawer"
     class="lg:hidden fixed inset-y-0 left-0 w-72 bg-white dark:bg-background-dark z-50 overflow-y-auto shadow-2xl transform -translate-x-full transition-transform duration-300">

    <!-- Drawer Header -->
    <div class="sticky top-0 bg-white dark:bg-background-dark border-b border-gray-100 dark:border-zinc-800 px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">filter_list</span>
            <span class="font-bold text-sm text-[#0d1b13] dark:text-white">Filter Produk</span>
        </div>
        <button onclick="toggleMobileSidebar()" class="text-gray-400 hover:text-gray-600">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>

    <div class="p-4 space-y-4">

        <!-- Kategori Mobile -->
        <div>
            <p class="text-[10px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider mb-2">Kategori</p>
            <div class="space-y-1" id="parentCategoryFiltersMobile">
                <button data-parent="" data-parentname="Semua"
                        class="parent-filter-mobile w-full flex items-center justify-between gap-2 px-3 py-2 border-2 rounded-lg text-xs font-medium transition-all
                            border-primary bg-primary text-white shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">grid_view</span>
                        <span>Semua</span>
                    </div>
                    <span class="px-1.5 py-0.5 bg-white/20 rounded text-[10px] font-bold">{{ $products->total() }}</span>
                </button>

                @foreach($parentCategories as $parent)
                    <button data-parent="{{ $parent->id }}"
                            data-parentname="{{ $parent->name }}"
                            data-children="{{ $parent->children->toJson() }}"
                            class="parent-filter-mobile w-full flex items-center justify-between gap-2 px-3 py-2 border-2 rounded-lg text-xs font-medium transition-all
                                border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-[#0d1b13] dark:text-white hover:border-primary hover:bg-primary/5">
                        <div class="flex items-center gap-2 min-w-0">
                            @if($parent->image)
                                <img src="{{ Storage::url($parent->image) }}" alt="{{ $parent->name }}"
                                     class="w-4 h-4 rounded-full object-cover flex-shrink-0">
                            @else
                                <span class="material-symbols-outlined text-sm text-gray-400">pets</span>
                            @endif
                            <span class="truncate">{{ $parent->name }}</span>
                        </div>
                        @if(isset($categoryCounts[$parent->id]))
                            <span class="px-1.5 py-0.5 bg-gray-100 dark:bg-zinc-700 text-gray-500 dark:text-zinc-400 rounded text-[10px] font-bold flex-shrink-0">
                                {{ $categoryCounts[$parent->id] }}
                            </span>
                        @endif
                    </button>
                @endforeach
            </div>

            <div id="subCategoryRowMobile" class="hidden mt-3 pt-3 border-t border-gray-100 dark:border-zinc-800">
                <p class="text-[10px] font-semibold text-gray-400 dark:text-zinc-500 uppercase tracking-wider mb-2">Sub Kategori</p>
                <div class="space-y-1" id="subCategoryFiltersMobile"></div>
            </div>
        </div>

        <!-- Filter Harga Mobile -->
        <div class="border-t border-gray-100 dark:border-zinc-800 pt-4">
            <p class="text-[10px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider mb-3">Filter Harga</p>
            <div class="grid grid-cols-2 gap-1.5 mb-3">
                <button onclick="setQuickPrice(0, 500000)"
                        class="quick-price-mobile px-2 py-2 border border-gray-200 dark:border-zinc-700 rounded-lg text-[10px] font-medium text-gray-600 dark:text-zinc-300 hover:border-primary hover:text-primary transition-colors text-center">
                    &lt; 500rb
                </button>
                <button onclick="setQuickPrice(500000, 1000000)"
                        class="quick-price-mobile px-2 py-2 border border-gray-200 dark:border-zinc-700 rounded-lg text-[10px] font-medium text-gray-600 dark:text-zinc-300 hover:border-primary hover:text-primary transition-colors text-center">
                    500rb–1jt
                </button>
                <button onclick="setQuickPrice(1000000, 5000000)"
                        class="quick-price-mobile px-2 py-2 border border-gray-200 dark:border-zinc-700 rounded-lg text-[10px] font-medium text-gray-600 dark:text-zinc-300 hover:border-primary hover:text-primary transition-colors text-center">
                    1jt–5jt
                </button>
                <button onclick="setQuickPrice(5000000, 10000000)"
                        class="quick-price-mobile px-2 py-2 border border-gray-200 dark:border-zinc-700 rounded-lg text-[10px] font-medium text-gray-600 dark:text-zinc-300 hover:border-primary hover:text-primary transition-colors text-center">
                    &gt; 5jt
                </button>
            </div>
        </div>

        <!-- Tombol Reset Mobile -->
        <div class="border-t border-gray-100 dark:border-zinc-800 pt-4">
            <button onclick="clearAllFilters()"
                    class="w-full py-2.5 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 rounded-lg text-xs font-medium transition-colors flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">refresh</span>
                Reset Semua Filter
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---------- Elemen ----------
    const productsContainer  = document.getElementById('productsContainer');
    const resultsCount       = document.getElementById('results-count');
    const subCategoryRow     = document.getElementById('subCategoryRow');
    const subCategoryFilters = document.getElementById('subCategoryFilters');
    const activeFilterTags   = document.getElementById('activeFilterTags');
    const topbarResetBtn     = document.getElementById('topbar-reset-btn');
    const sidebarResetAll    = document.getElementById('sidebar-reset-all');
    const mobileBadge        = document.getElementById('mobile-filter-badge');

    // ---------- State ----------
    let currentParent     = '';
    let currentSub        = '';
    let currentSearch     = '{{ request("search", "") }}';
    let currentSort       = 'newest';
    let currentParentName = '';
    let currentSubName    = '';

    const PRICE_MIN = 0;
    const PRICE_MAX = 10000000;
    window.currentMinPrice = 0;
    window.currentMaxPrice = 0;

    // ---------- Slider Elemen ----------
    const track    = document.getElementById('slider-track');
    const thumbMin = document.getElementById('thumb-min');
    const thumbMax = document.getElementById('thumb-max');
    const fill     = document.getElementById('price-range-fill');
    const inputMin = document.getElementById('price-min-input');
    const inputMax = document.getElementById('price-max-input');
    const resetBtn = document.getElementById('price-reset-btn');
    const labelMin = document.getElementById('slider-label-min');
    const labelMax = document.getElementById('slider-label-max');

    let minVal   = PRICE_MIN;
    let maxVal   = PRICE_MAX;
    let dragging = null;

    // ===================== FETCH PRODUCTS =====================
    function fetchProducts() {
        showSkeleton();

        const params = new URLSearchParams();
        if (currentSearch)  params.append('search', currentSearch);
        if (currentParent)  params.append('parent_category', currentParent);
        if (currentSub)     params.append('category', currentSub);
        if (currentSort)    params.append('sort', currentSort);
        if (window.currentMinPrice > 0)
            params.append('min_price', window.currentMinPrice);
        if (window.currentMaxPrice > 0 && window.currentMaxPrice < PRICE_MAX)
            params.append('max_price', window.currentMaxPrice);
        params.append('ajax', '1');

        fetch(`{{ route('pembeli.produk.index') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.html !== undefined)  productsContainer.innerHTML = data.html;
            if (data.total !== undefined) resultsCount.textContent = data.total;
            updateActiveFilterTags();
            updateResetButtons();
            updateURL();
        })
        .catch(() => showError());
    }

    // ===================== SKELETON =====================
    function showSkeleton() {
        let cards = '';
        for (let i = 0; i < 8; i++) {
            cards += `
                <div class="animate-pulse">
                    <div class="aspect-square rounded-xl bg-gray-200 dark:bg-zinc-700 mb-3"></div>
                    <div class="h-4 bg-gray-200 dark:bg-zinc-700 rounded mb-2 w-3/4"></div>
                    <div class="h-4 bg-gray-200 dark:bg-zinc-700 rounded mb-2 w-1/2"></div>
                    <div class="h-3 bg-gray-200 dark:bg-zinc-700 rounded mb-3 w-1/3"></div>
                    <div class="h-9 bg-gray-200 dark:bg-zinc-700 rounded-lg"></div>
                </div>`;
        }
        productsContainer.innerHTML = `
            <div class="bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm p-4 md:p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">${cards}</div>
            </div>`;
    }

    function showError() {
        productsContainer.innerHTML = `
            <div class="bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm">
                <div class="text-center py-16 px-4">
                    <span class="material-symbols-outlined text-red-500 text-5xl mb-3">error</span>
                    <p class="text-sm text-red-500">Gagal memuat produk</p>
                    <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-primary text-white rounded-lg text-sm">Muat Ulang</button>
                </div>
            </div>`;
    }

    // ===================== UPDATE URL =====================
    function updateURL() {
        const params = new URLSearchParams();
        if (currentSearch) params.append('search', currentSearch);
        if (currentParent) params.append('parent_category', currentParent);
        if (currentSub)    params.append('category', currentSub);
        const newURL = params.toString() ? `?${params.toString()}` : window.location.pathname;
        window.history.pushState({}, '', newURL);
    }

    // ===================== ACTIVE FILTER TAGS =====================
    function updateActiveFilterTags() {
        const priceActive = window.currentMinPrice > 0 ||
                            (window.currentMaxPrice > 0 && window.currentMaxPrice < PRICE_MAX);

        let html = '';

        if (currentParent) {
            html += `
                <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 rounded-lg text-[10px] font-medium">
                    <span class="material-symbols-outlined text-xs">category</span>
                    ${currentParentName}
                    <button onclick="clearParent()" class="hover:text-blue-900 ml-0.5">
                        <span class="material-symbols-outlined text-xs">close</span>
                    </button>
                </span>`;
        }

        if (currentSub) {
            html += `
                <span class="inline-flex items-center gap-1 px-2 py-1 bg-purple-50 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400 rounded-lg text-[10px] font-medium">
                    <span class="material-symbols-outlined text-xs">account_tree</span>
                    ${currentSubName}
                    <button onclick="clearSub()" class="hover:text-purple-900 ml-0.5">
                        <span class="material-symbols-outlined text-xs">close</span>
                    </button>
                </span>`;
        }

        if (currentSearch) {
            html += `
                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-300 rounded-lg text-[10px] font-medium">
                    <span class="material-symbols-outlined text-xs">search</span>
                    "${currentSearch}"
                    <button onclick="clearSearch()" class="hover:text-gray-900 ml-0.5">
                        <span class="material-symbols-outlined text-xs">close</span>
                    </button>
                </span>`;
        }

        if (priceActive) {
            const minLabel = window.currentMinPrice > 0
                ? 'Rp ' + window.currentMinPrice.toLocaleString('id-ID') : '0';
            const maxLabel = window.currentMaxPrice < PRICE_MAX
                ? 'Rp ' + window.currentMaxPrice.toLocaleString('id-ID') : 'Rp 10jt+';
            html += `
                <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 rounded-lg text-[10px] font-medium">
                    <span class="material-symbols-outlined text-xs">payments</span>
                    ${minLabel} — ${maxLabel}
                    <button onclick="resetPriceFilter()" class="hover:text-green-900 ml-0.5">
                        <span class="material-symbols-outlined text-xs">close</span>
                    </button>
                </span>`;
        }

        activeFilterTags.innerHTML = html;
    }

    function updateResetButtons() {
        const priceActive = window.currentMinPrice > 0 ||
                            (window.currentMaxPrice > 0 && window.currentMaxPrice < PRICE_MAX);
        const hasFilter = currentSearch || currentParent || currentSub || priceActive;

        // Hitung jumlah filter aktif untuk badge mobile
        let count = 0;
        if (currentParent) count++;
        if (currentSub) count++;
        if (priceActive) count++;
        if (currentSearch) count++;

        if (topbarResetBtn) topbarResetBtn.classList.toggle('hidden', !hasFilter);
        if (sidebarResetAll) sidebarResetAll.classList.toggle('hidden', !hasFilter);
        if (mobileBadge) {
            mobileBadge.textContent = count;
            mobileBadge.classList.toggle('hidden', count === 0);
        }
    }

    // ===================== POPULATE SUB KATEGORI =====================
    function populateSubCategories(children, containerEl, rowEl) {
        if (!children || children.length === 0) {
            rowEl.classList.add('hidden');
            return;
        }

        rowEl.classList.remove('hidden');

        let html = `
            <button data-sub="" data-subname=""
                    class="sub-filter w-full flex items-center gap-2 px-3 py-2 border-2 rounded-lg text-xs font-medium transition-all
                        border-primary bg-primary text-white shadow-sm">
                <span class="material-symbols-outlined text-sm">subdirectory_arrow_right</span>
                Semua ${currentParentName}
            </button>`;

        children.forEach(child => {
            html += `
                <button data-sub="${child.id}" data-subname="${child.name}"
                        class="sub-filter w-full flex items-center gap-2 px-3 py-2 border-2 rounded-lg text-xs font-medium transition-all
                            border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-[#0d1b13] dark:text-white hover:border-primary hover:bg-primary/5">
                    <span class="material-symbols-outlined text-sm">arrow_right</span>
                    ${child.name}
                </button>`;
        });

        containerEl.innerHTML = html;

        containerEl.querySelectorAll('.sub-filter').forEach(btn => {
            btn.addEventListener('click', function () {
                // Sync kedua sidebar
                document.querySelectorAll('.sub-filter').forEach(b => {
                    b.classList.remove('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
                    b.classList.add('border-gray-200', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
                });
                this.classList.remove('border-gray-200', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
                this.classList.add('border-primary', 'bg-primary', 'text-white', 'shadow-sm');

                currentSub     = this.dataset.sub;
                currentSubName = this.dataset.subname || '';
                fetchProducts();
            });
        });
    }

    // ===================== PARENT CATEGORY CLICK (helper) =====================
    function handleParentClick(btn, allBtns) {
        allBtns.forEach(b => {
            b.classList.remove('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
            b.classList.add('border-gray-200', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
            const badge = b.querySelector('span.bg-white\\/20');
            if (badge) {
                badge.classList.remove('bg-white/20');
                badge.classList.add('bg-gray-100', 'dark:bg-zinc-700', 'text-gray-500', 'dark:text-zinc-400');
            }
        });

        btn.classList.remove('border-gray-200', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
        btn.classList.add('border-primary', 'bg-primary', 'text-white', 'shadow-sm');

        currentParent     = btn.dataset.parent;
        currentParentName = btn.dataset.parentname || btn.textContent.trim();
        currentSub        = '';
        currentSubName    = '';
    }

    // ===================== DESKTOP PARENT FILTER =====================
    document.querySelectorAll('#parentCategoryFilters .parent-filter').forEach(btn => {
        btn.addEventListener('click', function () {
            const all = document.querySelectorAll('#parentCategoryFilters .parent-filter');
            handleParentClick(this, all);

            if (currentParent) {
                const children = this.dataset.children ? JSON.parse(this.dataset.children) : [];
                populateSubCategories(children, subCategoryFilters, subCategoryRow);
            } else {
                subCategoryRow.classList.add('hidden');
            }

            fetchProducts();
        });
    });

    // ===================== MOBILE PARENT FILTER =====================
    document.querySelectorAll('#parentCategoryFiltersMobile .parent-filter-mobile').forEach(btn => {
        btn.addEventListener('click', function () {
            const all = document.querySelectorAll('#parentCategoryFiltersMobile .parent-filter-mobile');
            handleParentClick(this, all);

            const subRowMobile  = document.getElementById('subCategoryRowMobile');
            const subContMobile = document.getElementById('subCategoryFiltersMobile');

            if (currentParent) {
                const children = this.dataset.children ? JSON.parse(this.dataset.children) : [];
                populateSubCategories(children, subContMobile, subRowMobile);
                // Sync desktop juga
                populateSubCategories(children, subCategoryFilters, subCategoryRow);
            } else {
                subRowMobile.classList.add('hidden');
                subCategoryRow.classList.add('hidden');
            }

            fetchProducts();
        });
    });

    // ===================== SORT =====================
    document.getElementById('sortSelect').addEventListener('change', function () {
        currentSort = this.value;
        fetchProducts();
    });

    // ===================== SLIDER =====================
    function formatRupiah(val) {
        return 'Rp ' + parseInt(val).toLocaleString('id-ID');
    }

    function pctFromVal(val) {
        return ((val - PRICE_MIN) / (PRICE_MAX - PRICE_MIN)) * 100;
    }

    function valFromPct(pct) {
        let raw = PRICE_MIN + (pct / 100) * (PRICE_MAX - PRICE_MIN);
        raw = Math.round(raw / 50000) * 50000;
        return Math.max(PRICE_MIN, Math.min(PRICE_MAX, raw));
    }

    function getPctFromEvent(e) {
        const rect    = track.getBoundingClientRect();
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const pct     = ((clientX - rect.left) / rect.width) * 100;
        return Math.max(0, Math.min(100, pct));
    }

    function updateSliderUI() {
        const minPct = pctFromVal(minVal);
        const maxPct = pctFromVal(maxVal);

        thumbMin.style.left = `${minPct}%`;
        thumbMax.style.left = `${maxPct}%`;
        fill.style.left     = `${minPct}%`;
        fill.style.width    = `${maxPct - minPct}%`;

        if (labelMin) labelMin.textContent = formatRupiah(minVal);
        if (labelMax) labelMax.textContent = formatRupiah(maxVal);
    }

    function onDragStart(e, type) {
        e.preventDefault();
        dragging = type;
        thumbMin.style.zIndex = type === 'min' ? '5' : '3';
        thumbMax.style.zIndex = type === 'max' ? '5' : '3';
        document.addEventListener('mousemove', onDragMove);
        document.addEventListener('mouseup',   onDragEnd);
        document.addEventListener('touchmove', onDragMove, { passive: false });
        document.addEventListener('touchend',  onDragEnd);
    }

    thumbMin.addEventListener('mousedown',  e => onDragStart(e, 'min'));
    thumbMin.addEventListener('touchstart', e => onDragStart(e, 'min'), { passive: false });
    thumbMax.addEventListener('mousedown',  e => onDragStart(e, 'max'));
    thumbMax.addEventListener('touchstart', e => onDragStart(e, 'max'), { passive: false });

    function onDragMove(e) {
        if (!dragging) return;
        if (e.cancelable) e.preventDefault();

        const pct = getPctFromEvent(e);
        const val = valFromPct(pct);

        if (dragging === 'min') {
            minVal = Math.min(val, maxVal - 50000);
            if (inputMin) inputMin.value = minVal;
        } else {
            maxVal = Math.max(val, minVal + 50000);
            if (inputMax) inputMax.value = maxVal;
        }

        updateSliderUI();
    }

    function onDragEnd() {
        dragging = null;
        document.removeEventListener('mousemove', onDragMove);
        document.removeEventListener('mouseup',   onDragEnd);
        document.removeEventListener('touchmove', onDragMove);
        document.removeEventListener('touchend',  onDragEnd);
        thumbMin.style.zIndex = '3';
        thumbMax.style.zIndex = '3';
    }

    track.addEventListener('click', function(e) {
        if (e.target === thumbMin || e.target === thumbMax) return;

        const pct       = getPctFromEvent(e);
        const val       = valFromPct(pct);
        const dMin      = Math.abs(val - minVal);
        const dMax      = Math.abs(val - maxVal);

        if (dMin <= dMax) {
            minVal = Math.min(val, maxVal - 50000);
            if (inputMin) inputMin.value = minVal;
        } else {
            maxVal = Math.max(val, minVal + 50000);
            if (inputMax) inputMax.value = maxVal;
        }

        updateSliderUI();
    });

    if (inputMin) {
        inputMin.addEventListener('input', function () {
            let val = parseInt(this.value) || 0;
            minVal  = Math.max(PRICE_MIN, Math.min(val, maxVal - 50000));
            updateSliderUI();
        });
    }

    if (inputMax) {
        inputMax.addEventListener('input', function () {
            let val = parseInt(this.value) || PRICE_MAX;
            maxVal  = Math.min(PRICE_MAX, Math.max(val, minVal + 50000));
            updateSliderUI();
        });
    }

    // ===================== PRICE FUNCTIONS (global) =====================
    window.applyPriceFilter = function () {
        window.currentMinPrice = minVal;
        window.currentMaxPrice = maxVal;
        if (resetBtn) resetBtn.classList.remove('hidden');
        highlightQuickPrice(minVal, maxVal);
        fetchProducts();
    };

    window.resetPriceFilter = function () {
        minVal = PRICE_MIN;
        maxVal = PRICE_MAX;
        window.currentMinPrice = 0;
        window.currentMaxPrice = 0;
        if (inputMin) inputMin.value = '';
        if (inputMax) inputMax.value = '';
        if (resetBtn) resetBtn.classList.add('hidden');
        document.querySelectorAll('.quick-price, .quick-price-mobile').forEach(b => {
            b.classList.remove('border-primary', 'text-primary', 'bg-primary/5');
        });
        updateSliderUI();
        fetchProducts();
    };

    window.setQuickPrice = function (min, max) {
        minVal = min;
        maxVal = max;
        if (inputMin) inputMin.value = min;
        if (inputMax) inputMax.value = max;
        updateSliderUI();
        window.applyPriceFilter();
    };

    function highlightQuickPrice(min, max) {
        const ranges = [
            { min: 0,       max: 500000   },
            { min: 500000,  max: 1000000  },
            { min: 1000000, max: 5000000  },
            { min: 5000000, max: 10000000 },
        ];
        document.querySelectorAll('.quick-price, .quick-price-mobile').forEach((btn, i) => {
            btn.classList.remove('border-primary', 'text-primary', 'bg-primary/5');
            if (ranges[i] && ranges[i].min === min && ranges[i].max === max) {
                btn.classList.add('border-primary', 'text-primary', 'bg-primary/5');
            }
        });
    }

    // ===================== CLEAR FUNCTIONS (global) =====================
    window.clearParent = function () {
        currentParent = ''; currentParentName = '';
        currentSub = '';    currentSubName = '';
        subCategoryRow.classList.add('hidden');

        const resetActiveStyle = (selector) => {
            document.querySelectorAll(selector).forEach(b => {
                b.classList.remove('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
                b.classList.add('border-gray-200', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
            });
            const allBtn = document.querySelector(selector + '[data-parent=""]');
            if (allBtn) {
                allBtn.classList.add('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
                allBtn.classList.remove('border-gray-200', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
            }
        };

        resetActiveStyle('#parentCategoryFilters .parent-filter');
        resetActiveStyle('#parentCategoryFiltersMobile .parent-filter-mobile');
        fetchProducts();
    };

    window.clearSub = function () {
        currentSub = ''; currentSubName = '';
        document.querySelectorAll('.sub-filter').forEach(b => {
            b.classList.remove('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
            b.classList.add('border-gray-200', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
        });
        const allSubBtn = document.querySelector('.sub-filter[data-sub=""]');
        if (allSubBtn) {
            allSubBtn.classList.add('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
            allSubBtn.classList.remove('border-gray-200', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
        }
        fetchProducts();
    };

    window.clearSearch = function () {
        currentSearch = '';
        window.location.href = '{{ route("pembeli.produk.index") }}';
    };

    window.clearAllFilters = function () {
        window.location.href = '{{ route("pembeli.produk.index") }}';
    };

    // ===================== MOBILE SIDEBAR =====================
    window.toggleMobileSidebar = function () {
        const overlay = document.getElementById('mobile-sidebar-overlay');
        const drawer  = document.getElementById('mobile-sidebar-drawer');
        const isOpen  = !drawer.classList.contains('-translate-x-full');

        if (isOpen) {
            drawer.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        } else {
            drawer.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    };

    // ===================== INIT =====================
    updateSliderUI();
    updateResetButtons();

});
</script>
@endpush