{{-- resources/views/admin/categories/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Kategori')

@section('content')

<style>
    .pagination { display: flex; gap: 0.5rem; align-items: center; }
    .pagination .page-link { padding: 0.5rem 0.75rem; font-size: 0.875rem; font-weight: 500; border-radius: 0.5rem; transition: all 0.2s; }
    .pagination .page-item.active .page-link { background: linear-gradient(to right, #7BB661, #72e236); color: white; border: none; }
    .pagination .page-link:hover:not(.active) { background-color: rgba(123, 182, 97, 0.1); color: #7BB661; }
    .dark .pagination .page-link { color: #e4e4e7; background-color: #27272a; border-color: #3f3f46; }
    .dark .pagination .page-link:hover:not(.active) { background-color: rgba(123, 182, 97, 0.15); border-color: #7BB661; }
    .animate-fade { animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }

    /* ── Bulk toolbar ── */
    #bulk-toolbar {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        max-height: 0;
        overflow: hidden;
        opacity: 0;
    }
    #bulk-toolbar.show { max-height: 80px; opacity: 1; }
    .bulk-checkbox { width: 1rem; height: 1rem; accent-color: #7BB661; cursor: pointer; }
    tr.selected-row { background-color: rgba(123, 182, 97, 0.06) !important; }
    .dark tr.selected-row { background-color: rgba(123, 182, 97, 0.08) !important; }

    /* ── Expand button ── */
    .expand-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        cursor: pointer;
        transition: background 0.15s, border-color 0.15s;
        flex-shrink: 0;
    }
    .dark .expand-btn { border-color: #3f3f46; background: #27272a; }
    .expand-btn:hover { border-color: #7BB661; background: rgba(123,182,97,0.08); }
    .expand-btn .expand-icon {
        font-size: 16px;
        color: #6b7280;
        transition: transform 0.25s cubic-bezier(0.4,0,0.2,1), color 0.15s;
        display: block;
        line-height: 1;
    }
    .dark .expand-btn .expand-icon { color: #a1a1aa; }
    .expand-btn.open .expand-icon  { transform: rotate(90deg); color: #7BB661; }
    .expand-btn.open { border-color: #7BB661; background: rgba(123,182,97,0.08); }

    /* ── Sub table ── */
    .sub-table {
        width: 100%;
        overflow: hidden;
        transition: max-height 0.3s cubic-bezier(0.4,0,0.2,1), opacity 0.25s ease;
    }

    /* ── Sub row styling — beda jelas dari parent ── */
    .sub-table tbody tr {
        background-color: rgba(139, 92, 246, 0.03) !important;
        border-left: 3px solid rgba(139, 92, 246, 0.25);
    }
    .dark .sub-table tbody tr {
        background-color: rgba(139, 92, 246, 0.05) !important;
        border-left: 3px solid rgba(139, 92, 246, 0.3);
    }
    .sub-table tbody tr:hover {
        background-color: rgba(139, 92, 246, 0.07) !important;
    }
    .dark .sub-table tbody tr:hover {
        background-color: rgba(139, 92, 246, 0.1) !important;
    }

    /* ── Kolom AKSI center ── */
    th.col-aksi { text-align: center !important; }
    td.col-aksi > div { justify-content: center !important; }
</style>

<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">Kelola Kategori</h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Manajemen kategori & sub-kategori produk toko Anda</p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-soft-green to-primary rounded-lg hover:shadow-lg transition-all">
            <span class="material-symbols-outlined text-lg">add</span>
            Tambah Kategori
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-xl">category</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 font-medium">Kategori Utama</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalParent }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-50 dark:bg-purple-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-xl">account_tree</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 font-medium">Sub Kategori</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalSub }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-50 dark:bg-green-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-xl">inventory_2</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 font-medium">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $categories->sum(fn($c) => $c->products_count + $c->children->sum(fn($s) => $s->products_count)) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Kategori -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">

        <!-- Header Tabel -->
        <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-soft-green/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-soft-green text-lg">account_tree</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Kategori</h3>
                        <p class="text-sm text-gray-500 dark:text-zinc-400">{{ $totalParent }} kategori utama, {{ $totalSub }} sub kategori</p>
                    </div>
                </div>

                <!-- Tombol Expand / Collapse semua -->
                <div class="flex items-center gap-2">
                    <button onclick="expandAll()"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-zinc-300 bg-gray-100 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-700 transition-colors">
                        <span class="material-symbols-outlined text-sm">unfold_more</span>
                        Buka Semua
                    </button>
                    <button onclick="collapseAll()"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-zinc-300 bg-gray-100 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-700 transition-colors">
                        <span class="material-symbols-outlined text-sm">unfold_less</span>
                        Tutup Semua
                    </button>
                </div>
            </div>

            <!-- Search + Filter -->
            <div class="mt-4 flex flex-col sm:flex-row gap-3">
                <form method="GET" id="filter-form" class="flex-1 sm:max-w-xs">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500 text-xl">search</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari kategori..."
                            class="pl-10 pr-4 py-2.5 w-full bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white placeholder:text-gray-400 focus:ring-2 focus:ring-soft-green focus:border-transparent transition-all">
                        <input type="hidden" name="sort"   value="{{ request('sort', 'latest') }}">
                        <input type="hidden" name="status" value="{{ request('status', '') }}">
                        <input type="hidden" name="type"   value="{{ request('type', '') }}">
                    </div>
                </form>

                <div class="flex flex-wrap items-center gap-2">
                    <select onchange="applyFilter('sort', this.value)"
                            class="text-sm bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-zinc-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-soft-green cursor-pointer">
                        <option value="latest"    {{ request('sort','latest')==='latest'    ?'selected':'' }}>Terbaru</option>
                        <option value="name_asc"  {{ request('sort','latest')==='name_asc'  ?'selected':'' }}>Nama A–Z</option>
                        <option value="name_desc" {{ request('sort','latest')==='name_desc' ?'selected':'' }}>Nama Z–A</option>
                        <option value="products"  {{ request('sort','latest')==='products'  ?'selected':'' }}>Terbanyak Produk</option>
                    </select>
                    <select onchange="applyFilter('status', this.value)"
                            class="text-sm bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-zinc-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-soft-green cursor-pointer">
                        <option value=""  {{ request('status','')==''  ?'selected':'' }}>Semua Status</option>
                        <option value="1" {{ request('status','')=='1' ?'selected':'' }}>Aktif</option>
                        <option value="0" {{ request('status','')=='0' ?'selected':'' }}>Nonaktif</option>
                    </select>
                    <select onchange="applyFilter('type', this.value)"
                            class="text-sm bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-zinc-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-soft-green cursor-pointer">
                        <option value=""       {{ request('type','')==''       ?'selected':'' }}>Semua Tipe</option>
                        <option value="parent" {{ request('type','')=='parent' ?'selected':'' }}>Punya Sub Kategori</option>
                    </select>

                    @php
                        $activeFilters = collect([
                            request('search'), request('status'), request('type'),
                            request('sort') !== 'latest' ? request('sort') : null
                        ])->filter()->count();
                    @endphp
                    @if($activeFilters > 0)
                        <a href="{{ route('admin.categories.index') }}"
                           class="inline-flex items-center gap-1 px-3 py-2 text-xs font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 rounded-lg hover:bg-red-100 transition-colors">
                            <span class="material-symbols-outlined text-sm">close</span>
                            Reset
                            <span class="ml-1 w-4 h-4 bg-red-500 text-white rounded-full text-[10px] flex items-center justify-center font-bold">{{ $activeFilters }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── BULK ACTION TOOLBAR ── --}}
        <div id="bulk-toolbar" class="border-b border-soft-green/30 dark:border-soft-green/20 bg-soft-green/5">
            <div class="px-6 py-3 flex flex-wrap items-center gap-3">
                <span class="text-sm font-semibold text-gray-700 dark:text-zinc-200">
                    <span id="selected-count">0</span> item dipilih
                </span>
                <div class="h-4 w-px bg-gray-300 dark:bg-zinc-600"></div>
                <button onclick="bulkAction('activate')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-lg hover:bg-green-100 transition-colors">
                    <span class="material-symbols-outlined text-sm">check_circle</span>Aktifkan
                </button>
                <button onclick="bulkAction('deactivate')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-orange-700 dark:text-orange-400 bg-orange-50 dark:bg-orange-500/10 border border-orange-200 dark:border-orange-500/20 rounded-lg hover:bg-orange-100 transition-colors">
                    <span class="material-symbols-outlined text-sm">block</span>Nonaktifkan
                </button>
                <button onclick="bulkAction('delete')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-lg hover:bg-red-100 transition-colors">
                    <span class="material-symbols-outlined text-sm">delete</span>Hapus Terpilih
                </button>
                <button onclick="clearSelection()"
                    class="ml-auto inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-500 dark:text-zinc-400 hover:text-gray-700 transition-colors">
                    <span class="material-symbols-outlined text-sm">close</span>Batal
                </button>
            </div>
        </div>

        <!-- Form Bulk -->
        <form id="bulk-form" action="{{ route('admin.categories.bulk-action') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="action" id="bulk-action-input">
            <div id="bulk-ids-container"></div>
        </form>

        <!-- Tabel -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-zinc-800/50 border-b border-gray-200 dark:border-zinc-800">
                    <tr>
                        <th class="px-4 py-4">
                            <input type="checkbox" id="check-all" class="bulk-checkbox rounded" title="Pilih semua">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Slug</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Produk</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-800">
                    @forelse($categories as $category)

                        {{-- ══ BARIS KATEGORI UTAMA ══ --}}
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors bg-white dark:bg-zinc-900"
                            data-id="{{ $category->id }}">
                            <td class="px-4 py-4 text-center">
                                <input type="checkbox" class="bulk-checkbox row-checkbox rounded"
                                    value="{{ $category->id }}" onchange="onCheckboxChange()">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($category->children_count > 0)
                                        <button type="button"
                                                class="expand-btn"
                                                data-target="subtable-{{ $category->id }}"
                                                onclick="toggleSubRows(this)"
                                                title="Tampilkan/Sembunyikan sub-kategori">
                                            <span class="material-symbols-outlined expand-icon">chevron_right</span>
                                        </button>
                                    @else
                                        <div class="w-[22px] flex-shrink-0"></div>
                                    @endif

                                    @if($category->image)
                                        <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}"
                                            class="w-9 h-9 object-cover rounded-lg shadow-sm flex-shrink-0">
                                    @else
                                        <div class="w-9 h-9 bg-gradient-to-br from-soft-green to-primary rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-sm flex-shrink-0">
                                            {{ strtoupper(substr($category->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $category->name }}</p>
                                            @if($category->children_count > 0)
                                                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 rounded text-[10px] font-medium flex-shrink-0">
                                                    <span class="material-symbols-outlined text-xs">account_tree</span>
                                                    {{ $category->children_count }} sub
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-blue-500 dark:text-blue-400 mt-0.5">Kategori Utama</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <code class="text-xs text-gray-600 dark:text-zinc-300 bg-gray-100 dark:bg-zinc-800 px-2 py-1 rounded break-all">{{ $category->slug }}</code>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 rounded-full text-xs font-semibold">
                                    <span class="material-symbols-outlined text-sm">inventory_2</span>
                                    {{ $category->products_count }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($category->is_active)
                                    <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 rounded-full text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full flex-shrink-0"></span>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400 rounded-full text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full flex-shrink-0"></span>Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 rounded-lg hover:bg-blue-100 transition-colors">
                                        <span class="material-symbols-outlined text-sm">edit</span>Edit
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="button"
                                                onclick="confirmDeleteCategory('{{ $category->name }}', '{{ route('admin.categories.destroy', $category) }}', {{ $category->children_count > 0 ? 'true' : 'false' }})"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-500/10 rounded-lg hover:bg-red-100 transition-colors">
                                            <span class="material-symbols-outlined text-sm">delete</span>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- ══ SUB-KATEGORI ══ --}}
                        @if($category->children_count > 0)
                            <tr class="sub-wrapper" data-parent="{{ $category->id }}">
                                <td colspan="6" class="p-0 border-0">
                                    <table class="w-full sub-table" id="subtable-{{ $category->id }}">
                                        <tbody>
                                            @foreach($category->children as $sub)
                                                <tr data-id="{{ $sub->id }}"
                                                    style="background-color: rgba(139,92,246,0.05); border-left: 3px solid rgba(139,92,246,0.35);">
                                                    <td class="px-4 py-3 text-center">
                                                        <input type="checkbox" class="bulk-checkbox row-checkbox rounded"
                                                            value="{{ $sub->id }}" onchange="onCheckboxChange()">
                                                    </td>
                                                    <td class="py-3 px-6" style="padding-left: 6rem !important;">
                                                        <div class="flex items-center gap-3">
                                                            {{-- Tree connector --}}
                                                            <div class="flex-shrink-0 w-[22px] flex items-center justify-center">
                                                                <svg width="14" height="24" viewBox="0 0 14 24" fill="none" class="text-purple-300 dark:text-purple-700">
                                                                    @if(!$loop->last)
                                                                        <line x1="2" y1="0" x2="2" y2="24" stroke="currentColor" stroke-width="1.5"/>
                                                                    @else
                                                                        <line x1="2" y1="0" x2="2" y2="12" stroke="currentColor" stroke-width="1.5"/>
                                                                    @endif
                                                                    <line x1="2" y1="12" x2="14" y2="12" stroke="currentColor" stroke-width="1.5"/>
                                                                </svg>
                                                            </div>
                                                            <div class="w-8 h-8 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-sm flex-shrink-0">
                                                                {{ strtoupper(substr($sub->name, 0, 1)) }}
                                                            </div>
                                                            <div class="min-w-0">
                                                                <p class="text-sm font-medium text-gray-800 dark:text-zinc-200 truncate">{{ $sub->name }}</p>
                                                                <p class="text-xs text-purple-500 dark:text-purple-400 mt-0.5">Sub Kategori</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-3">
                                                        <code class="text-xs text-gray-500 dark:text-zinc-400 bg-gray-100 dark:bg-zinc-800 px-2 py-1 rounded break-all">{{ $sub->slug }}</code>
                                                    </td>
                                                    <td class="px-4 py-3 text-center">
                                                        <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1 bg-purple-50 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400 rounded-full text-xs font-semibold">
                                                            <span class="material-symbols-outlined text-sm">inventory_2</span>
                                                            {{ $sub->products_count }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-center">
                                                        @if($sub->is_active)
                                                            <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 rounded-full text-xs font-semibold">
                                                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full flex-shrink-0"></span>Aktif
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400 rounded-full text-xs font-semibold">
                                                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full flex-shrink-0"></span>Nonaktif
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex items-center justify-center gap-2">
                                                            <a href="{{ route('admin.categories.edit', $sub) }}"
                                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 rounded-lg hover:bg-blue-100 transition-colors">
                                                                <span class="material-symbols-outlined text-sm">edit</span>Edit
                                                            </a>
                                                            <form action="{{ route('admin.categories.destroy', $sub) }}" method="POST" class="inline">
                                                                @csrf @method('DELETE')
                                                                <button type="button"
                                                                        onclick="confirmDeleteCategory('{{ $sub->name }}', '{{ route('admin.categories.destroy', $sub) }}', false)"
                                                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-500/10 rounded-lg hover:bg-red-100 transition-colors">
                                                                    <span class="material-symbols-outlined text-sm">delete</span>Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endif

                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-4xl">category</span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white mb-1">Belum ada kategori</p>
                                    <p class="text-xs text-gray-500 dark:text-zinc-400 mb-4">Mulai dengan menambahkan kategori pertama</p>
                                    <a href="{{ route('admin.categories.create') }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-soft-green to-primary rounded-lg hover:shadow-lg transition-all">
                                        <span class="material-symbols-outlined text-lg">add</span>
                                        Tambah Kategori
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-800/30">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-600 dark:text-zinc-400">
                        Menampilkan <span class="font-semibold">{{ $categories->firstItem() }}</span>
                        sampai <span class="font-semibold">{{ $categories->lastItem() }}</span>
                        dari <span class="font-semibold">{{ $categories->total() }}</span> kategori utama
                    </div>
                    <div>{{ $categories->links() }}</div>
                </div>
            </div>
        @endif

    </div>
</div>

<script>
// ════════════════════════════════════════════
// TREE VIEW EXPAND / COLLAPSE
// ════════════════════════════════════════════

function toggleSubRows(btn) {
    const tableId   = btn.dataset.target;
    const table     = document.getElementById(tableId);
    if (!table) return;

    // <tr class="sub-wrapper"> = parent dari <td> yang membungkus sub-table
    const wrapperTr = table.closest('tr.sub-wrapper');
    const isOpen    = btn.classList.contains('open');

    if (isOpen) {
        // COLLAPSE
        table.style.transition = 'max-height 0.3s cubic-bezier(0.4,0,0.2,1), opacity 0.25s ease';
        table.style.overflow   = 'hidden';
        table.style.maxHeight  = table.scrollHeight + 'px';
        table.style.opacity    = '1';

        requestAnimationFrame(() => {
            table.style.maxHeight = '0';
            table.style.opacity   = '0';
        });

        // Sembunyikan wrapper <tr> setelah animasi selesai
        table.addEventListener('transitionend', function onEnd(e) {
            if (e.propertyName !== 'max-height') return;
            if (wrapperTr) wrapperTr.style.display = 'none'; // ← ini kuncinya
            table.removeEventListener('transitionend', onEnd);
        });

        btn.classList.remove('open');

    } else {
        // EXPAND — tampilkan dulu wrapper <tr>-nya
        if (wrapperTr) wrapperTr.style.display = ''; // ← kembalikan display

        table.style.overflow   = 'hidden';
        table.style.maxHeight  = '0';
        table.style.opacity    = '0';

        requestAnimationFrame(() => {
            table.style.transition = 'max-height 0.3s cubic-bezier(0.4,0,0.2,1), opacity 0.25s ease';
            table.style.maxHeight  = table.scrollHeight + 'px';
            table.style.opacity    = '1';
        });

        table.addEventListener('transitionend', function onEnd(e) {
            if (e.propertyName !== 'max-height') return;
            table.style.maxHeight = 'none';
            table.style.overflow  = 'visible';
            table.removeEventListener('transitionend', onEnd);
        });

        btn.classList.add('open');
    }
}

function expandAll() {
    document.querySelectorAll('.expand-btn:not(.open)').forEach(btn => toggleSubRows(btn));
}

function collapseAll() {
    document.querySelectorAll('.expand-btn.open').forEach(btn => toggleSubRows(btn));
}


// ════════════════════════════════════════════
// BULK ACTION
// ════════════════════════════════════════════
const checkAll      = document.getElementById('check-all');
const toolbar       = document.getElementById('bulk-toolbar');
const selectedCount = document.getElementById('selected-count');

function getRowCheckboxes() { return [...document.querySelectorAll('.row-checkbox')]; }
function getChecked()        { return getRowCheckboxes().filter(c => c.checked); }

function updateToolbar() {
    const checked = getChecked();
    selectedCount.textContent = checked.length;
    toolbar.classList.toggle('show', checked.length > 0);
    const total = getRowCheckboxes().length;
    checkAll.checked       = checked.length === total && total > 0;
    checkAll.indeterminate = checked.length > 0 && checked.length < total;
}

function onCheckboxChange() {
    updateToolbar();
    getRowCheckboxes().forEach(cb => {
        cb.closest('tr').classList.toggle('selected-row', cb.checked);
    });
}

checkAll.addEventListener('change', function () {
    getRowCheckboxes().forEach(cb => { cb.checked = this.checked; });
    onCheckboxChange();
});

function clearSelection() {
    checkAll.checked = false;
    getRowCheckboxes().forEach(cb => { cb.checked = false; });
    onCheckboxChange();
}

function bulkAction(action) {
    const checked = getChecked();
    if (!checked.length) return;
    if (action === 'delete') {
        if (!confirm(`Yakin hapus ${checked.length} item?\n\n⚠️ Kategori yang masih punya sub-kategori tidak akan terhapus.`)) return;
    }
    const form = document.getElementById('bulk-form');
    const container = document.getElementById('bulk-ids-container');
    document.getElementById('bulk-action-input').value = action;
    container.innerHTML = '';
    checked.forEach(cb => {
        const inp = document.createElement('input');
        inp.type = 'hidden'; inp.name = 'ids[]'; inp.value = cb.value;
        container.appendChild(inp);
    });
    form.submit();
}


// ════════════════════════════════════════════
// FILTER HELPERS
// ════════════════════════════════════════════
function applyFilter(key, value) {
    const url = new URL(window.location.href);
    url.searchParams.set(key, value);
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
}

// ── SweetAlert Confirm Delete ──
function confirmDeleteCategory(name, actionUrl, hasSub) {
    Swal.fire({
        title: 'Hapus Kategori?',
        html: `Yakin ingin menghapus <strong>${name}</strong>?`
            + (hasSub ? `<br><span class="text-sm text-red-400 mt-1 block">⚠️ Sub-kategorinya harus dihapus dulu.</span>` : ''),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = actionUrl;
            form.innerHTML = `
                @csrf
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}


// ════════════════════════════════════════════
// AUTO-HIDE ALERTS + SWAL
// ════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.sub-wrapper').forEach(w => w.style.display = 'none');
    document.querySelectorAll('.expand-btn').forEach(btn => btn.classList.remove('open'));
      
});
</script>

@endsection