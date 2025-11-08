{{-- resources/views/pembeli/produk/show.blade.php --}}
@extends('layouts.app')

@section('title', $product->name . ' - Lembah Hijau')

@section('content')
<div class="p-6 bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
    <div class="flex flex-col lg:flex-row gap-8">

        <!-- LEFT: PRODUCT IMAGE GALLERY -->
        <div class="lg:w-1/2 w-full flex flex-col items-center space-y-4">
            <!-- Gambar Utama -->
            <div class="relative w-full max-w-md aspect-[4/3] rounded-xl overflow-hidden bg-gray-100 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700">
                @if($product->image)
                    <img id="main-image" 
                         src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-8xl">image</span>
                    </div>
                @endif

                <!-- Stock Label di Gambar -->
                <div id="image-stock-label">
                    @if($product->stock > 0 && $product->stock <= 5)
                        <div class="absolute top-3 right-3 px-3 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full shadow-md">
                            Stok {{ $product->stock }}
                        </div>
                    @elseif($product->stock == 0)
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                            <span class="bg-red-600 text-white px-3 py-1 rounded-full font-semibold text-sm">Stok Habis</span>
                        </div>
                    @endif
                </div>

                <!-- Navigation Arrows -->
                @if(isset($product->images) && count($product->images) > 1)
                <button onclick="prevImage()" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/50 hover:bg-black/70 text-white rounded-full flex items-center justify-center transition backdrop-blur-sm">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button onclick="nextImage()" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/50 hover:bg-black/70 text-white rounded-full flex items-center justify-center transition backdrop-blur-sm">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>

                <!-- Image Counter -->
                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 px-3 py-1 bg-black/60 text-white text-xs rounded-full backdrop-blur-sm">
                    <span id="current-image-index">1</span> / {{ count($product->images) }}
                </div>
                @endif
            </div>

            <!-- Thumbnail Gallery (Horizontal Scroll) -->
            @if(isset($product->images) && count($product->images) > 1)
            <div class="w-full max-w-md">
                <div class="relative">
                    <!-- Scroll Container -->
                    <div id="thumbnail-container" class="flex gap-2 overflow-x-auto scrollbar-hide scroll-smooth pb-2">
                        @foreach($product->images as $index => $image)
                        <button onclick="selectImage({{ $index }})" 
                                class="thumbnail-btn flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 transition-all {{ $index === 0 ? 'border-soft-green' : 'border-gray-200 dark:border-zinc-700' }} hover:border-soft-green">
                            <img src="{{ asset('storage/' . $image) }}" 
                                 alt="Gambar {{ $index + 1 }}"
                                 class="w-full h-full object-cover">
                        </button>
                        @endforeach
                    </div>

                    <!-- Scroll Indicators (optional) -->
                    <div class="absolute -left-2 top-1/2 -translate-y-1/2 pointer-events-none">
                        <div class="w-8 h-20 bg-gradient-to-r from-white dark:from-zinc-900 to-transparent"></div>
                    </div>
                    <div class="absolute -right-2 top-1/2 -translate-y-1/2 pointer-events-none">
                        <div class="w-8 h-20 bg-gradient-to-l from-white dark:from-zinc-900 to-transparent"></div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Tombol Download Sertifikat -->
            @if($product->health_certificate)
                <div class="w-full max-w-md flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-500/10 dark:to-cyan-500/10 border border-blue-200 dark:border-blue-500/20 rounded-lg shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-2xl">verified</span>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white">Sertifikat Kesehatan</h4>
                            <p class="text-[11px] text-gray-600 dark:text-zinc-400">Produk bersertifikat resmi</p>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $product->health_certificate) }}" 
                       target="_blank"
                       class="flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-xs font-medium transition">
                        <span class="material-symbols-outlined text-xs">download</span>
                        Download
                    </a>
                </div>
            @endif
        </div>

        <!-- RIGHT: DETAILS -->
        <div class="lg:w-1/2 w-full flex flex-col justify-between">
            <div>
                <!-- Nama dan Harga -->
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $product->name }}</h1>
                <p class="text-2xl font-bold text-soft-green mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                <!-- Deskripsi -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-soft-green">description</span>
                        Deskripsi Produk
                    </h3>
                    <div class="p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg">
                        <p class="text-gray-700 dark:text-zinc-300 leading-relaxed text-justify">
                            {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                        </p>
                    </div>
                </div>

                <!-- Info Stok & Berat -->
                <div class="flex gap-4 mb-3 text-sm text-gray-600 dark:text-zinc-400">
                    <div>
                        Stok: 
                        <span id="stock-count-{{ $product->id }}" 
                              class="font-semibold {{ $product->stock > 5 ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                            {{ $product->stock }}
                        </span>
                    </div>
                    <div>
                        Berat: 
                        <span class="font-semibold {{ ($product->weight ?? 1) > 5 ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                            {{ $product->weight ?? 1 }} gram
                        </span>
                    </div>
                </div>

                <!-- JUMLAH -->
                @if($product->stock > 0)
                <div class="flex items-center gap-3 mb-5">
                    <button onclick="decreaseQuantity()" class="w-9 h-9 flex items-center justify-center bg-gray-100 dark:bg-zinc-800 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-700">
                        <span class="material-symbols-outlined">remove</span>
                    </button>
                    <input id="quantity" type="number" value="1" min="1" max="{{ $product->stock }}"
                           class="w-16 text-center border border-gray-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 font-semibold text-gray-900 dark:text-white">
                    <button onclick="increaseQuantity()" class="w-9 h-9 flex items-center justify-center bg-gray-100 dark:bg-zinc-800 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-700">
                        <span class="material-symbols-outlined">add</span>
                    </button>
                </div>
                @endif
            </div>

            <!-- BUTTONS -->
            <div class="flex flex-col gap-3">
                <div id="add-to-cart-button">
                    @if($product->stock > 0)
                    <button onclick="addToCart({{ $product->id }})"
                            class="flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-soft-green to-primary text-white rounded-lg font-semibold hover:shadow-md transition-all">
                        <span class="material-symbols-outlined">shopping_cart</span>
                        Tambah ke Keranjang
                    </button>
                    @else
                    <button disabled class="px-4 py-3 bg-gray-200 dark:bg-zinc-800 text-gray-400 dark:text-zinc-500 rounded-lg cursor-not-allowed">
                        <span class="material-symbols-outlined text-xl">block</span>
                        Stok Habis
                    </button>
                    @endif
                </div>

                <button onclick="buyNow({{ $product->id }})"
                        class="flex items-center justify-center gap-2 px-4 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-lg font-semibold hover:opacity-90 transition-all">
                    <span class="material-symbols-outlined">bolt</span>
                    Beli Sekarang
                </button>

                <a href="{{ route('pembeli.produk.index') }}"
                   class="flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-700 transition">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Kembali ke Katalog
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script>
let currentStock = {{ $product->stock }};
const productId = {{ $product->id }};

// Image Gallery Variables
const productImages = @json($product->images ?? [$product->image]);
let currentImageIndex = 0;

function selectImage(index) {
    currentImageIndex = index;
    updateMainImage();
    updateThumbnails();
}

function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % productImages.length;
    updateMainImage();
    updateThumbnails();
}

