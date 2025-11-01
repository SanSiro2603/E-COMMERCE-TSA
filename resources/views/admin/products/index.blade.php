{{-- resources/views/admin/products/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Produk - Admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-charcoal">Kelola Produk</h1>
    <a href="{{ route('admin.products.create') }}" class="gradient-button text-white px-4 py-2 rounded-lg text-sm font-medium">
        + Tambah Produk
    </a>
</div>

<!-- Search -->
<form method="GET" class="mb-4">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..."
           class="px-4 py-2 border rounded-lg w-full md:w-64">
</form>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gambar</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Kategori</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Harga</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Stok</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($products as $product)
                <tr>
                    <td class="px-6 py-4">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="h-12 w-12 object-cover rounded-lg">
                        @else
                            <div class="h-12 w-12 bg-gray-200 rounded-lg"></div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-charcoal">{{ $product->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $product->category->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">Rp {{ number_format($product->price) }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="{{ $product->stock <= 5 ? 'text-red-600' : '' }}">{{ $product->stock }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-soft-green hover:underline">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus produk ini?')" class="text-red-600 hover:underline ml-2">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-8 text-gray-500">Belum ada produk</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $products->links() }}
</div>
@endsection