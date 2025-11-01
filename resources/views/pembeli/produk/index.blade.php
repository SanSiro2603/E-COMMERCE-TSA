{{-- resources/views/pembeli/produk/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Katalog Produk - Lembah Hijau')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-charcoal dark:text-white font-be-vietnam">Katalog Produk</h1>
            <p class="text-sm text-charcoal/70 dark:text-zinc-400">Temukan hewan impianmu di Lembah Hijau</p>
        </div>
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                   class="px-4 py-2 border rounded-lg text-sm w-64">
            <button type="submit" class="px-4 py-2 bg-soft-green text-white rounded-lg text-sm font-medium">
                Cari
            </button>
        </form>
    </div>

    <!-- Filter Kategori -->
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('pembeli.produk.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ !request('category') ? 'bg-soft-green text-white' : 'bg-gray-200 text-charcoal hover:bg-gray-300' }}">
            Semua
        </a>
        @foreach($categories as $category)
            <a href="{{ route('pembeli.produk.index', ['category' => $category->id]) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium {{ request('category') == $category->id ? 'bg-soft-green text-white' : 'bg-gray-200 text-charcoal hover:bg-gray-300' }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    <!-- Grid Produk -->
    @if($products->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                @include('pembeli.components._card', ['product' => $product])
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">Tidak ada produk ditemukan</p>
            <a href="{{ route('pembeli.produk.index') }}" class="text-soft-green hover:underline">Reset filter</a>
        </div>
    @endif

</div>
@endsection