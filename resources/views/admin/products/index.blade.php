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
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <!-- Search Input -->
            <div class="flex-1">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500">search</span>
                    <input type="text" 
                           id="searchInput"
                           placeholder="Cari nama produk..."
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                </div>
            </div>

            <!-- Category Filter -->
            <div class="w-full sm:w-64">
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

            <!-- Reset Button -->
            <button id="resetBtn" 
                    class="hidden items-center justify-center gap-2 px-6 py-2.5 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-gray-700 dark:text-zinc-300 font-medium rounded-lg transition-colors">
                <span class="material-symbols-outlined text-lg">close</span>
                Reset
            </button>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden mt-3 flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400">
            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memuat data...
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-zinc-800/50 border-b border-gray-200 dark:border-zinc-800">
                    <tr>
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
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors" data-product-id="{{ $product->id }}">
                            <!-- Image -->
                            <td class="px-6 py-4">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}"
                                         class="h-14 w-14 object-cover rounded-lg border border-gray-200 dark:border-zinc-700">
                                @else
                                    <div class="h-14 w-14 bg-gray-200 dark:bg-zinc-700 rounded-lg flex items-center justify-center border border-gray-300 dark:border-zinc-600">
                                        <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-xl">image</span>
                                    </div>
                                @endif
                            </td>
                            
                            <!-- Name -->
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">{{ Str::limit($product->description, 50) }}</p>
                            </td>
                            
                            <!-- Category -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400 text-xs font-medium rounded-full">
                                    <span class="material-symbols-outlined text-sm">category</span>
                                    {{ $product->category->name ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            
                            <!-- Price -->
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-gray-900 dark:text-white">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400">per {{ $product->unit }}</p>
                            </td>
                            
                            <!-- Stock -->
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg font-semibold text-sm
                                    {{ $product->stock <= 5 ? 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400' : 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400' }}">
                                    @if($product->stock <= 5)
                                        <span class="material-symbols-outlined text-base">warning</span>
                                    @endif
                                    {{ $product->stock }}
                                </span>
                            </td>
                            
                            <!-- Status -->
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full
                                    {{ $product->is_active ? 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-500/20 text-gray-700 dark:text-gray-400' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $product->is_active ? 'bg-green-500' : 'bg-gray-500' }}"></span>
                                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            
                            <!-- Actions -->
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
                                        <button type="submit" 
                                                onclick="return confirm('Yakin ingin menghapus produk {{ $product->name }}?')" 
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
                            <td colspan="7" class="px-6 py-12 text-center">
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

</div>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const resetBtn = document.getElementById('resetBtn');
    const tableBody = document.getElementById('productsTableBody');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const paginationContainer = document.getElementById('paginationContainer');
    
    let searchTimeout;
    
    // Auto dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.animate-fade-in');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
    
    // Show/hide reset button
    function updateResetButton() {
        if (searchInput.value || categoryFilter.value) {
            resetBtn.classList.remove('hidden');
            resetBtn.classList.add('flex');
        } else {
            resetBtn.classList.add('hidden');
            resetBtn.classList.remove('flex');
        }
    }
    
    // Fetch products with filters
    function fetchProducts(page = 1) {
        const search = searchInput.value;
        const category = categoryFilter.value;
        
        // Show loading
        loadingIndicator.classList.remove('hidden');
        
        // Build query string
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (category) params.append('category', category);
        params.append('page', page);
        
        // Fetch data
        fetch(`{{ route('admin.products.index') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update table body
            tableBody.innerHTML = data.html;
            
            // Update pagination
            paginationContainer.innerHTML = data.pagination;
            
            // Update stats
            document.getElementById('stat-total').textContent = data.stats.total;
            document.getElementById('stat-active').textContent = data.stats.active;
            document.getElementById('stat-low-stock').textContent = data.stats.low_stock;
            document.getElementById('stat-inactive').textContent = data.stats.inactive;
            
            // Hide loading
            loadingIndicator.classList.add('hidden');
            
            // Re-attach pagination click handlers
            attachPaginationHandlers();
        })
        .catch(error => {
            console.error('Error:', error);
            loadingIndicator.classList.add('hidden');
        });
    }
    
    // Attach pagination handlers
    function attachPaginationHandlers() {
        const paginationLinks = paginationContainer.querySelectorAll('a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                const page = url.searchParams.get('page');
                if (page) {
                    fetchProducts(page);
                }
            });
        });
    }
    
    // Live search with debounce
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        updateResetButton();
        
        searchTimeout = setTimeout(() => {
            fetchProducts();
        }, 300); // 300ms delay
    });
    
    // Category filter change
    categoryFilter.addEventListener('change', function() {
        updateResetButton();
        fetchProducts();
    });
    
    // Reset button
    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        categoryFilter.value = '';
        updateResetButton();
        fetchProducts();
    });
    
    // Initial pagination handlers
    attachPaginationHandlers();
});

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ session("success") }}',
        toast: true,
        position: 'top-end',
        timer: 2000,
        showConfirmButton: false,
    });
</script>
@endif
</script>
@endsection