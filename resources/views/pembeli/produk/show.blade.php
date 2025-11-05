{{-- resources/views/pembeli/produk/show.blade.php --}}
@extends('layouts.app')

@section('title', $product->name . ' - Lembah Hijau')

@section('content')
<div class="p-6 bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
    <div class="flex flex-col lg:flex-row gap-8">

        <!-- LEFT: PRODUCT IMAGE -->
<div class="lg:w-1/2 w-full flex flex-col items-center space-y-4">
    <!-- Gambar Produk -->
    <div class="relative w-full max-w-md aspect-[4/3] rounded-xl overflow-hidden bg-gray-100 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" 
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-8xl">image</span>
            </div>
        @endif

        <!-- Stock Label -->
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

                <!-- Deskripsi penuh -->
                <!-- Description -->
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

                <!-- Info stok -->
                <p class="text-sm text-gray-600 dark:text-zinc-400 mb-3">
                    Stok: 
                    <span class="font-semibold {{ $product->stock > 5 ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                        {{ $product->stock }}
                    </span>
                </p>

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
                @if($product->stock > 0)
                <button onclick="addToCart({{ $product->id }})"
                        class="flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-soft-green to-primary text-white rounded-lg font-semibold hover:shadow-md transition-all">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    Tambah ke Keranjang
                </button>
                <button onclick="buyNow({{ $product->id }})"
                        class="flex items-center justify-center gap-2 px-4 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-lg font-semibold hover:opacity-90 transition-all">
                    <span class="material-symbols-outlined">bolt</span>
                    Beli Sekarang
                </button>
                @else
                <button disabled class="px-4 py-3 bg-gray-200 dark:bg-zinc-800 text-gray-400 dark:text-zinc-500 rounded-lg cursor-not-allowed">
                    <span class="material-symbols-outlined text-xl">block</span>
                    Stok Habis
                </button>
                @endif

                <a href="{{ route('pembeli.produk.index') }}"
                   class="flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 rounded-lg hover:bg-gray-200 dark:hover:bg-zinc-700 transition">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Kembali ke Katalog
                </a>
            </div>
        </div>
    </div>
</div>

<script>
const maxStock = {{ $product->stock }};
function increaseQuantity() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) < maxStock) input.value++;
}
function decreaseQuantity() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) input.value--;
}
function addToCart(productId) {
    const qty = parseInt(document.getElementById('quantity').value);
    fetch(`/pembeli/keranjang/tambah/${productId}`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
        body: JSON.stringify({ quantity: qty })
    }).then(res=>res.json()).then(data=>{
        if(data.success){ alert(`${qty} produk ditambahkan ke keranjang!`); location.reload();}
        else alert(data.message||'Gagal menambahkan ke keranjang');
    }).catch(()=>alert('Terjadi kesalahan.'));
}
function buyNow(id){
    addToCart(id);
    setTimeout(()=>window.location.href='{{ route("pembeli.keranjang.index") }}',800);
}
</script>
@endsection
