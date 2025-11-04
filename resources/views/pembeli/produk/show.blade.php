{{-- resources/views/pembeli/produk/show.blade.php --}}
@extends('layouts.app')

@section('title', $product->name . ' - Lembah Hijau')

@section('content')
<div class="space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400">
        <a href="{{ route('pembeli.dashboard') }}" class="hover:text-soft-green transition-colors">Beranda</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <a href="{{ route('pembeli.produk.index') }}" class="hover:text-soft-green transition-colors">Produk</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <a href="{{ route('pembeli.produk.index', ['category' => $product->category_id]) }}" class="hover:text-soft-green transition-colors">
            {{ $product->category->name ?? 'Uncategorized' }}
        </a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-gray-900 dark:text-white font-medium">{{ Str::limit($product->name, 30) }}</span>
    </nav>

    <!-- Product Detail Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6 lg:p-8">
            
            <!-- Left: Product Images -->
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="relative aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover"
                             id="mainImage">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-9xl">image</span>
                        </div>
                    @endif

                    <!-- Stock Badge Overlay -->
                    @if($product->stock > 0 && $product->stock <= 5)
                        <div class="absolute top-4 right-4 px-3 py-1.5 bg-yellow-500 text-white text-sm font-bold rounded-full shadow-lg">
                            <span class="material-symbols-outlined text-sm align-middle">warning</span>
                            Stok {{ $product->stock }}
                        </div>
                    @elseif($product->stock == 0)
                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                            <div class="text-center">
                                <span class="material-symbols-outlined text-white text-6xl mb-2">block</span>
                                <p class="text-white text-xl font-bold">Stok Habis</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Certificate Download -->
                @if($product->health_certificate)
                    <div class="p-4 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-500/10 dark:to-cyan-500/10 border border-blue-200 dark:border-blue-500/20 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-2xl">verified</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Sertifikat Kesehatan</h4>
                                <p class="text-xs text-gray-600 dark:text-zinc-400">Produk bersertifikat resmi</p>
                            </div>
                            <a href="{{ asset('storage/' . $product->health_certificate) }}" 
                               target="_blank"
                               class="flex items-center gap-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                                <span class="material-symbols-outlined text-base">download</span>
                                Download
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right: Product Info -->
            <div class="space-y-6">
                <!-- Category Badge -->
                <div>
                    <a href="{{ route('pembeli.produk.index', ['category' => $product->category_id]) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400 hover:bg-purple-200 dark:hover:bg-purple-500/30 rounded-full text-sm font-medium transition-colors">
                        <span class="material-symbols-outlined text-sm">category</span>
                        {{ $product->category->name ?? 'Uncategorized' }}
                    </a>
                </div>

                <!-- Product Name & Price -->
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white font-be-vietnam mb-3">
                        {{ $product->name }}
                    </h1>
                    <div class="flex items-end gap-3">
                        <p class="text-4xl font-bold text-soft-green dark:text-soft-green">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- Stock Info -->
                <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-600 dark:text-zinc-400">inventory</span>
                        <span class="text-sm text-gray-600 dark:text-zinc-400">Stok:</span>
                        <span class="text-sm font-bold {{ $product->stock > 5 ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                            {{ $product->stock }} tersedia
                        </span>
                    </div>
                    @if($product->available_from)
                        <div class="flex items-center gap-2 pl-4 border-l border-gray-200 dark:border-zinc-700">
                            <span class="material-symbols-outlined text-gray-600 dark:text-zinc-400">schedule</span>
                            <span class="text-sm text-gray-600 dark:text-zinc-400">
                                Tersedia: {{ \Carbon\Carbon::parse($product->available_from)->format('d M Y') }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Description -->
                <div class="space-y-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-soft-green">description</span>
                        Deskripsi Produk
                    </h3>
                    <div class="p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg">
                        <p class="text-gray-700 dark:text-zinc-300 leading-relaxed whitespace-pre-line">
                            {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                        </p>
                    </div>
                </div>

                <!-- Quantity Selector (if in stock) -->
                @if($product->stock > 0)
                    <div class="space-y-3">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Jumlah</h3>
                        <div class="flex items-center gap-3">
                            <button onclick="decreaseQuantity()" 
                                    class="w-10 h-10 flex items-center justify-center bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 rounded-lg transition-colors">
                                <span class="material-symbols-outlined">remove</span>
                            </button>
                            <input type="number" 
                                   id="quantity" 
                                   value="1" 
                                   min="1" 
                                   max="{{ $product->stock }}"
                                   class="w-20 text-center px-4 py-2 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white font-semibold focus:ring-2 focus:ring-soft-green focus:border-soft-green">
                            <button onclick="increaseQuantity()" 
                                    class="w-10 h-10 flex items-center justify-center bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 rounded-lg transition-colors">
                                <span class="material-symbols-outlined">add</span>
                            </button>
                            <span class="text-sm text-gray-600 dark:text-zinc-400">Max: {{ $product->stock }}</span>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="space-y-3 pt-4 border-t border-gray-200 dark:border-zinc-800">
                    @if($product->stock > 0)
                        <button onclick="addToCart({{ $product->id }})"
                                class="w-full flex items-center justify-center gap-2 px-6 py-4 bg-gradient-to-r from-soft-green to-primary text-white font-semibold rounded-xl hover:shadow-xl hover:scale-[1.02] transition-all">
                            <span class="material-symbols-outlined text-xl">shopping_cart</span>
                            Tambah ke Keranjang
                        </button>
                        <button onclick="buyNow({{ $product->id }})"
                                class="w-full flex items-center justify-center gap-2 px-6 py-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-semibold rounded-xl hover:bg-gray-800 dark:hover:bg-gray-100 transition-colors">
                            <span class="material-symbols-outlined text-xl">bolt</span>
                            Beli Sekarang
                        </button>
                    @else
                        <button disabled
                                class="w-full flex items-center justify-center gap-2 px-6 py-4 bg-gray-200 dark:bg-zinc-800 text-gray-400 dark:text-zinc-500 font-semibold rounded-xl cursor-not-allowed">
                            <span class="material-symbols-outlined text-xl">block</span>
                            Stok Habis
                        </button>
                    @endif

                    <!-- Back to Products -->
                    <a href="{{ route('pembeli.produk.index') }}"
                       class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 font-medium rounded-xl hover:bg-gray-200 dark:hover:bg-zinc-700 transition-colors">
                        <span class="material-symbols-outlined">arrow_back</span>
                        Kembali ke Katalog
                    </a>
                </div>

                <!-- Product Info Cards -->
                <div class="grid grid-cols-2 gap-3 pt-4">
                    <div class="p-3 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-500/10 dark:to-emerald-500/10 rounded-lg border border-green-200 dark:border-green-500/20">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-lg">verified</span>
                            <span class="text-xs font-medium text-green-900 dark:text-green-300">Kualitas Terjamin</span>
                        </div>
                        <p class="text-xs text-green-700 dark:text-green-400">Produk berkualitas premium</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-500/10 dark:to-cyan-500/10 rounded-lg border border-blue-200 dark:border-blue-500/20">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-lg">local_shipping</span>
                            <span class="text-xs font-medium text-blue-900 dark:text-blue-300">Pengiriman Aman</span>
                        </div>
                        <p class="text-xs text-blue-700 dark:text-blue-400">Garansi sampai tujuan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Produk Terkait</h2>
                        <p class="text-sm text-gray-600 dark:text-zinc-400 mt-1">Produk lain yang mungkin Anda suka</p>
                    </div>
                    <a href="{{ route('pembeli.produk.index', ['category' => $product->category_id]) }}"
                       class="text-sm font-medium text-soft-green hover:text-primary transition-colors inline-flex items-center gap-1">
                        <span>Lihat Semua</span>
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </a>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($relatedProducts as $related)
                        <div class="group bg-gray-50 dark:bg-zinc-800/50 rounded-xl border border-gray-200 dark:border-zinc-700 hover:border-soft-green dark:hover:border-soft-green overflow-hidden transition-all">
                            <a href="{{ route('pembeli.produk.show', $related->slug) }}" class="block relative overflow-hidden bg-gray-100 dark:bg-zinc-800 aspect-square">
                                @if($related->image)
                                    <img src="{{ asset('storage/' . $related->image) }}" 
                                         alt="{{ $related->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-4xl">image</span>
                                    </div>
                                @endif
                            </a>
                            <div class="p-3">
                                <a href="{{ route('pembeli.produk.show', $related->slug) }}">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-2 group-hover:text-soft-green transition-colors mb-1">
                                        {{ $related->name }}
                                    </h3>
                                </a>
                                <p class="text-sm font-bold text-soft-green">
                                    Rp {{ number_format($related->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    const maxStock = {{ $product->stock }};
    
    function increaseQuantity() {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue < maxStock) {
            input.value = currentValue + 1;
        }
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
        }
    }

    function addToCart(productId) {
        const quantity = parseInt(document.getElementById('quantity').value);
        
        fetch(`/pembeli/keranjang/tambah/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`${quantity} produk berhasil ditambahkan ke keranjang!`);
                location.reload();
            } else {
                alert(data.message || 'Gagal menambahkan ke keranjang');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menambahkan ke keranjang');
        });
    }

    function buyNow(productId) {
        const quantity = parseInt(document.getElementById('quantity').value);
        // Add to cart first, then redirect to checkout
        addToCart(productId);
        setTimeout(() => {
            window.location.href = '{{ route("pembeli.keranjang") }}';
        }, 1000);
    }
</script>
@endsection