function prevImage() {
    currentImageIndex = (currentImageIndex - 1 + productImages.length) % productImages.length;
    updateMainImage();
    updateThumbnails();
}

function updateMainImage() {
    const mainImage = document.getElementById('main-image');
    const imageCounter = document.getElementById('current-image-index');
    
    if (mainImage && productImages[currentImageIndex]) {
        mainImage.src = "{{ asset('storage') }}/" + productImages[currentImageIndex];
        mainImage.style.opacity = '0';
        setTimeout(() => {
            mainImage.style.transition = 'opacity 0.3s';
            mainImage.style.opacity = '1';
        }, 50);
    }
    
    if (imageCounter) {
        imageCounter.textContent = currentImageIndex + 1;
    }
}

function updateThumbnails() {
    const thumbnails = document.querySelectorAll('.thumbnail-btn');
    thumbnails.forEach((thumb, index) => {
        if (index === currentImageIndex) {
            thumb.classList.remove('border-gray-200', 'dark:border-zinc-700');
            thumb.classList.add('border-soft-green');
        } else {
            thumb.classList.remove('border-soft-green');
            thumb.classList.add('border-gray-200', 'dark:border-zinc-700');
        }
    });
    
    // Auto scroll thumbnail into view
    const container = document.getElementById('thumbnail-container');
    const selectedThumb = thumbnails[currentImageIndex];
    if (container && selectedThumb) {
        selectedThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }
}

