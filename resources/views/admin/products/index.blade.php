{{-- resources/views/admin/products/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Produk - E-Commerce TSA')

@section('content')
<div class="space-y-6">

    <!-- Success Alert -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-lg p-4 animate-fade-in">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">check_circle</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-green-900 dark:text-green-300">Berhasil!</h3>
                    <p class="text-sm text-green-800 dark:text-green-400 mt-1">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="flex-shrink-0 text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>
    @endif

    <!-- Error Alert -->
    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-lg p-4 animate-fade-in">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-2xl">error</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-red-900 dark:text-red-300">Gagal!</h3>
                    <p class="text-sm text-red-800 dark:text-red-400 mt-1">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="flex-shrink-0 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>
    @endif

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-soft-green transition-colors">Dashboard</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-gray-900 dark:text-white font-medium">Produk</span>
    </nav>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">Kelola Produk</h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Kelola semua produk di toko Anda</p>
        </div>
        <a href="{{ route('admin.products.create') }}" 
           class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-soft-green to-primary rounded-lg hover:shadow-lg transition-all">
            <span class="material-symbols-outlined text-lg">add</span>
            Tambah Produk
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg border border-gray-200 dark:border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">inventory_2</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400">Total Produk</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white" id="stat-total">{{ $products->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg border border-gray-200 dark:border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400">Produk Aktif</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white" id="stat-active">{{ $products->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg border border-gray-200 dark:border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 dark:bg-red-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-400">warning</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400">Stok Rendah</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white" id="stat-low-stock">{{ $products->where('stock', '<=', 5)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg border border-gray-200 dark:border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 dark:bg-gray-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-gray-600 dark:text-gray-400">cancel</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400">Nonaktif</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white" id="stat-inactive">{{ $products->where('is_active', false)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-4 space-y-3">
        <!-- Baris 1: Search + Kategori + Reset -->
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500">search</span>
                    <input type="text" id="searchInput" placeholder="Cari nama produk..."
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                </div>
            </div>
            <div class="w-full sm:w-56">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500">category</span>
                    <select id="categoryFilter"
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors appearance-none">
                        <option value="">Semua Kategori</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500 pointer-events-none">expand_more</span>
                </div>
            </div>
            <button id="resetBtn"
                    class="hidden items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-gray-700 dark:text-zinc-300 font-medium rounded-lg transition-colors text-sm">
                <span class="material-symbols-outlined text-base">filter_list_off</span>
                Reset Filter
            </button>
        </div>

        <!-- Baris 2: Filter Tambahan -->
        <div class="flex flex-wrap gap-3">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500 text-sm">toggle_on</span>
                <select id="statusFilter"
                        class="pl-9 pr-8 py-2 text-sm bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors appearance-none">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
                <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-sm">expand_more</span>
            </div>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500 text-sm">inventory</span>
                <select id="stockFilter"
                        class="pl-9 pr-8 py-2 text-sm bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors appearance-none">
                    <option value="">Semua Stok</option>
                    <option value="low">Stok Rendah (≤5)</option>
                    <option value="empty">Stok Habis (0)</option>
                </select>
                <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-sm">expand_more</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500 text-xs font-medium">Rp</span>
                    <input type="number" id="priceMin" placeholder="Harga Min" min="0"
                           class="pl-9 pr-3 py-2 text-sm w-36 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                </div>
                <span class="text-gray-400 dark:text-zinc-500 text-sm font-medium">—</span>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500 text-xs font-medium">Rp</span>
                    <input type="number" id="priceMax" placeholder="Harga Max" min="0"
                           class="pl-9 pr-3 py-2 text-sm w-36 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                </div>
            </div>
        </div>

        <!-- Baris 3: Sort + Per Page + Loading -->
        <div class="flex flex-wrap items-center gap-2 pt-1 border-t border-gray-100 dark:border-zinc-800">
            <span class="text-xs text-gray-400 dark:text-zinc-500 font-medium">Urutkan:</span>
            <button class="sort-btn flex items-center gap-1 px-2.5 py-1 text-xs rounded-lg border border-gray-200 dark:border-zinc-700 text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors" data-sort="name">
                Nama <span class="sort-icon" data-col="name">↕</span>
            </button>
            <button class="sort-btn flex items-center gap-1 px-2.5 py-1 text-xs rounded-lg border border-gray-200 dark:border-zinc-700 text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors" data-sort="price">
                Harga <span class="sort-icon" data-col="price">↕</span>
            </button>
            <button class="sort-btn flex items-center gap-1 px-2.5 py-1 text-xs rounded-lg border border-gray-200 dark:border-zinc-700 text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors" data-sort="stock">
                Stok <span class="sort-icon" data-col="stock">↕</span>
            </button>
            <button class="sort-btn flex items-center gap-1 px-2.5 py-1 text-xs rounded-lg border border-gray-200 dark:border-zinc-700 text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors" data-sort="created_at">
                Terbaru <span class="sort-icon" data-col="created_at">↓</span>
            </button>

            <!-- Per Page Dropdown -->
            <div class="flex items-center gap-2 ml-auto">
                <span class="text-xs text-gray-400 dark:text-zinc-500">Tampilkan:</span>
                <select id="perPageSelect"
                        class="px-2 py-1 text-xs bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-gray-700 dark:text-zinc-300 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <span class="text-xs text-gray-400 dark:text-zinc-500">per halaman</span>
            </div>

            <!-- Loading Indicator -->
            <div id="loadingIndicator" class="hidden flex items-center gap-2 text-xs text-gray-400 dark:text-zinc-500">
                <svg class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memuat...
            </div>
        </div>
    </div>

    <!-- ✅ Bulk Action Toolbar (muncul saat ada produk dipilih) -->
    <div id="bulkToolbar"
         class="hidden items-center gap-3 px-4 py-3 bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-xl transition-all">
        <div class="flex items-center gap-2 flex-1">
            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">checklist</span>
            <span id="bulkCount" class="text-sm font-semibold text-blue-700 dark:text-blue-300">0 produk dipilih</span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="bulkActivate()"
                    class="flex items-center gap-1.5 px-3 py-1.5 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-500/30 rounded-lg text-xs font-medium transition-colors">
                <span class="material-symbols-outlined text-base">check_circle</span>
                Aktifkan
            </button>
            <button onclick="bulkDeactivate()"
                    class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 dark:bg-zinc-700 text-gray-700 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-600 rounded-lg text-xs font-medium transition-colors">
                <span class="material-symbols-outlined text-base">cancel</span>
                Nonaktifkan
            </button>
            <button onclick="bulkDelete()"
                    class="flex items-center gap-1.5 px-3 py-1.5 bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-500/30 rounded-lg text-xs font-medium transition-colors">
                <span class="material-symbols-outlined text-base">delete_sweep</span>
                Hapus Terpilih
            </button>
            <button onclick="clearSelection()"
                    class="flex items-center gap-1 px-2 py-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-zinc-300 rounded-lg text-xs transition-colors">
                <span class="material-symbols-outlined text-base">close</span>
            </button>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-zinc-800/50 border-b border-gray-200 dark:border-zinc-800">
                    <tr>
                        <!-- ✅ Kolom Checkbox -->
                        <th class="px-4 py-4 w-10">
                            <input type="checkbox" id="checkAll"
                                   class="w-4 h-4 rounded border-gray-300 dark:border-zinc-600 text-soft-green focus:ring-soft-green cursor-pointer">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider">Gambar</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider">Nama Produk</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-zinc-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="productsTableBody" class="divide-y divide-gray-200 dark:divide-zinc-800">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors product-row" data-product-id="{{ $product->id }}">
                            <!-- Checkbox -->
                            <td class="px-4 py-4">
                                <input type="checkbox" class="product-checkbox w-4 h-4 rounded border-gray-300 dark:border-zinc-600 text-soft-green focus:ring-soft-green cursor-pointer"
                                       value="{{ $product->id }}">
                            </td>
                            <td class="px-6 py-4">
                                @if($product->image)
                                    <button onclick="openImageModal('{{ asset('storage/' . $product->image) }}', '{{ $product->name }}')"
                                            class="group relative block cursor-zoom-in">
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                            alt="{{ $product->name }}"
                                            class="h-14 w-14 object-cover rounded-lg border border-gray-200 dark:border-zinc-700 group-hover:opacity-80 transition-opacity">
                                        <!-- Overlay icon zoom -->
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span class="material-symbols-outlined text-white text-lg drop-shadow">zoom_in</span>
                                        </div>
                                    </button>
                                @else
                                    <button onclick="openImageModal(null, '{{ $product->name }}')"
                                            class="h-14 w-14 bg-gray-200 dark:bg-zinc-700 rounded-lg flex items-center justify-center border border-gray-300 dark:border-zinc-600 hover:bg-gray-300 dark:hover:bg-zinc-600 transition-colors cursor-pointer">
                                        <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-xl">image</span>
                                    </button>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">{{ Str::limit($product->description, 50) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($product->category)
                                    @if($product->category->parent)
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 text-xs font-medium rounded-full w-fit">
                                                <span class="material-symbols-outlined text-sm">category</span>
                                                {{ $product->category->parent->name }}
                                            </span>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400 text-xs font-medium rounded-full w-fit">
                                                <span class="material-symbols-outlined text-sm">account_tree</span>
                                                {{ $product->category->name }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 text-xs font-medium rounded-full">
                                            <span class="material-symbols-outlined text-sm">category</span>
                                            {{ $product->category->name }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400 dark:text-zinc-500">Tanpa Kategori</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-gray-900 dark:text-white">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400">per {{ $product->unit }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg font-semibold text-sm
                                    {{ $product->stock <= 5 ? 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400' : 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400' }}">
                                    @if($product->stock <= 5)
                                        <span class="material-symbols-outlined text-base">warning</span>
                                    @endif
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full
                                    {{ $product->is_active ? 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-500/20 text-gray-700 dark:text-gray-400' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $product->is_active ? 'bg-green-500' : 'bg-gray-500' }}"></span>
                                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="flex items-center gap-1 px-3 py-1.5 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 rounded-lg text-xs font-medium transition-colors">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="confirmDelete(this.closest('form'), '{{ $product->name }}')"
                                                class="flex items-center gap-1 px-3 py-1.5 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 rounded-lg text-xs font-medium transition-colors">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-6xl mb-3">inventory_2</span>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Belum ada produk</p>
                                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Mulai tambahkan produk untuk ditampilkan di sini</p>
                                    <a href="{{ route('admin.products.create') }}"
                                       class="mt-4 flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-soft-green to-primary text-white text-sm font-medium rounded-lg hover:shadow-lg transition-all">
                                        <span class="material-symbols-outlined text-base">add</span>
                                        Tambah Produk Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="paginationContainer" class="px-6 py-4 border-t border-gray-200 dark:border-zinc-800">
            @if($products->hasPages())
                {{ $products->links() }}
            @endif
        </div>
    </div>
<!-- Modal Preview Gambar -->
<div id="imageModal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     onclick="closeImageModal()">
    
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
    
    <!-- Modal Content -->
    <div class="relative z-10 max-w-2xl w-full" onclick="event.stopPropagation()">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl overflow-hidden shadow-2xl">
            
            <!-- Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-zinc-800">
                <p id="imageModalTitle" class="text-sm font-semibold text-gray-900 dark:text-white truncate"></p>
                <button onclick="closeImageModal()"
                        class="p-1 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <!-- Image -->
            <div class="relative bg-gray-100 dark:bg-zinc-800 flex items-center justify-center min-h-64">
                <img id="imageModalImg"
                     src=""
                     alt=""
                     class="max-h-[70vh] max-w-full object-contain">
                <!-- Placeholder kalau no image -->
                <div id="imageModalPlaceholder" class="hidden flex-col items-center justify-center py-16 gap-3">
                    <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-6xl">image_not_supported</span>
                    <p class="text-sm text-gray-400 dark:text-zinc-500">Tidak ada gambar</p>
                </div>
            </div>

        </div>
    </div>
</div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.3s ease-out; }

    .sort-btn.active {
        background-color: rgb(74 222 128 / 0.15);
        border-color: rgb(74 222 128 / 0.4);
        color: #16a34a;
    }
    .dark .sort-btn.active { color: #4ade80; }

    .product-row.selected {
        background-color: rgb(59 130 246 / 0.05);
    }
    .dark .product-row.selected {
        background-color: rgb(59 130 246 / 0.08);
    }

    #imageModal.show {
    display: flex;
    animation: fade-in 0.2s ease-out;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Elemen Filter ────────────────────────────────────────
    const searchInput         = document.getElementById('searchInput');
    const categoryFilter      = document.getElementById('categoryFilter');
    const statusFilter        = document.getElementById('statusFilter');
    const stockFilter         = document.getElementById('stockFilter');
    const priceMin            = document.getElementById('priceMin');
    const priceMax            = document.getElementById('priceMax');
    const resetBtn            = document.getElementById('resetBtn');
    const tableBody           = document.getElementById('productsTableBody');
    const loadingIndicator    = document.getElementById('loadingIndicator');
    const paginationContainer = document.getElementById('paginationContainer');
    const perPageSelect       = document.getElementById('perPageSelect');

    // ── Elemen Bulk ──────────────────────────────────────────
    const checkAll   = document.getElementById('checkAll');
    const bulkToolbar = document.getElementById('bulkToolbar');
    const bulkCount  = document.getElementById('bulkCount');

    // ── State ────────────────────────────────────────────────
    let currentSort  = { by: 'created_at', dir: 'desc' };
    let searchTimeout, priceTimeout;

    // ── Auto dismiss alerts ──────────────────────────────────
    document.querySelectorAll('.animate-fade-in').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s, transform 0.3s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // ── Helper: cek filter aktif ─────────────────────────────
    function hasActiveFilter() {
        return searchInput.value || categoryFilter.value ||
               statusFilter.value || stockFilter.value ||
               priceMin.value || priceMax.value;
    }

    function updateResetButton() {
        if (hasActiveFilter()) {
            resetBtn.classList.remove('hidden');
            resetBtn.classList.add('flex');
        } else {
            resetBtn.classList.add('hidden');
            resetBtn.classList.remove('flex');
        }
    }

    // ── Fetch produk ─────────────────────────────────────────
    function fetchProducts(page = 1) {
        loadingIndicator.classList.remove('hidden');
        clearSelection();

        const params = new URLSearchParams();
        if (searchInput.value)    params.append('search',       searchInput.value);
        if (categoryFilter.value) params.append('category',     categoryFilter.value);
        if (statusFilter.value)   params.append('status',       statusFilter.value);
        if (stockFilter.value)    params.append('stock_filter', stockFilter.value);
        if (priceMin.value)       params.append('price_min',    priceMin.value);
        if (priceMax.value)       params.append('price_max',    priceMax.value);
        params.append('sort_by',  currentSort.by);
        params.append('sort_dir', currentSort.dir);
        params.append('per_page', perPageSelect.value);
        params.append('page',     page);

        fetch(`{{ route('admin.products.index') }}?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            tableBody.innerHTML           = data.html;
            paginationContainer.innerHTML = data.pagination;

            document.getElementById('stat-total').textContent     = data.stats.total;
            document.getElementById('stat-active').textContent    = data.stats.active;
            document.getElementById('stat-low-stock').textContent = data.stats.low_stock;
            document.getElementById('stat-inactive').textContent  = data.stats.inactive;

            loadingIndicator.classList.add('hidden');
            attachPaginationHandlers();
            attachCheckboxHandlers();
            checkAll.checked = false;
        })
        .catch(() => loadingIndicator.classList.add('hidden'));
    }

    // ── Pagination ───────────────────────────────────────────
    function attachPaginationHandlers() {
        paginationContainer.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const page = new URL(this.href).searchParams.get('page');
                if (page) fetchProducts(page);
            });
        });
    }

    // ── Sort ─────────────────────────────────────────────────
    document.querySelectorAll('.sort-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const col = this.dataset.sort;
            currentSort.dir = currentSort.by === col
                ? (currentSort.dir === 'asc' ? 'desc' : 'asc')
                : 'asc';
            currentSort.by = col;

            document.querySelectorAll('.sort-btn').forEach(b => {
                b.classList.remove('active');
                b.querySelector('.sort-icon').textContent = '↕';
            });
            this.classList.add('active');
            this.querySelector('.sort-icon').textContent = currentSort.dir === 'asc' ? '↑' : '↓';
            fetchProducts();
        });
    });

    // ── Event listeners filter ────────────────────────────────
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        updateResetButton();
        searchTimeout = setTimeout(() => fetchProducts(), 350);
    });

    [categoryFilter, statusFilter, stockFilter].forEach(el => {
        el.addEventListener('change', function () {
            updateResetButton();
            fetchProducts();
        });
    });

    [priceMin, priceMax].forEach(input => {
        input.addEventListener('input', function () {
            updateResetButton();
            clearTimeout(priceTimeout);
            priceTimeout = setTimeout(() => fetchProducts(), 600);
        });
    });

    perPageSelect.addEventListener('change', function () {
    fetchProducts(1);
    });

    resetBtn.addEventListener('click', function () {
        searchInput.value    = '';
        categoryFilter.value = '';
        statusFilter.value   = '';
        stockFilter.value    = '';
        priceMin.value       = '';
        priceMax.value       = '';
         perPageSelect.value  = '10';
        currentSort          = { by: 'created_at', dir: 'desc' };
        document.querySelectorAll('.sort-btn').forEach(b => {
            b.classList.remove('active');
            b.querySelector('.sort-icon').textContent = '↕';
        });
        updateResetButton();
        fetchProducts();
    });

    // ── BULK ACTION ──────────────────────────────────────────

    // Ambil semua ID yang tercentang
    window.getSelectedIds = function () {
        return [...document.querySelectorAll('.product-checkbox:checked')]
            .map(cb => cb.value);
    };

    // Update toolbar bulk
    window.updateBulkToolbar = function () {
        const ids = getSelectedIds();
        if (ids.length > 0) {
            bulkToolbar.classList.remove('hidden');
            bulkToolbar.classList.add('flex');
            bulkCount.textContent = `${ids.length} produk dipilih`;
        } else {
            bulkToolbar.classList.add('hidden');
            bulkToolbar.classList.remove('flex');
        }
        // Sinkron checkAll
        const all = document.querySelectorAll('.product-checkbox');
        checkAll.checked        = all.length > 0 && ids.length === all.length;
        checkAll.indeterminate  = ids.length > 0 && ids.length < all.length;
    };

    // Clear semua selection
    window.clearSelection = function () {
        document.querySelectorAll('.product-checkbox').forEach(cb => {
            cb.checked = false;
            cb.closest('.product-row')?.classList.remove('selected');
        });
        checkAll.checked       = false;
        checkAll.indeterminate = false;
        updateBulkToolbar();
    };

    // Attach handler checkbox (dipanggil ulang setiap fetch)
    function attachCheckboxHandlers() {
        document.querySelectorAll('.product-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                this.closest('.product-row')?.classList.toggle('selected', this.checked);
                updateBulkToolbar();
            });
        });
    }

    // Check All
    checkAll.addEventListener('change', function () {
        document.querySelectorAll('.product-checkbox').forEach(cb => {
            cb.checked = this.checked;
            cb.closest('.product-row')?.classList.toggle('selected', this.checked);
        });
        updateBulkToolbar();
    });

    // Init handler pertama kali
    attachPaginationHandlers();
    attachCheckboxHandlers();

    // ── Bulk Delete ───────────────────────────────────────────
    window.bulkDelete = function () {
        const ids = getSelectedIds();
        if (!ids.length) return;

        Swal.fire({
            title: 'Hapus Produk Terpilih?',
            text: `${ids.length} produk akan dihapus permanen dan tidak dapat dikembalikan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal',
        }).then(result => {
            if (!result.isConfirmed) return;

            fetch('{{ route('admin.products.bulk-delete') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ ids }),
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: data.message,
                        toast: true,
                        position: 'top-end',
                        timer: 2500,
                        showConfirmButton: false,
                        timerProgressBar: true,
                    });
                    fetchProducts();
                }
            });
        });
    };

    // ── Bulk Aktifkan ────────────────────────────────────────
    window.bulkActivate = function () {
        const ids = getSelectedIds();
        if (!ids.length) return;

        Swal.fire({
            title: 'Aktifkan Produk Terpilih?',
            text: `${ids.length} produk akan diaktifkan.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Aktifkan!',
            cancelButtonText: 'Batal',
        }).then(result => {
            if (!result.isConfirmed) return;
            sendBulkStatus(ids, 1);
        });
    };

    // ── Bulk Nonaktifkan ─────────────────────────────────────
    window.bulkDeactivate = function () {
        const ids = getSelectedIds();
        if (!ids.length) return;

        Swal.fire({
            title: 'Nonaktifkan Produk Terpilih?',
            text: `${ids.length} produk akan dinonaktifkan.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6b7280',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Nonaktifkan!',
            cancelButtonText: 'Batal',
        }).then(result => {
            if (!result.isConfirmed) return;
            sendBulkStatus(ids, 0);
        });
    };

    function sendBulkStatus(ids, status) {
        fetch('{{ route('admin.products.bulk-status') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ ids, status }),
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: data.message,
                    toast: true,
                    position: 'top-end',
                    timer: 2500,
                    showConfirmButton: false,
                    timerProgressBar: true,
                });
                fetchProducts();
            }
        });
    }
});

// ── Konfirmasi Hapus Single ───────────────────────────────────
function confirmDelete(form, productName) {
    Swal.fire({
        title: 'Hapus Produk?',
        text: `Produk "${productName}" akan dihapus permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
    }).then(result => {
        if (result.isConfirmed) form.submit();
    });
}

// ── Preview Gambar Modal ──────────────────────────────────────
function openImageModal(src, name) {
    const modal       = document.getElementById('imageModal');
    const img         = document.getElementById('imageModalImg');
    const title       = document.getElementById('imageModalTitle');
    const placeholder = document.getElementById('imageModalPlaceholder');

    title.textContent = name;

    if (src) {
        img.src = src;
        img.classList.remove('hidden');
        placeholder.classList.add('hidden');
        placeholder.classList.remove('flex');
    } else {
        img.classList.add('hidden');
        placeholder.classList.remove('hidden');
        placeholder.classList.add('flex');
    }

    modal.classList.remove('hidden');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('show');
    document.body.style.overflow = '';
}

// Tutup modal dengan tombol ESC
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeImageModal();
});
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ session("success") }}',
        toast: true,
        position: 'top-end',
        timer: 2500,
        showConfirmButton: false,
        timerProgressBar: true,
    });
</script>
@endif

@endsection