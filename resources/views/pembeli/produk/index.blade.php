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
            <button data-parent=""
                    class="parent-filter inline-flex items-center gap-1.5 px-3 py-1.5 border-2 rounded-lg text-xs font-medium transition-all
                        border-primary bg-primary text-white shadow-sm">
                <span class="material-symbols-outlined text-sm">grid_view</span>
                Semua
            </button>
            @foreach($parentCategories as $parent)
                <button data-parent="{{ $parent->id }}"
                        data-children="{{ $parent->children->toJson() }}"
                        class="parent-filter inline-flex items-center gap-1.5 px-3 py-1.5 border-2 rounded-lg text-xs font-medium transition-all
                            border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-[#0d1b13] dark:text-white hover:border-primary hover:bg-primary/10">
                    @if($parent->image)
                        <img src="{{ Storage::url($parent->image) }}" alt="{{ $parent->name }}"
                             class="w-4 h-4 rounded-full object-cover flex-shrink-0">
                    @endif
                    {{ $parent->name }}
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

    <!-- Baris 3: Search + Sort -->
    <div class="flex flex-col sm:flex-row gap-3 pt-1 border-t border-gray-100 dark:border-zinc-800">
        <!-- Search -->
        <form method="GET" action="{{ route('pembeli.produk.index') }}" class="flex-1">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl">search</span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari nama hewan..."
                       class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-[#0d1b13] dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            </div>
        </form>

        <!-- Sort -->
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

    <!-- Active Filters -->
    <div id="activeFilters" class="hidden flex items-center gap-2 flex-wrap pt-1 border-t border-gray-100 dark:border-zinc-800">
        <span class="text-xs text-gray-500 dark:text-zinc-400">Filter aktif:</span>
        <div id="activeFilterTags" class="flex items-center gap-2 flex-wrap"></div>
        <button onclick="clearAllFilters()"
                class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 rounded-lg text-xs font-medium transition-colors">
            <span class="material-symbols-outlined text-sm">refresh</span>
            Reset
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
document.addEventListener('DOMContentLoaded', function() {
    const productsContainer = document.getElementById('productsContainer');
    const resultsCount      = document.getElementById('results-count');
    const subCategoryRow    = document.getElementById('subCategoryRow');
    const subCategoryFilters = document.getElementById('subCategoryFilters');
    const activeFilters     = document.getElementById('activeFilters');
    const activeFilterTags  = document.getElementById('activeFilterTags');

    let currentParent   = '';
    let currentSub      = '';
    let currentSearch   = '{{ request("search", "") }}';
    let currentSort     = 'newest';
    let currentParentName = '';
    let currentSubName  = '';

    // ===================== FETCH PRODUCTS =====================
    function fetchProducts() {
        showLoading();

        const params = new URLSearchParams();
        if (currentSearch)  params.append('search', currentSearch);
        if (currentParent)  params.append('parent_category', currentParent);
        if (currentSub)     params.append('category', currentSub);
        if (currentSort)    params.append('sort', currentSort);
        params.append('ajax', '1');

        fetch(`{{ route('pembeli.produk.index') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.html) productsContainer.innerHTML = data.html;
            if (data.total !== undefined) resultsCount.textContent = data.total;
            updateActiveFilterTags();
            updateURL();
        })
        .catch(() => showError());
    }

    // ===================== LOADING =====================
    function showLoading() {
        productsContainer.innerHTML = `
            <div class="bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm">
                <div class="text-center py-16 px-4">
                    <div class="w-12 h-12 border-4 border-primary border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Memuat produk...</p>
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
        const hasFilter = currentSearch || currentParent || currentSub;
        activeFilters.classList.toggle('hidden', !hasFilter);
        activeFilters.classList.toggle('flex', hasFilter);

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
            <button data-sub=""
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

        // Attach event listeners ke sub filter buttons
        document.querySelectorAll('.sub-filter').forEach(btn => {
            btn.addEventListener('click', function() {
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
        btn.addEventListener('click', function() {
            document.querySelectorAll('.parent-filter').forEach(b => {
                b.classList.remove('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
                b.classList.add('border-gray-300', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
            });
            this.classList.remove('border-gray-300', 'dark:border-zinc-700', 'bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white');
            this.classList.add('border-primary', 'bg-primary', 'text-white', 'shadow-sm');

            currentParent     = this.dataset.parent;
            currentParentName = this.textContent.trim();
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
    document.getElementById('sortSelect').addEventListener('change', function() {
        currentSort = this.value;
        fetchProducts();
    });

    // ===================== CLEAR FUNCTIONS =====================
    window.clearParent = function() {
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
    }

    window.clearSub = function() {
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
    }

    window.clearSearch = function() {
        currentSearch = '';
        window.location.href = '{{ route("pembeli.produk.index") }}';
    }

    window.clearAllFilters = function() {
        currentParent = '';
        currentParentName = '';
        currentSub = '';
        currentSubName = '';
        currentSearch = '';
        window.location.href = '{{ route("pembeli.produk.index") }}';
    }
});
</script>
@endpush