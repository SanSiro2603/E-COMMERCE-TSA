{{-- resources/views/pembeli/produk/show.blade.php --}}
@extends('layouts.app')

@section('title', $product->name . ' - E-Commerce TSA')

@section('content')
<div class="space-y-6">

    <!-- Detail Produk -->
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
                            onclick="openZoom(currentImageIndex)"
                            class="w-full h-full object-cover cursor-zoom-in">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-8xl">image</span>
                        </div>
                    @endif

                    <!-- Stock Label -->
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
                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 px-3 py-1 bg-black/60 text-white text-xs rounded-full backdrop-blur-sm">
                            <span id="current-image-index">1</span> / {{ count($product->images) }}
                        </div>
                    @endif
                </div>

                <!-- Thumbnail Gallery -->
                @if(isset($product->images) && count($product->images) > 1)
                    <div class="w-full max-w-md">
                        <div id="thumbnail-container" class="flex gap-2 overflow-x-auto scrollbar-hide scroll-smooth pb-2">
                            @foreach($product->images as $index => $image)
                                <button onclick="selectImage({{ $index }})"
                                        class="thumbnail-btn flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 transition-all {{ $index === 0 ? 'border-soft-green' : 'border-gray-200 dark:border-zinc-700' }} hover:border-soft-green">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Gambar {{ $index + 1 }}" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Sertifikat Kesehatan -->
                @if($product->health_certificate)
                    <div class="w-full max-w-md flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-500/10 dark:to-cyan-500/10 border border-blue-200 dark:border-blue-500/20 rounded-lg shadow-sm">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-2xl">verified</span>
                            <div>
                                <h4 class="text-xs font-semibold text-gray-900 dark:text-white">Sertifikat Kesehatan</h4>
                                <p class="text-[11px] text-gray-600 dark:text-zinc-400">Produk bersertifikat resmi</p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $product->health_certificate) }}" target="_blank"
                           class="flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-xs font-medium transition">
                            <span class="material-symbols-outlined text-xs">download</span>
                            Download
                        </a>
                    </div>
                @endif

            </div>
            {{-- akhir LEFT --}}

            <!-- RIGHT: DETAILS -->
            <div class="lg:w-1/2 w-full flex flex-col gap-4">

                <!-- Breadcrumb Kategori -->
                @if($product->category)
                    <div class="flex items-center gap-1.5 flex-wrap">
                        @if($product->category->parent)
                            <a href="{{ route('pembeli.produk.index', ['parent_category' => $product->category->parent_id]) }}"
                               class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 rounded-full text-xs font-medium hover:bg-blue-100 transition-colors">
                                <span class="material-symbols-outlined text-sm">category</span>
                                {{ $product->category->parent->name }}
                            </a>
                            <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                            <a href="{{ route('pembeli.produk.index', ['category' => $product->category_id]) }}"
                               class="inline-flex items-center gap-1 px-2.5 py-1 bg-purple-50 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400 rounded-full text-xs font-medium hover:bg-purple-100 transition-colors">
                                <span class="material-symbols-outlined text-sm">account_tree</span>
                                {{ $product->category->name }}
                            </a>
                        @else
                            <a href="{{ route('pembeli.produk.index', ['parent_category' => $product->category_id]) }}"
                               class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 rounded-full text-xs font-medium hover:bg-blue-100 transition-colors">
                                <span class="material-symbols-outlined text-sm">category</span>
                                {{ $product->category->name }}
                            </a>
                        @endif
                    </div>
                @endif

                <!-- Nama dan Harga -->
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ $product->name }}</h1>
                    <p class="text-2xl font-bold text-soft-green">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>

                <!-- Deskripsi -->
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-2">
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
                <div class="flex gap-4 text-sm text-gray-600 dark:text-zinc-400">
                    <div>
                        Stok:
                        <span id="stock-count-{{ $product->id }}"
                              class="font-semibold {{ $product->stock > 5 ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                            {{ $product->stock }}
                        </span>
                    </div>
                    <div>
                        Berat:
                        <span class="font-semibold text-gray-900 dark:text-white">
                            {{ $product->weight ?? 1 }} gram
                        </span>
                    </div>
                </div>

                <!-- Jumlah -->
                @if($product->stock > 0)
                    <div class="flex items-center gap-3">
                        <button onclick="decreaseQuantity()" class="w-9 h-9 flex items-center justify-center bg-gray-100 dark:bg-zinc-800 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-700 transition-colors">
                            <span class="material-symbols-outlined">remove</span>
                        </button>
                        <input id="quantity" type="number" value="1" min="1" max="{{ $product->stock }}"
                               class="w-16 text-center border border-gray-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 font-semibold text-gray-900 dark:text-white py-1.5">
                        <button onclick="increaseQuantity()" class="w-9 h-9 flex items-center justify-center bg-gray-100 dark:bg-zinc-800 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-700 transition-colors">
                            <span class="material-symbols-outlined">add</span>
                        </button>
                    </div>
                @endif

                <!-- Tombol Aksi -->
                <div class="flex flex-col gap-3">
                    <div id="add-to-cart-button">
                        @if($product->stock > 0)
                            <button onclick="addToCart({{ $product->id }})"
                                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-soft-green to-primary text-white rounded-lg font-semibold hover:shadow-md transition-all">
                                <span class="material-symbols-outlined">shopping_cart</span>
                                Tambah ke Keranjang
                            </button>
                        @else
                            <button disabled class="w-full px-4 py-3 bg-gray-200 dark:bg-zinc-800 text-gray-400 dark:text-zinc-500 rounded-lg cursor-not-allowed font-semibold">
                                Stok Habis
                            </button>
                        @endif
                    </div>

                    @if($product->stock > 0)
                        <button id="btn-buy-now"
                                onclick="buyNow({{ $product->id }})"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-lg font-semibold hover:opacity-90 transition-all">
                            <span class="material-symbols-outlined">bolt</span>
                            Beli Sekarang
                        </button>
                    @endif

                    <a href="{{ route('pembeli.produk.index') }}"
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-700 transition-colors">
                        <span class="material-symbols-outlined">arrow_back</span>
                        Kembali ke Katalog
                    </a>

                    <!-- Share Section -->
                    <div class="mt-2 pt-4 border-t border-gray-200 dark:border-zinc-800">
                        <p class="text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider mb-3">Bagikan Produk</p>
                        <div class="flex items-center gap-2 flex-wrap">

                            <!-- WhatsApp -->
                            <a href="https://wa.me/?text={{ urlencode($product->name . ' - Rp ' . number_format($product->price, 0, ',', '.') . ' | Cek di sini: ' . url()->current()) }}"
                               target="_blank"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-xs font-medium transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-white" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                WhatsApp
                            </a>

                            <!-- Copy Link -->
                            <button onclick="copyLink()"
                                    id="copy-link-btn"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-gray-700 dark:text-zinc-300 rounded-lg text-xs font-medium transition-all">
                                <span class="material-symbols-outlined text-sm">link</span>
                                <span id="copy-link-text">Salin Link</span>
                            </button>

                        </div>
                    </div>
                    {{-- akhir share section --}}

                </div>
                {{-- akhir tombol aksi --}}

            </div>
            {{-- akhir RIGHT --}}

        </div>
        {{-- akhir flex flex-col lg:flex-row --}}

    </div>
    {{-- akhir Detail Produk card --}}

    <!-- Modal Zoom Gambar -->
    <div id="zoom-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/90 backdrop-blur-sm"
        onclick="closeZoom()">

        <div class="relative max-w-4xl max-h-screen w-full h-full flex items-center justify-center p-4">

            <!-- Tombol Close -->
            <button onclick="closeZoom()"
                    class="absolute top-4 right-4 w-10 h-10 bg-white/20 hover:bg-white/30 text-white rounded-full flex items-center justify-center z-10 transition-all">
                <span class="material-symbols-outlined">close</span>
            </button>

            <!-- Tombol Prev -->
            <button onclick="prevZoom(event)"
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/20 hover:bg-white/30 text-white rounded-full flex items-center justify-center z-10 transition-all">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>

            <!-- Gambar Zoom -->
            <img id="zoom-image"
                src=""
                alt="Zoom"
                class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl">

            <!-- Tombol Next -->
            <button onclick="nextZoom(event)"
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/20 hover:bg-white/30 text-white rounded-full flex items-center justify-center z-10 transition-all">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>

            <!-- Counter -->
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 px-3 py-1 bg-black/60 text-white text-xs rounded-full">
                <span id="zoom-counter">1</span> / {{ count($product->images ?? [$product->image]) }}
            </div>

        </div>
    </div>

    <!-- Produk Terkait -->
    @if($relatedProducts->count() > 0)
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-5">
                <span class="material-symbols-outlined text-soft-green text-2xl">recommend</span>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Produk Terkait</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($relatedProducts as $related)
                    <div class="group cursor-pointer">
                        <!-- Gambar -->
                        <div class="relative aspect-square rounded-xl overflow-hidden mb-2 bg-gray-100 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700">
                            <a href="{{ route('pembeli.produk.show', $related->slug) }}">
                                @if($related->image)
                                    <img src="{{ asset('storage/' . $related->image) }}"
                                         alt="{{ $related->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-3xl">image</span>
                                    </div>
                                @endif
                            </a>

                            <!-- Stok habis overlay -->
                            @if($related->stock == 0)
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                    <span class="px-2 py-0.5 bg-red-500 text-white text-[10px] font-bold rounded">Habis</span>
                                </div>
                            @endif

                            <!-- Kategori badge -->
                            @if($related->category)
                                <div class="absolute top-1.5 left-1.5">
                                    <span class="px-1.5 py-0.5 bg-primary/90 text-white text-[9px] font-bold rounded">
                                        {{ Str::limit($related->category->parent->name ?? $related->category->name, 10) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <a href="{{ route('pembeli.produk.show', $related->slug) }}">
                            <p class="text-xs font-semibold text-gray-900 dark:text-white line-clamp-2 mb-1 group-hover:text-primary transition-colors">
                                {{ $related->name }}
                            </p>
                        </a>
                        <p class="text-xs font-bold text-soft-green">
                            Rp {{ number_format($related->price, 0, ',', '.') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
{{-- akhir space-y-6 --}}

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
let currentStock = {{ $product->stock }};
const productId  = {{ $product->id }};

// ===================== IMAGE GALLERY =====================
const productImages  = @json($product->images ?? [$product->image]);
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
    const mainImage    = document.getElementById('main-image');
    const imageCounter = document.getElementById('current-image-index');

    if (mainImage && productImages[currentImageIndex]) {
        mainImage.style.opacity = '0';
        setTimeout(() => {
            mainImage.src = "{{ asset('storage') }}/" + productImages[currentImageIndex];
            mainImage.style.transition = 'opacity 0.3s';
            mainImage.style.opacity = '1';
        }, 50);
    }

    if (imageCounter) imageCounter.textContent = currentImageIndex + 1;
}

function updateThumbnails() {
    const thumbnails = document.querySelectorAll('.thumbnail-btn');
    thumbnails.forEach((thumb, index) => {
        thumb.classList.toggle('border-soft-green', index === currentImageIndex);
        thumb.classList.toggle('border-gray-200', index !== currentImageIndex);
        thumb.classList.toggle('dark:border-zinc-700', index !== currentImageIndex);
    });

    const container      = document.getElementById('thumbnail-container');
    const selectedThumb  = thumbnails[currentImageIndex];
    if (container && selectedThumb) {
        selectedThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }
}

// ===================== ZOOM =====================
function openZoom(index) {
    const modal    = document.getElementById('zoom-modal');
    const zoomImg  = document.getElementById('zoom-image');
    const counter  = document.getElementById('zoom-counter');

    currentImageIndex = index;
    zoomImg.src = "{{ asset('storage') }}/" + productImages[index];
    counter.textContent = index + 1;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeZoom() {
    const modal = document.getElementById('zoom-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

function prevZoom(event) {
    event.stopPropagation();
    currentImageIndex = (currentImageIndex - 1 + productImages.length) % productImages.length;
    document.getElementById('zoom-image').src = "{{ asset('storage') }}/" + productImages[currentImageIndex];
    document.getElementById('zoom-counter').textContent = currentImageIndex + 1;
    updateThumbnails();
}

function nextZoom(event) {
    event.stopPropagation();
    currentImageIndex = (currentImageIndex + 1) % productImages.length;
    document.getElementById('zoom-image').src = "{{ asset('storage') }}/" + productImages[currentImageIndex];
    document.getElementById('zoom-counter').textContent = currentImageIndex + 1;
    updateThumbnails();
}

// Tutup zoom dengan ESC, navigasi dengan arrow key
document.addEventListener('keydown', e => {
    const zoomOpen = !document.getElementById('zoom-modal').classList.contains('hidden');
    if (e.key === 'Escape') closeZoom();
    else if (e.key === 'ArrowLeft') zoomOpen ? prevZoom(e) : prevImage();
    else if (e.key === 'ArrowRight') zoomOpen ? nextZoom(e) : nextImage();
});

let touchStartX = 0;
const mainImageEl = document.getElementById('main-image');
if (mainImageEl) {
    mainImageEl.addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].screenX; });
    mainImageEl.addEventListener('touchend', e => {
        const diff = touchStartX - e.changedTouches[0].screenX;
        if (diff > 50) nextImage();
        else if (diff < -50) prevImage();
    });
}

// ===================== QUANTITY =====================
function increaseQuantity() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) < currentStock) input.value++;
}

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) input.value--;
}