// Keyboard navigation
document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') {
        prevImage();
    } else if (e.key === 'ArrowRight') {
        nextImage();
    }
});

// Touch swipe support
let touchStartX = 0;
let touchEndX = 0;

const mainImageEl = document.getElementById('main-image');
if (mainImageEl) {
    mainImageEl.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    });

    mainImageEl.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });
}

function handleSwipe() {
    if (touchEndX < touchStartX - 50) {
        nextImage();
    }
    if (touchEndX > touchStartX + 50) {
        prevImage();
    }
}

function increaseQuantity() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) < currentStock) input.value++;
}

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) input.value--;
}

function addToCart(productId) {
    const qty = parseInt(document.getElementById('quantity').value) || 1;
    const inputQty = document.getElementById('quantity');
    const stockEl = document.getElementById(`stock-count-${productId}`);
    const imageLabel = document.getElementById('image-stock-label');
    const addBtn = document.getElementById('add-to-cart-button').querySelector('button');

    fetch(`/pembeli/keranjang/tambah/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ quantity: qty })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            currentStock = data.new_stock;

            // Update stok di teks
            if (stockEl) {
                stockEl.textContent = currentStock > 0 ? currentStock : 'Habis';
                stockEl.className = 'font-semibold ';
                if (currentStock > 5) {
                    stockEl.classList.add('text-green-600', 'dark:text-green-400');
                } else if (currentStock > 0) {
                    stockEl.classList.add('text-yellow-600', 'dark:text-yellow-400');
                } else {
                    stockEl.classList.add('text-red-600', 'dark:text-red-400');
                }
            }

            // Update label di gambar
            imageLabel.innerHTML = '';
            if (currentStock > 0 && currentStock <= 5) {
                imageLabel.innerHTML = `<div class="absolute top-3 right-3 px-3 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full shadow-md">Stok ${currentStock}</div>`;
            } else if (currentStock === 0) {
                imageLabel.innerHTML = `<div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                    <span class="bg-red-600 text-white px-3 py-1 rounded-full font-semibold text-sm">Stok Habis</span>
                </div>`;
            }

            // Update input max
            inputQty.max = currentStock;
            if (parseInt(inputQty.value) > currentStock && currentStock > 0) {
                inputQty.value = currentStock;
            }

            // Nonaktifkan tombol jika habis
            if (currentStock === 0) {
                addBtn.disabled = true;
                addBtn.innerHTML = '<span class="material-symbols-outlined">block</span> Stok Habis';
                addBtn.className = 'px-4 py-3 bg-gray-200 dark:bg-zinc-800 text-gray-400 dark:text-zinc-500 rounded-lg cursor-not-allowed';
            }

            alert(`${qty} produk ditambahkan ke keranjang!`);
        } else {
            alert(data.message || 'Gagal menambahkan ke keranjang');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan jaringan.');
    });
}

function buyNow(id) {
    addToCart(id);
    setTimeout(() => {
        window.location.href = '{{ route("pembeli.pesanan.checkout") }}';
    }, 800);
}
</script>
@endsection