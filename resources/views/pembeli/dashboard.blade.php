{{-- resources/views/pembeli/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - Tunas Sejahtera Adi Perkasa')

@section('content')

<!-- Hero Banner -->
<section class="mt-6 @container">
    <div class="relative overflow-hidden rounded-xl bg-primary/10 min-h-[400px] flex items-center">
        <div class="absolute inset-0 z-0">
            {{-- Ganti dengan image yang sesuai atau gunakan gradient --}}
            <img class="w-full h-full object-cover opacity-80 object-right" 
                 src="https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=1200" 
                 alt="Hero Banner">
            <div class="absolute inset-0 bg-gradient-to-r from-[#102218]/90 via-[#102218]/40 to-transparent"></div>
        </div>
        
        <div class="relative z-10 p-8 md:p-16 max-w-2xl">
            <span class="inline-block px-3 py-1 bg-primary text-[#0d1b13] text-xs font-bold rounded-full mb-4">
                Pusat Ternak Terbesar
            </span>
            <h1 class="text-white text-4xl md:text-6xl font-black leading-tight tracking-tight mb-4">
                Selamat Datang, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="text-gray-200 text-base md:text-lg mb-8 max-w-md">
                Temukan berbagai jenis hewan ternak berkualitas tinggi untuk kebutuhan Anda
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('pembeli.produk.index') }}" 
                   class="bg-primary hover:bg-primary/90 text-[#0d1b13] px-8 py-3 rounded-lg font-bold transition-all shadow-lg shadow-primary/20 inline-flex items-center gap-2">
                    <span class="material-symbols-outlined">storefront</span>
                    Lihat Semua Hewan
                </a>
                <a href="{{ route('pembeli.pesanan.index') }}" 
                   class="bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white border border-white/30 px-8 py-3 rounded-lg font-bold transition-all inline-flex items-center gap-2">
                    <span class="material-symbols-outlined">receipt_long</span>
                    Pesanan Saya
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Kategori Hewan -->
<section class="mt-12">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-[#0d1b13] dark:text-white text-2xl font-bold tracking-tight">Kategori Hewan</h2>
        <a href="{{ route('pembeli.produk.index') }}" class="text-primary font-bold text-sm hover:underline inline-flex items-center gap-1">
            Lihat Semua
            <span class="material-symbols-outlined text-base">arrow_forward</span>
        </a>
    </div>
    
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        {{-- Sapi --}}
        <a href="{{ route('pembeli.produk.index') }}?search=sapi" 
           class="group cursor-pointer bg-white dark:bg-background-dark border border-[#cfe7d9] dark:border-primary/20 p-4 rounded-xl flex flex-col items-center transition-all hover:shadow-xl hover:shadow-primary/10 hover:border-primary">
            <div class="size-16 rounded-full bg-primary/10 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-4xl text-primary">pets</span>
            </div>
            <span class="font-bold text-sm text-[#0d1b13] dark:text-white">Sapi</span>
        </a>

        {{-- Kambing --}}
        <a href="{{ route('pembeli.produk.index') }}?search=kambing" 
           class="group cursor-pointer bg-white dark:bg-background-dark border border-[#cfe7d9] dark:border-primary/20 p-4 rounded-xl flex flex-col items-center transition-all hover:shadow-xl hover:shadow-primary/10 hover:border-primary">
            <div class="size-16 rounded-full bg-primary/10 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-4xl text-primary">cruelty_free</span>
            </div>
            <span class="font-bold text-sm text-[#0d1b13] dark:text-white">Kambing</span>
        </a>

        {{-- Domba --}}
        <a href="{{ route('pembeli.produk.index') }}?search=domba" 
           class="group cursor-pointer bg-white dark:bg-background-dark border border-[#cfe7d9] dark:border-primary/20 p-4 rounded-xl flex flex-col items-center transition-all hover:shadow-xl hover:shadow-primary/10 hover:border-primary">
            <div class="size-16 rounded-full bg-primary/10 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-4xl text-primary">eco</span>
            </div>
            <span class="font-bold text-sm text-[#0d1b13] dark:text-white">Domba</span>
        </a>

        {{-- Ayam --}}
        <a href="{{ route('pembeli.produk.index') }}?search=ayam" 
           class="group cursor-pointer bg-white dark:bg-background-dark border border-[#cfe7d9] dark:border-primary/20 p-4 rounded-xl flex flex-col items-center transition-all hover:shadow-xl hover:shadow-primary/10 hover:border-primary">
            <div class="size-16 rounded-full bg-primary/10 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-4xl text-primary">flutter_dash</span>
            </div>
            <span class="font-bold text-sm text-[#0d1b13] dark:text-white">Ayam</span>
        </a>

        {{-- Bebek --}}
        <a href="{{ route('pembeli.produk.index') }}?search=bebek" 
           class="group cursor-pointer bg-white dark:bg-background-dark border border-[#cfe7d9] dark:border-primary/20 p-4 rounded-xl flex flex-col items-center transition-all hover:shadow-xl hover:shadow-primary/10 hover:border-primary">
            <div class="size-16 rounded-full bg-primary/10 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-4xl text-primary">pest_control_rodent</span>
            </div>
            <span class="font-bold text-sm text-[#0d1b13] dark:text-white">Bebek</span>
        </a>

        {{-- Pakan & Alkes --}}
        <a href="{{ route('pembeli.produk.index') }}?search=pakan" 
           class="group cursor-pointer bg-white dark:bg-background-dark border border-[#cfe7d9] dark:border-primary/20 p-4 rounded-xl flex flex-col items-center transition-all hover:shadow-xl hover:shadow-primary/10 hover:border-primary">
            <div class="size-16 rounded-full bg-primary/10 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-4xl text-primary">medical_services</span>
            </div>
            <span class="font-bold text-sm text-[#0d1b13] dark:text-white">Alat & Pakan</span>
        </a>
    </div>
</section>

<!-- Rekomendasi Hewan -->
<section class="mt-16">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">verified</span>
            <h2 class="text-[#0d1b13] dark:text-white text-2xl font-bold tracking-tight">Rekomendasi Hewan</h2>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($topProducts->take(4) as $index => $product)
            <div class="group bg-white dark:bg-background-dark rounded-xl overflow-hidden border border-[#cfe7d9] dark:border-primary/20 shadow-sm transition-all hover:shadow-lg">
                <div class="relative h-48 overflow-hidden">
                    @if($product->image)
                        <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" 
                             src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}">
                    @else
                        <div class="w-full h-full bg-gray-200 dark:bg-zinc-800 flex items-center justify-center">
                            <span class="material-symbols-outlined text-gray-400 text-5xl">image</span>
                        </div>
                    @endif
                    
                    @if($index === 0)
                        <div class="absolute top-2 left-2 px-2 py-1 bg-primary text-[#0d1b13] text-[10px] font-bold rounded uppercase">
                            Best Seller
                        </div>
                    @elseif($index === 1)
                        <div class="absolute top-2 left-2 px-2 py-1 bg-yellow-400 text-[#0d1b13] text-[10px] font-bold rounded uppercase">
                            Promo
                        </div>
                    @endif
                </div>
                
                <div class="p-4">
                    <h3 class="font-bold text-[#0d1b13] dark:text-white mb-1">{{ Str::limit($product->name, 30) }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">category</span>
                        {{ $product->category->name ?? 'Uncategorized' }}
                    </p>
                    <div class="flex items-baseline gap-1 mb-4">
                        <span class="text-primary font-extrabold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('pembeli.produk.show', $product->slug) }}" 
                       class="block w-full bg-primary/10 hover:bg-primary hover:text-white text-[#0d1b13] font-bold py-2 rounded-lg transition-colors text-sm text-center">
                        Detail Hewan
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center py-12 bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20">
                <span class="material-symbols-outlined text-gray-300 text-6xl mb-3">inventory_2</span>
                <p class="text-gray-500 dark:text-gray-400">Belum ada produk populer</p>
            </div>
        @endforelse
    </div>
</section>

<!-- Semua Hewan Section -->
<section class="mt-16">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-[#0d1b13] dark:text-white text-2xl font-bold tracking-tight">Semua Hewan</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Menampilkan <span id="product-count">{{ $allProducts->count() }}</span> hasil untuk Anda</p>
        </div>
        
        <div class="flex items-center gap-3 flex-wrap">
            <!-- Filter Kategori -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2" id="categoryFilters">
                <button onclick="filterProducts('')" 
                        class="filter-btn px-4 py-2 border-2 border-primary rounded-lg text-sm font-medium transition-all whitespace-nowrap bg-primary text-white shadow-sm">
                    Semua
                </button>
                @foreach($categories as $category)
                    <button onclick="filterProducts('{{ $category->id }}')" 
                            class="filter-btn px-4 py-2 border-2 border-gray-300 dark:border-zinc-700 rounded-lg text-sm font-medium hover:border-primary hover:bg-primary/10 transition-all whitespace-nowrap text-[#0d1b13] dark:text-white bg-white dark:bg-zinc-900">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
            
            <!-- Sort Dropdown -->
            <div class="relative">
                <select id="sortSelect" onchange="sortProducts(this.value)" 
                        class="appearance-none px-4 py-2 pr-10 border-2 border-gray-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-900 text-sm font-medium text-[#0d1b13] dark:text-white cursor-pointer hover:border-primary transition-all">
                    <option value="newest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="cheapest">Termurah</option>
                    <option value="expensive">Termahal</option>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-lg">
                    expand_more
                </span>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div id="productsGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-8 md:gap-x-6">
        @forelse($allProducts as $product)
            <div class="group cursor-pointer product-item" 
                 data-category="{{ $product->category_id }}" 
                 data-price="{{ $product->price }}" 
                 data-date="{{ $product->created_at->timestamp }}">
                
                <!-- Product Image -->
                <div class="relative aspect-square rounded-xl overflow-hidden mb-3 bg-gray-100 dark:bg-background-dark border border-[#cfe7d9] dark:border-primary/20">
                    <a href="{{ route('pembeli.produk.show', $product->slug) }}">
                        @if($product->image)
                            <img class="w-full h-full object-cover transition-transform duration-300" 
                                 src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}"
                                 loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-gray-300 dark:text-gray-600 text-4xl">image</span>
                            </div>
                        @endif
                    </a>

                    <!-- Stock Badge -->
                    @if($product->stock <= 5 && $product->stock > 0)
                        <div class="absolute bottom-2 right-2 bg-white/90 dark:bg-black/70 px-2 py-1 rounded text-[10px] font-bold text-yellow-600">
                            Stok {{ $product->stock }}
                        </div>
                    @elseif($product->stock == 0)
                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center backdrop-blur-sm">
                            <span class="px-2.5 py-1 bg-red-500 text-white text-xs font-bold rounded shadow-lg">
                                Stok Habis
                            </span>
                        </div>
                    @endif

                    <!-- Category Badge -->
                    @if($product->category)
                        <div class="absolute top-2 left-2 px-2 py-1 bg-primary/90 backdrop-blur-sm text-white text-[10px] font-bold rounded">
                            {{ Str::limit($product->category->name, 15) }}
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <a href="{{ route('pembeli.produk.show', $product->slug) }}">
                    <h4 class="font-bold text-sm text-[#0d1b13] dark:text-white line-clamp-2 mb-1 group-hover:text-primary transition-colors">
                        {{ $product->name }}
                    </h4>
                </a>

                <p class="text-primary font-bold text-sm mb-2">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>

                <div class="flex items-center gap-1 text-[10px] text-gray-400 dark:text-gray-500 mb-3">
                    <span class="material-symbols-outlined text-xs">store</span>
                    <span>{{ $product->unit ?? 'ekor' }}</span>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    @if($product->stock > 0)
                        <button onclick="addToCart({{ $product->id }})"
                                class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-primary text-white rounded-lg text-xs font-medium transition-all hover:bg-primary/90">
                            <span class="material-symbols-outlined text-sm">add_shopping_cart</span>
                            <span class="hidden sm:inline">Keranjang</span>
                        </button>
                    @else
                        <button disabled
                                class="flex-1 px-3 py-2 bg-gray-200 dark:bg-zinc-800 text-gray-400 dark:text-gray-500 rounded-lg text-xs font-medium cursor-not-allowed">
                            Habis
                        </button>
                    @endif
                    
                    <a href="{{ route('pembeli.produk.show', $product->slug) }}"
                       class="flex items-center justify-center px-3 py-2 bg-gray-100 dark:bg-zinc-800 text-[#0d1b13] dark:text-white hover:bg-gray-200 dark:hover:bg-zinc-700 rounded-lg transition-all">
                        <span class="material-symbols-outlined text-sm">visibility</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center py-12 bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20">
                <span class="material-symbols-outlined text-gray-300 text-6xl mb-3">inventory_2</span>
                <p class="text-gray-500 dark:text-gray-400">Belum ada produk tersedia</p>
            </div>
        @endforelse
    </div>
</section>

<!-- Recent Orders -->
@if($recentOrders->count() > 0)
<section class="mt-16">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-[#0d1b13] dark:text-white text-2xl font-bold tracking-tight">Pesanan Terbaru</h2>
        <a href="{{ route('pembeli.pesanan.index') }}" class="text-primary font-bold text-sm hover:underline inline-flex items-center gap-1">
            Lihat Semua
            <span class="material-symbols-outlined text-base">arrow_forward</span>
        </a>
    </div>

    <div class="bg-white dark:bg-background-dark rounded-xl border border-[#cfe7d9] dark:border-primary/20 shadow-sm p-6">
        <div class="space-y-4">
            @foreach($recentOrders as $order)
                <div class="p-4 bg-gray-50 dark:bg-primary/5 rounded-lg hover:bg-gray-100 dark:hover:bg-primary/10 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-sm font-semibold text-[#0d1b13] dark:text-white">
                                    #{{ $order->order_number }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded-full
                                    @if($order->status === 'pending') bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400
                                    @elseif($order->status === 'paid') bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400
                                    @elseif($order->status === 'processing') bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400
                                    @elseif($order->status === 'shipped') bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400
                                    @elseif($order->status === 'completed') bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400
                                    @else bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400
                                    @endif">
                                    <span class="w-1.5 h-1.5 rounded-full
                                        @if($order->status === 'pending') bg-yellow-500
                                        @elseif($order->status === 'paid') bg-blue-500
                                        @elseif($order->status === 'processing') bg-purple-500
                                        @elseif($order->status === 'shipped') bg-indigo-500
                                        @elseif($order->status === 'completed') bg-green-500
                                        @else bg-red-500
                                        @endif"></span>
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $order->items->count() }} item • 
                                <span class="font-semibold text-[#0d1b13] dark:text-white">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                {{ $order->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <a href="{{ route('pembeli.pesanan.show', $order->id) }}" 
                           class="flex-shrink-0 px-3 py-1.5 bg-primary/10 text-primary hover:bg-primary hover:text-white rounded-lg text-xs font-medium transition-colors inline-flex items-center gap-1">
                            <span>Detail</span>
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
// Filter & Sort Products
let allProducts = document.querySelectorAll('.product-item');
let currentCategory = '';
let currentSort = 'newest';

function filterProducts(categoryId) {
    currentCategory = categoryId;
    
    // Update button styles
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('bg-primary', 'text-white', 'border-primary', 'shadow-sm');
        btn.classList.add('bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white', 'border-gray-300', 'dark:border-zinc-700');
    });
    
    event.target.classList.remove('bg-white', 'dark:bg-zinc-900', 'text-[#0d1b13]', 'dark:text-white', 'border-gray-300', 'dark:border-zinc-700');
    event.target.classList.add('bg-primary', 'text-white', 'border-primary', 'shadow-sm');
    
    applyFilters();
}

