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

    .bulk-checkbox { width: 1rem; height: 1rem; accent-color: #7BB661; cursor: pointer; }
    tr.selected-row { background-color: rgba(123, 182, 97, 0.06) !important; }
    .dark tr.selected-row { background-color: rgba(123, 182, 97, 0.08) !important; }

    .expand-btn {
        display: inline-flex; align-items: center; justify-content: center;
        width: 22px; height: 22px; border-radius: 6px;
        border: 1px solid #e5e7eb; background: #f9fafb;
        cursor: pointer; transition: background 0.15s, border-color 0.15s; flex-shrink: 0;
    }
    .dark .expand-btn { border-color: #3f3f46; background: #27272a; }
    .expand-btn:hover { border-color: #7BB661; background: rgba(123,182,97,0.08); }
    .expand-btn .expand-icon {
        font-size: 16px; color: #6b7280;
        transition: transform 0.25s cubic-bezier(0.4,0,0.2,1), color 0.15s;
        display: block; line-height: 1;
    }
    .dark .expand-btn .expand-icon { color: #a1a1aa; }
    .expand-btn.open .expand-icon  { transform: rotate(90deg); color: #7BB661; }
    .expand-btn.open { border-color: #7BB661; background: rgba(123,182,97,0.08); }

    .sub-table { width: 100%; overflow: hidden; transition: max-height 0.3s cubic-bezier(0.4,0,0.2,1), opacity 0.25s ease; }
    .sub-table tbody tr { background-color: rgba(139, 92, 246, 0.03) !important; border-left: 3px solid rgba(139, 92, 246, 0.25); }
    .dark .sub-table tbody tr { background-color: rgba(139, 92, 246, 0.05) !important; border-left: 3px solid rgba(139, 92, 246, 0.3); }
    .sub-table tbody tr:hover { background-color: rgba(139, 92, 246, 0.07) !important; }
    .dark .sub-table tbody tr:hover { background-color: rgba(139, 92, 246, 0.1) !important; }

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

    <!-- Draft Banner (permanen, tidak ada auto-dismiss) -->
    <div id="draft-banner" class="hidden items-center gap-3 p-4
         bg-yellow-50 dark:bg-yellow-500/10
         border border-yellow-200 dark:border-yellow-500/20
         rounded-xl">
        <div class="w-9 h-9 bg-yellow-100 dark:bg-yellow-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-xl">edit_note</span>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-yellow-900 dark:text-yellow-300">
                Ada draft kategori yang belum selesai
            </p>
            <p id="draft-banner-meta" class="text-xs text-yellow-700 dark:text-yellow-400 mt-0.5"></p>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('admin.categories.create') }}"
               class="flex items-center gap-1.5 px-3 py-1.5
                      bg-yellow-500 hover:bg-yellow-600
                      text-white text-xs font-medium rounded-lg transition-colors">
                <span class="material-symbols-outlined text-base">edit_note</span>
                Lanjutkan Draft
            </a>
            <button onclick="discardCategoryDraft()"
                    class="flex items-center gap-1.5 px-3 py-1.5
                           bg-white dark:bg-zinc-800
                           border border-yellow-300 dark:border-yellow-500/30
                           text-yellow-700 dark:text-yellow-400
                           hover:bg-yellow-50 dark:hover:bg-yellow-500/10
                           text-xs font-medium rounded-lg transition-colors">
                <span class="material-symbols-outlined text-base">delete</span>
                Buang
            </button>
        </div>
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
        <div id="bulk-toolbar"
             class="hidden items-center gap-3 px-4 py-3 mx-6 mt-4 bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-xl transition-all">
            <div class="flex items-center gap-2 flex-1">
                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">checklist</span>
                <span id="selected-count" class="text-sm font-semibold text-blue-700 dark:text-blue-300">0 kategori dipilih</span>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="bulkAction('activate')"
                        class="flex items-center gap-1.5 px-3 py-1.5 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-500/30 rounded-lg text-xs font-medium transition-colors">
                    <span class="material-symbols-outlined text-base">check_circle</span>Aktifkan
                </button>
                <button onclick="bulkAction('deactivate')"
                        class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 dark:bg-zinc-700 text-gray-700 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-600 rounded-lg text-xs font-medium transition-colors">
                    <span class="material-symbols-outlined text-base">cancel</span>Nonaktifkan
                </button>
                <button onclick="bulkAction('delete')"
                        class="flex items-center gap-1.5 px-3 py-1.5 bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-500/30 rounded-lg text-xs font-medium transition-colors">
                    <span class="material-symbols-outlined text-base">delete_sweep</span>Hapus Terpilih
                </button>
                <button onclick="clearSelection()"
                        class="flex items-center gap-1 px-2 py-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-zinc-300 rounded-lg text-xs transition-colors">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>
            </div>
        </div>

        <form id="bulk-form" action="{{ route('admin.categories.bulk-action') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="action" id="bulk-action-input">
            <div id="bulk-ids-container"></div>
        </form>

        <!-- Tabel -->
        <div class="overflow-x-auto mt-4">
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
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors bg-white dark:bg-zinc-900" data-id="{{ $category->id }}">
                            <td class="px-4 py-4 text-center">
                                <input type="checkbox" class="bulk-checkbox row-checkbox rounded" value="{{ $category->id }}" onchange="onCheckboxChange()">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($category->children_count > 0)
                                        <button type="button" class="expand-btn" data-target="subtable-{{ $category->id }}" onclick="toggleSubRows(this)" title="Tampilkan/Sembunyikan sub-kategori">
                                            <span class="material-symbols-outlined expand-icon">chevron_right</span>
                                        </button>
                                    @else
                                        <div class="w-[22px] flex-shrink-0"></div>
                                    @endif
                                    @if($category->image)
                                        <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-9 h-9 object-cover rounded-lg shadow-sm flex-shrink-0">
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
                                    <span class="material-symbols-outlined text-sm">inventory_2</span>{{ $category->products_count }}
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
                                    <button type="button"
                                            onclick="confirmDeleteCategory('{{ $category->name }}', '{{ route('admin.categories.destroy', $category) }}', {{ $category->children_count > 0 ? 'true' : 'false' }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-500/10 rounded-lg hover:bg-red-100 transition-colors">
                                        <span class="material-symbols-outlined text-sm">delete</span>Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>

                        @if($category->children_count > 0)
                            <tr class="sub-wrapper" data-parent="{{ $category->id }}">
                                <td colspan="6" class="p-0 border-0">
                                    <table class="w-full sub-table" id="subtable-{{ $category->id }}">
                                        <tbody>
                                            @foreach($category->children as $sub)
                                                <tr data-id="{{ $sub->id }}" style="background-color: rgba(139,92,246,0.05); border-left: 3px solid rgba(139,92,246,0.35);">
                                                    <td class="px-4 py-3 text-center">
                                                        <input type="checkbox" class="bulk-checkbox row-checkbox rounded" value="{{ $sub->id }}" onchange="onCheckboxChange()">
                                                    </td>
                                                    <td class="py-3 px-6" style="padding-left: 6rem !important;">
                                                        <div class="flex items-center gap-3">
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
                                                            <span class="material-symbols-outlined text-sm">inventory_2</span>{{ $sub->products_count }}
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
                                                            <button type="button"
                                                                    onclick="confirmDeleteCategory('{{ $sub->name }}', '{{ route('admin.categories.destroy', $sub) }}', false)"
                                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-500/10 rounded-lg hover:bg-red-100 transition-colors">
                                                                <span class="material-symbols-outlined text-sm">delete</span>Hapus
                                                            </button>
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
                                        <span class="material-symbols-outlined text-lg">add</span>Tambah Kategori
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

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
// DRAFT BANNER — cek localStorage saat index dimuat
// ════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
    // Init tree view
    document.querySelectorAll('.sub-wrapper').forEach(w => w.style.display = 'none');
    document.querySelectorAll('.expand-btn').forEach(btn => btn.classList.remove('open'));

    // Cek draft kategori
    try {
        const raw = localStorage.getItem('category_draft');
        if (!raw) return;
        const data = JSON.parse(raw);
        if (!data?.name?.trim()) return;

        const banner = document.getElementById('draft-banner');
        const meta   = document.getElementById('draft-banner-meta');
        if (!banner) return;

        const savedAt = new Date(data.savedAt);
        const timeStr = savedAt.toLocaleDateString('id-ID', {
            day: '2-digit', month: 'short', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });

        if (meta) meta.textContent = `Terakhir disimpan ${timeStr} · "${data.name}"`;

        banner.classList.remove('hidden');
        banner.classList.add('flex');
    } catch(e) {}
});

// Fungsi buang draft — di luar DOMContentLoaded agar bisa dipanggil onclick
function discardCategoryDraft() {
    Swal.fire({
        title: 'Buang draft?',
        text: 'Data kategori yang sudah diisi akan dihapus.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Buang!',
        cancelButtonText: 'Batal',
    }).then(result => {
        if (!result.isConfirmed) return;
        try { localStorage.removeItem('category_draft'); } catch(e) {}
        const banner = document.getElementById('draft-banner');
        if (banner) {
            banner.style.transition = 'opacity 0.3s, transform 0.3s';
            banner.style.opacity    = '0';
            banner.style.transform  = 'translateY(-8px)';
            setTimeout(() => banner.remove(), 300);
        }
    });
}

// ════════════════════════════════════════════
// TREE VIEW
// ════════════════════════════════════════════
function toggleSubRows(btn) {
    const tableId   = btn.dataset.target;
    const table     = document.getElementById(tableId);
    if (!table) return;
    const wrapperTr = table.closest('tr.sub-wrapper');
    const isOpen    = btn.classList.contains('open');
    if (isOpen) {
        table.style.transition = 'max-height 0.3s cubic-bezier(0.4,0,0.2,1), opacity 0.25s ease';
        table.style.overflow   = 'hidden';
        table.style.maxHeight  = table.scrollHeight + 'px';
        table.style.opacity    = '1';
        requestAnimationFrame(() => { table.style.maxHeight = '0'; table.style.opacity = '0'; });
        table.addEventListener('transitionend', function onEnd(e) {
            if (e.propertyName !== 'max-height') return;
            if (wrapperTr) wrapperTr.style.display = 'none';
            table.removeEventListener('transitionend', onEnd);
        });
        btn.classList.remove('open');
    } else {
        if (wrapperTr) wrapperTr.style.display = '';
        table.style.overflow  = 'hidden';
        table.style.maxHeight = '0';
        table.style.opacity   = '0';
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
function expandAll()  { document.querySelectorAll('.expand-btn:not(.open)').forEach(btn => toggleSubRows(btn)); }
function collapseAll(){ document.querySelectorAll('.expand-btn.open').forEach(btn => toggleSubRows(btn)); }

// ════════════════════════════════════════════
// BULK ACTION
// ════════════════════════════════════════════
const checkAll    = document.getElementById('check-all');
const bulkToolbar = document.getElementById('bulk-toolbar');

function getRowCheckboxes() { return [...document.querySelectorAll('.row-checkbox')]; }
function getChecked()        { return getRowCheckboxes().filter(c => c.checked); }

function updateToolbar() {
    const count = getChecked().length;
    if (count > 0) {
        bulkToolbar.classList.remove('hidden');
        bulkToolbar.classList.add('flex');
        document.getElementById('selected-count').textContent = `${count} kategori dipilih`;
    } else {
        bulkToolbar.classList.add('hidden');
        bulkToolbar.classList.remove('flex');
    }
    const total = getRowCheckboxes().length;
    checkAll.checked       = count === total && total > 0;
    checkAll.indeterminate = count > 0 && count < total;
}

function onCheckboxChange() {
    updateToolbar();
    getRowCheckboxes().forEach(cb => cb.closest('tr').classList.toggle('selected-row', cb.checked));
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

function submitBulkForm(action, checked) {
    const form      = document.getElementById('bulk-form');
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

function bulkAction(action) {
    const checked = getChecked();
    if (!checked.length) return;
    const configs = {
        delete:     { title: 'Hapus Kategori Terpilih?', text: `${checked.length} kategori akan dihapus permanen. Kategori yang masih punya sub-kategori tidak akan terhapus.`, icon: 'warning', confirmButtonColor: '#ef4444', confirmButtonText: 'Ya, Hapus Semua!' },
        activate:   { title: 'Aktifkan Kategori Terpilih?', text: `${checked.length} kategori akan diaktifkan.`, icon: 'question', confirmButtonColor: '#22c55e', confirmButtonText: 'Ya, Aktifkan!' },
        deactivate: { title: 'Nonaktifkan Kategori Terpilih?', text: `${checked.length} kategori akan dinonaktifkan.`, icon: 'question', confirmButtonColor: '#6b7280', confirmButtonText: 'Ya, Nonaktifkan!' },
    };
    const cfg = configs[action];
    if (!cfg) return;
    Swal.fire({ ...cfg, showCancelButton: true, cancelButtonColor: '#6b7280', cancelButtonText: 'Batal' })
        .then(result => { if (result.isConfirmed) submitBulkForm(action, checked); });
}

// ════════════════════════════════════════════
// FILTER + DELETE
// ════════════════════════════════════════════
function applyFilter(key, value) {
    const url = new URL(window.location.href);
    url.searchParams.set(key, value);
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
}

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
    }).then(result => {
        if (!result.isConfirmed) return;
        const form     = document.createElement('form');
        form.method    = 'POST';
        form.action    = actionUrl;
        form.innerHTML = `@csrf <input type="hidden" name="_method" value="DELETE">`;
        document.body.appendChild(form);
        form.submit();
    });
}
</script>

@endsection