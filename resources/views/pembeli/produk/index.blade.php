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

<!-- Filter Bar -->
<div class="mt-4 bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm p-4 space-y-3">

    <!-- Baris 1: Kategori Utama -->
    <div>
        <p class="text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider mb-2">Kategori</p>
        <div class="flex items-center gap-2 flex-wrap" id="parentCategoryFilters">
            <button data-parent="" data-parentname="Semua"
                    class="parent-filter inline-flex items-center gap-1.5 px-3 py-1.5 border-2 rounded-lg text-xs font-medium transition-all
                        border-primary bg-primary text-white shadow-sm">
                <span class="material-symbols-outlined text-sm">grid_view</span>
                Semua
                <span class="ml-1 px-1.5 py-0.5 bg-white/20 rounded text-[10px] font-bold">{{ $products->total() }}</span>
            </button>
            @foreach($parentCategories as $parent)
                <button data-parent="{{ $parent->id }}"
                        data-parentname="{{ $parent->name }}"
                        data-children="{{ $parent->children->toJson() }}"
                        class="parent-filter inline-flex items-center gap-1.5 px-3 py-1.5 border-2 rounded-lg text-xs font-medium transition-all
                            border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-[#0d1b13] dark:text-white hover:border-primary hover:bg-primary/10">
                    @if($parent->image)
                        <img src="{{ Storage::url($parent->image) }}" alt="{{ $parent->name }}"
                             class="w-4 h-4 rounded-full object-cover flex-shrink-0">
                    @endif
                    {{ $parent->name }}
                    {{-- Tampilkan jumlah produk per kategori --}}
                    @if(isset($categoryCounts[$parent->id]))
                        <span class="ml-1 px-1.5 py-0.5 bg-gray-100 dark:bg-zinc-700 text-gray-500 dark:text-zinc-400 rounded text-[10px] font-bold">
                            {{ $categoryCounts[$parent->id] }}
                        </span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    <!-- Baris 2: Sub Kategori (muncul setelah pilih parent) -->
    <div id="subCategoryRow" class="hidden">
        <p class="text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider mb-2">Sub Kategori</p>
        <div class="flex items-center gap-2 flex-wrap" id="subCategoryFilters">
            <!-- Diisi lewat JavaScript -->
        </div>
    </div>

    <!-- Baris 3: Sort saja -->
    <div class="flex justify-end pt-1 border-t border-gray-100 dark:border-zinc-800">
        <div class="relative w-full sm:w-auto">
            <select id="sortSelect"
                    class="appearance-none w-full sm:w-48 px-4 py-2.5 pr-10 border-2 border-gray-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-900 text-sm font-medium text-[#0d1b13] dark:text-white cursor-pointer hover:border-primary transition-all">
                <option value="newest">Terbaru</option>
                <option value="cheapest">Termurah</option>
                <option value="expensive">Termahal</option>
                <option value="popular">Terpopuler</option>
            </select>
            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">expand_more</span>
        </div>
    </div>

    <!-- Baris 4: Filter Harga -->
    <div class="pt-1 border-t border-gray-100 dark:border-zinc-800">
        <p class="text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider mb-3">Filter Harga</p>
        <div class="space-y-3">

            <!-- =====================================================================
                 SLIDER RANGE — Custom Drag (fix slider kiri tidak bisa digeser)
                 Pendekatan: thumb adalah div biasa yang di-drag manual via
                 mousemove/touchmove. Tidak ada dua input bertumpuk yang berebut klik.
                 ===================================================================== -->
            <div class="px-1 pt-2 pb-1">
                <div id="slider-track"
                     class="relative h-1.5 bg-gray-200 dark:bg-zinc-700 rounded-full"
                     style="margin: 10px 0;">

                    <!-- Fill bar aktif -->
                    <div id="price-range-fill"
                         class="absolute h-full bg-primary rounded-full pointer-events-none"
                         style="left:0%; width:100%;"></div>

                    <!-- Thumb Min (kiri) -->
                    <div id="thumb-min"
                         class="absolute w-5 h-5 bg-white border-2 border-primary rounded-full shadow-md cursor-grab select-none"
                         style="top:50%; transform:translate(-50%,-50%); left:0%; touch-action:none; z-index:3; user-select:none;">
                    </div>

                    <!-- Thumb Max (kanan) -->
                    <div id="thumb-max"
                         class="absolute w-5 h-5 bg-white border-2 border-primary rounded-full shadow-md cursor-grab select-none"
                         style="top:50%; transform:translate(-50%,-50%); left:100%; touch-action:none; z-index:3; user-select:none;">
                    </div>

                    <!-- Hidden inputs — hanya menyimpan nilai, tidak sebagai UI -->
                    <input type="range" id="price-min" min="0" max="10000000" step="50000" value="0" class="sr-only">
                    <input type="range" id="price-max" min="0" max="10000000" step="50000" value="10000000" class="sr-only">
                </div>
            </div>

            <!-- Label harga min-max realtime -->
            <div class="flex justify-between text-[11px] text-gray-500 dark:text-zinc-400 px-1">
                <span id="slider-label-min">Rp 0</span>
                <span id="slider-label-max">Rp 10.000.000</span>
            </div>

            <!-- Input Harga Manual -->
            <div class="flex items-center gap-3">
                <div class="flex-1 relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-500 dark:text-zinc-400 font-medium">Rp</span>
                    <input type="number" id="price-min-input" placeholder="0" min="0"
                           class="w-full pl-8 pr-3 py-2 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-xs text-[#0d1b13] dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>
                <span class="text-xs text-gray-400">—</span>
                <div class="flex-1 relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-500 dark:text-zinc-400 font-medium">Rp</span>
                    <input type="number" id="price-max-input" placeholder="10.000.000" min="0"
                           class="w-full pl-8 pr-3 py-2 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-xs text-[#0d1b13] dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>
                <button onclick="applyPriceFilter()"
                        class="px-4 py-2 bg-primary text-white rounded-lg text-xs font-medium hover:bg-primary/90 transition-colors whitespace-nowrap">
                    Terapkan
                </button>
                <button onclick="resetPriceFilter()" id="price-reset-btn"
                        class="hidden px-3 py-2 bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-300 rounded-lg text-xs font-medium hover:bg-gray-200 transition-colors">
                    Reset
                </button>
            </div>

            <!-- Quick Price Buttons -->
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-xs text-gray-500 dark:text-zinc-400">Cepat:</span>
                <button onclick="setQuickPrice(0, 500000)"
                        class="quick-price px-2.5 py-1 border border-gray-200 dark:border-zinc-700 rounded-lg text-xs text-gray-600 dark:text-zinc-300 hover:border-primary hover:text-primary transition-colors">
                    &lt; Rp 500rb
                </button>
                <button onclick="setQuickPrice(500000, 1000000)"
                        class="quick-price px-2.5 py-1 border border-gray-200 dark:border-zinc-700 rounded-lg text-xs text-gray-600 dark:text-zinc-300 hover:border-primary hover:text-primary transition-colors">
                    Rp 500rb - 1jt
                </button>
                <button onclick="setQuickPrice(1000000, 5000000)"
                        class="quick-price px-2.5 py-1 border border-gray-200 dark:border-zinc-700 rounded-lg text-xs text-gray-600 dark:text-zinc-300 hover:border-primary hover:text-primary transition-colors">
                    Rp 1jt - 5jt
                </button>
                <button onclick="setQuickPrice(5000000, 10000000)"
                        class="quick-price px-2.5 py-1 border border-gray-200 dark:border-zinc-700 rounded-lg text-xs text-gray-600 dark:text-zinc-300 hover:border-primary hover:text-primary transition-colors">
                    &gt; Rp 5jt
                </button>
            </div>

        </div>
    </div>

    <!-- Active Filters -->
    <div id="activeFilters" class="hidden flex items-center gap-2 flex-wrap pt-1 border-t border-gray-100 dark:border-zinc-800">
        <span class="text-xs text-gray-500 dark:text-zinc-400">Filter aktif:</span>
        <div id="activeFilterTags" class="flex items-center gap-2 flex-wrap"></div>
        <button onclick="clearAllFilters()"
                class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 rounded-lg text-xs font-medium transition-colors">
            <span class="material-symbols-outlined text-sm">refresh</span>
            Reset Semua
        </button>
    </div>

</div>

<!-- Products Grid -->
<div id="productsContainer" class="mt-6">
    @include('pembeli.produk.partials.products-grid', ['products' => $products])
</div>

@endsection

@push('scripts')
<script>
// =====================================================================
// SATU DOMContentLoaded — semua logika filter di sini
// =====================================================================
document.addEventListener('DOMContentLoaded', function () {

    // ---------- Elemen ----------
    const productsContainer  = document.getElementById('productsContainer');
    const resultsCount       = document.getElementById('results-count');
    const subCategoryRow     = document.getElementById('subCategoryRow');
    const subCategoryFilters = document.getElementById('subCategoryFilters');
    const activeFilters      = document.getElementById('activeFilters');
    const activeFilterTags   = document.getElementById('activeFilterTags');

    // ---------- State kategori & sort ----------
    let currentParent     = '';
    let currentSub        = '';
    let currentSearch     = '{{ request("search", "") }}';
    let currentSort       = 'newest';
    let currentParentName = '';
    let currentSubName    = '';

    // ---------- Konstanta harga ----------
    const PRICE_MIN = 0;
    const PRICE_MAX = 10000000;
    window.currentMinPrice = 0;
    window.currentMaxPrice = 0;

    // ---------- Elemen slider ----------
    const track    = document.getElementById('slider-track');
    const thumbMin = document.getElementById('thumb-min');
    const thumbMax = document.getElementById('thumb-max');
    const fill     = document.getElementById('price-range-fill');
    const sliderMin = document.getElementById('price-min'); // hidden input
    const sliderMax = document.getElementById('price-max'); // hidden input
    const inputMin  = document.getElementById('price-min-input');
    const inputMax  = document.getElementById('price-max-input');
    const resetBtn  = document.getElementById('price-reset-btn');
    const labelMin  = document.getElementById('slider-label-min');
    const labelMax  = document.getElementById('slider-label-max');

    // ---------- State nilai slider ----------
    let minVal   = PRICE_MIN;
    let maxVal   = PRICE_MAX;
    let dragging = null; // 'min' atau 'max'

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
            updateURL();
        })
        .catch(() => showError());
    }

    // ===================== SKELETON LOADING =====================
    function showSkeleton() {
        let skeletonCards = '';
        for (let i = 0; i < 8; i++) {
            skeletonCards += `
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
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    ${skeletonCards}
                </div>
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
        const hasFilter   = currentSearch || currentParent || currentSub || priceActive;

        activeFilters.classList.toggle('hidden', !hasFilter);
        activeFilters.classList.toggle('flex',    hasFilter);

        let html = '';

        if (currentParent) {
            html += `
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 rounded-lg text-xs">
                    <span class="material-symbols-outlined text-sm">category</span>
                    ${currentParentName}
                    <button onclick="clearParent()" class="hover:text-blue-900">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </span>`;
        }

        if (currentSub) {
            html += `
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-purple-50 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400 rounded-lg text-xs">
                    <span class="material-symbols-outlined text-sm">account_tree</span>
                    ${currentSubName}
                    <button onclick="clearSub()" class="hover:text-purple-900">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </span>`;
        }

        if (currentSearch) {
            html += `
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-300 rounded-lg text-xs">
                    <span class="material-symbols-outlined text-sm">search</span>
                    "${currentSearch}"
                    <button onclick="clearSearch()" class="hover:text-gray-900">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </span>`;
        }

        if (priceActive) {
            const minLabel = window.currentMinPrice > 0
                ? 'Rp ' + window.currentMinPrice.toLocaleString('id-ID') : '0';
            const maxLabel = window.currentMaxPrice < PRICE_MAX
                ? 'Rp ' + window.currentMaxPrice.toLocaleString('id-ID') : 'Rp 10jt+';
            html += `
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 rounded-lg text-xs">
                    <span class="material-symbols-outlined text-sm">payments</span>
                    ${minLabel} — ${maxLabel}
                    <button onclick="resetPriceFilter()" class="hover:text-green-900">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </span>`;
        }

        activeFilterTags.innerHTML = html;
    }

    // ===================== POPULATE SUB KATEGORI =====================
    function populateSubCategories(children) {
        if (!children || children.length === 0) {
            subCategoryRow.classList.add('hidden');
            return;
        }

        subCategoryRow.classList.remove('hidden');

        let html = `
            <button data-sub="" data-subname=""
                    class="sub-filter inline-flex items-center gap-1 px-3 py-1.5 border-2 rounded-lg text-xs font-medium transition-all
                        border-primary bg-primary text-white shadow-sm">
                Semua ${currentParentName}
            </button>`;

        children.forEach(child => {
            html += `
                <button data-sub="${child.id}" data-subname="${child.name}"
                        class="sub-filter inline-flex items-center gap-1 px-3 py-1.5 border-2 rounded-lg text-xs font-medium transition-all
                            border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-[#0d1b13] dark:text-white hover:border-primary hover:bg-primary/10">
                    <span class="material-symbols-outlined text-sm">subdirectory_arrow_right</span>
                    ${child.name}
                </button>`;
        });

        subCategoryFilters.innerHTML = html;

        // Pasang event listener setelah HTML dirender
        document.querySelectorAll('.sub-filter').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.sub-filter').forEach(b => {
                    b.classList.remove('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
                    b.classList.add('border-gray-300', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
                });
                this.classList.remove('border-gray-300', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
                this.classList.add('border-primary', 'bg-primary', 'text-white', 'shadow-sm');

                currentSub     = this.dataset.sub;
                currentSubName = this.dataset.subname || '';
                fetchProducts();
            });
        });
    }

    // ===================== PARENT CATEGORY CLICK =====================
    document.querySelectorAll('.parent-filter').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.parent-filter').forEach(b => {
                b.classList.remove('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
                b.classList.add('border-gray-300', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
                // Kembalikan badge count ke warna normal
                const badge = b.querySelector('span.ml-1');
                if (badge) {
                    badge.classList.remove('bg-white/20');
                    badge.classList.add('bg-gray-100', 'dark:bg-zinc-700', 'text-gray-500', 'dark:text-zinc-400');
                }
            });
            this.classList.remove('border-gray-300', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
            this.classList.add('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
            // Badge aktif warna kontras
            const activeBadge = this.querySelector('span.ml-1');
            if (activeBadge) {
                activeBadge.classList.add('bg-white/20');
                activeBadge.classList.remove('bg-gray-100', 'dark:bg-zinc-700', 'text-gray-500', 'dark:text-zinc-400');
            }

            currentParent     = this.dataset.parent;
            currentParentName = this.dataset.parentname || this.textContent.trim();
            currentSub        = '';
            currentSubName    = '';

            if (currentParent) {
                const children = this.dataset.children ? JSON.parse(this.dataset.children) : [];
                populateSubCategories(children);
            } else {
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

    // =====================================================================
    // SLIDER CUSTOM DRAG — FIX FINAL
    // Tidak pakai dua input range bertumpuk yang berebut klik.
    // Thumb adalah div biasa yang di-drag manual via mousemove/touchmove.
    // Klik langsung di track juga bisa — otomatis gerak ke thumb terdekat.
    // =====================================================================

    function formatRupiah(val) {
        return 'Rp ' + parseInt(val).toLocaleString('id-ID');
    }

    function pctFromVal(val) {
        return ((val - PRICE_MIN) / (PRICE_MAX - PRICE_MIN)) * 100;
    }

    function valFromPct(pct) {
        // Snap ke step 50000
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

        // Sync ke hidden input (opsional, untuk konsistensi)
        sliderMin.value = minVal;
        sliderMax.value = maxVal;

        // Update label realtime
        if (labelMin) labelMin.textContent = formatRupiah(minVal);
        if (labelMax) labelMax.textContent = formatRupiah(maxVal);
    }

    // ---- Drag Start ----
    function onDragStart(e, type) {
        e.preventDefault();
        dragging = type;
        // Naikkan z-index thumb yang sedang aktif
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

    // ---- Drag Move ----
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

    // ---- Drag End ----
    function onDragEnd() {
        dragging = null;
        document.removeEventListener('mousemove', onDragMove);
        document.removeEventListener('mouseup',   onDragEnd);
        document.removeEventListener('touchmove', onDragMove);
        document.removeEventListener('touchend',  onDragEnd);
        // Reset z-index ke semula
        thumbMin.style.zIndex = '3';
        thumbMax.style.zIndex = '3';
    }

    // ---- Klik langsung di track (bukan di thumb) ----
    track.addEventListener('click', function(e) {
        // Kalau yang diklik adalah thumb, skip
        if (e.target === thumbMin || e.target === thumbMax) return;

        const pct        = getPctFromEvent(e);
        const val        = valFromPct(pct);
        const distToMin  = Math.abs(val - minVal);
        const distToMax  = Math.abs(val - maxVal);

        // Gerakkan thumb yang lebih dekat ke posisi klik
        if (distToMin <= distToMax) {
            minVal = Math.min(val, maxVal - 50000);
            if (inputMin) inputMin.value = minVal;
        } else {
            maxVal = Math.max(val, minVal + 50000);
            if (inputMax) inputMax.value = maxVal;
        }

        updateSliderUI();
    });

    // ---- Input manual (ketik langsung di kotak Rp) ----
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

    // ===================== PRICE FILTER FUNCTIONS (global) =====================
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
        document.querySelectorAll('.quick-price').forEach(b => {
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
        document.querySelectorAll('.quick-price').forEach((btn, i) => {
            btn.classList.remove('border-primary', 'text-primary', 'bg-primary/5');
            if (ranges[i] && ranges[i].min === min && ranges[i].max === max) {
                btn.classList.add('border-primary', 'text-primary', 'bg-primary/5');
            }
        });
    }

    // ===================== CLEAR FUNCTIONS (global) =====================
    window.clearParent = function () {
        currentParent     = '';
        currentParentName = '';
        currentSub        = '';
        currentSubName    = '';
        subCategoryRow.classList.add('hidden');

        document.querySelectorAll('.parent-filter').forEach(b => {
            b.classList.remove('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
            b.classList.add('border-gray-300', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
        });
        const allBtn = document.querySelector('.parent-filter[data-parent=""]');
        if (allBtn) {
            allBtn.classList.add('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
            allBtn.classList.remove('border-gray-300', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
        }
        fetchProducts();
    };

    window.clearSub = function () {
        currentSub     = '';
        currentSubName = '';
        document.querySelectorAll('.sub-filter').forEach(b => {
            b.classList.remove('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
            b.classList.add('border-gray-300', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
        });
        const allSubBtn = document.querySelector('.sub-filter[data-sub=""]');
        if (allSubBtn) {
            allSubBtn.classList.add('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
            allSubBtn.classList.remove('border-gray-300', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
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

    // ===================== INIT =====================
    updateSliderUI();

}); // ← satu DOMContentLoaded selesai di sini
</script>
@endpush