// ===================== CART =====================
function addToCart(productId) {
    const qty = parseInt(document.getElementById('quantity').value) || 1;

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
            if (typeof window.updateCartCount === 'function' && data.cart_count !== undefined) {
                window.updateCartCount(data.cart_count);
            }
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: `${qty} produk ditambahkan ke keranjang!`,
                toast: true,
                position: 'top-end',
                timer: 2000,
                showConfirmButton: false,
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message || 'Gagal menambahkan ke keranjang',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });
        }
    })
    .catch(() => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Terjadi kesalahan jaringan.',
            toast: true,
            position: 'top-end',
            timer: 3000,
            showConfirmButton: false,
        });
    });
}

// ===================== BUY NOW =====================
function buyNow(id) {
    const qty = parseInt(document.getElementById('quantity').value) || 1;
    const btn = document.getElementById('btn-buy-now');

    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined">sync</span> Memproses...';

    fetch(`/pembeli/keranjang/tambah/${id}`, {
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
            window.location.href = '{{ route("pembeli.pesanan.checkout") }}';
        } else {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined">bolt</span> Beli Sekarang';
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message || 'Gagal memproses pesanan',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined">bolt</span> Beli Sekarang';
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Terjadi kesalahan jaringan.',
            toast: true,
            position: 'top-end',
            timer: 3000,
            showConfirmButton: false,
        });
    });
}

// Reset tombol saat back button
window.addEventListener('pageshow', function() {
    const btn = document.getElementById('btn-buy-now');
    if (btn) {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined">bolt</span> Beli Sekarang';
    }
});

// ===================== SHARE =====================
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        const btn  = document.getElementById('copy-link-btn');
        const text = document.getElementById('copy-link-text');

        text.textContent = 'Tersalin!';
        btn.classList.add('bg-green-100', 'text-green-700');
        btn.classList.remove('bg-gray-100', 'dark:bg-zinc-800', 'text-gray-700', 'dark:text-zinc-300');

        setTimeout(() => {
            text.textContent = 'Salin Link';
            btn.classList.remove('bg-green-100', 'text-green-700');
            btn.classList.add('bg-gray-100', 'dark:bg-zinc-800', 'text-gray-700', 'dark:text-zinc-300');
        }, 2000);
    });
}
</script>
@endsection