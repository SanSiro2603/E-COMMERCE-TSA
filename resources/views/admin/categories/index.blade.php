{{-- resources/views/admin/categories/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Kategori')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-charcoal">Kelola Kategori</h1>
    <a href="{{ route('admin.categories.create') }}" class="gradient-button text-white px-4 py-2 rounded-lg text-sm font-medium">
        + Tambah Kategori
    </a>
</div>

<!-- Search -->
<form method="GET" class="mb-4">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kategori..."
           class="px-4 py-2 border rounded-lg w-full md:w-64">
</form>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Slug</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Jumlah Produk</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($categories as $category)
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-charcoal">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $category->slug }}</td>
                    <td class="px-6 py-4 text-sm">{{ $category->products()->count() }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-soft-green hover:underline">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline ml-2">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus kategori ini?')" class="text-red-600 hover:underline">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-8 text-gray-500">Belum ada kategori</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $categories->links() }}
</div>
@endsection