function sortProducts(sortType) {
    currentSort = sortType;
    applyFilters();
}

function applyFilters() {
    let visibleProducts = Array.from(allProducts);
    
    // Filter by category
    if (currentCategory) {
        visibleProducts = visibleProducts.filter(product => 
            product.dataset.category == currentCategory
        );
    }
    
    // Sort products
    visibleProducts.sort((a, b) => {
        switch(currentSort) {
            case 'newest':
                return b.dataset.date - a.dataset.date;
            case 'oldest':
                return a.dataset.date - b.dataset.date;
            case 'cheapest':
                return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
            case 'expensive':
                return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
            default:
                return 0;
        }
    });
    
    // Hide all products first
    allProducts.forEach(product => {
        product.style.display = 'none';
    });
    
    // Show and reorder visible products
    const grid = document.getElementById('productsGrid');
    visibleProducts.forEach(product => {
        product.style.display = 'block';
        grid.appendChild(product);
    });
    
    // Update count
    document.getElementById('product-count').textContent = visibleProducts.length;
}

// Add to Cart Function
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
            if (typeof updateCartCount === 'function') {
                updateCartCount(data.cart_count);
            }
        } else {
            showToast(data.message || 'Gagal menambahkan produk ke keranjang', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        showToast('Terjadi kesalahan saat menambahkan ke keranjang', 'error');
    });
}

// Toast Notification
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
</script>

<style>
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
</style>
@endpush