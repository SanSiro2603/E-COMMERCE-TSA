{{-- resources/views/pembeli/produk/show.blade.php --}}
@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">

    <!-- Gambar -->
    <div>
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                 class="w-full rounded-xl shadow-lg">
        @else
            <div class="w-full h-96 bg-gray-200 border-2 border-dashed rounded-xl"></div>
        @endif
        @if($product->health_certificate)
            <a href="{{ asset('storage/' . $product->health_certificate) }}" target="_blank"
               class="mt-4 inline-block px-4 py-2 bg-soft-green text-white rounded-lg text-sm font-medium">
                Download Sertifikat Kesehatan
            </a>
        @endif
    </div>

    <!-- Info -->
    <div class="space-y-6">
        <div>
            <p class="text-sm text-gray-600">{{ $product->category->name ?? 'Uncategorized' }}</p>
            <h1 class="text-3xl font-bold text-charcoal dark:text-white">{{ $product->name }}</h1>
            <p class="text-2xl font-bold text-soft-green mt-2">Rp {{ number_format($product->price) }}</p>
            <p class="text-sm text-gray-600 mt-1">Stok: {{ $product->stock }} tersedia</p>
        </div>

        <div>
            <h3 class="font-semibold text-charcoal">Deskripsi</h3>
            <p class="text-charcoal/80 dark:text-zinc-300">{!! nl2br(e($product->description ?? 'Tidak ada deskripsi.')) !!}</p>
        </div>

        <div class="flex gap-3">
            <button onclick="addToCart({{ $product->id }})"
                    class="flex-1 gradient-button text-white py-3 rounded-lg font-medium">
                + Keranjang
            </button>
            {{-- <a href="{{ route('pembeli.checkout') }}?product={{ $product->id }}"
               class="flex-1 bg-charcoal text-white py-3 rounded-lg font-medium text-center">
                Beli Sekarang
            </a> --}}
        </div>
    </div>
</div>

<!-- Related Products -->
<div class="mt-12">
    <h3 class="text-xl font-semibold mb-4">Produk Terkait</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($relatedProducts as $related)
            @include('pembeli.produk._card', ['product' => $related])
        @endforeach
    </div>
</div>

<script>
    function addToCart(productId) {
        // AJAX ke keranjang (nanti)
        alert('Ditambahkan ke keranjang!');
    }
</script>
@endsection