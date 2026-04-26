{{-- resources/views/admin/categories/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Kategori')

@section('content')

<style>
    .pagination { display: flex; gap: 0.5rem; align-items: center; }
    .pagination .page-link { padding: 0.5rem 0.75rem; font-size: 0.875rem; font-weight: 500; border-radius: 0.5rem; transition: all 0.2s; }
    .pagination .page-item.active .page-link { background: linear-gradient(to right, #7BB661, #72e236); color: white; border: none; }
    .pagination .page-link:hover:not(.active) { background-color: rgba(123, 182, 97, 0.1); color: #7BB661; }
    .dark .pagination .page-link { color: #e4e4e7; background-color: #27272a; border-color: #3f3f46; }
    .dark .pagination .page-link:hover:not(.active) { background-color: rgba(123, 182, 97, 0.15); border-color: #7BB661; }
    .animate-fade { animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">Kelola Kategori</h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Manajemen kategori & sub-kategori produk toko Anda</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-soft-green to-primary rounded-lg hover:shadow-lg transition-all">
            <span class="material-symbols-outlined text-lg">add</span>
            Tambah Kategori
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div id="alert-success" class="p-4 mb-4 text-sm text-green-700 bg-green-100 dark:bg-green-800 dark:text-green-200 rounded-lg flex items-center gap-2 animate-fade">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div id="alert-error" class="p-4 mb-4 text-sm text-red-700 bg-red-100 dark:bg-red-800 dark:text-red-200 rounded-lg flex items-center gap-2 animate-fade">
            <span class="material-symbols-outlined">error</span>
            {{ session('error') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-xl">category</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 font-medium">Kategori Utama</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $parentCategories->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-50 dark:bg-purple-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-xl">account_tree</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 font-medium">Sub Kategori</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $subCategories->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-50 dark:bg-green-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-xl">inventory_2</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 font-medium">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $parentCategories->sum(fn($c) => $c->products()->count()) + $subCategories->sum(fn($c) => $c->products()->count()) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== TABEL KATEGORI UTAMA ===================== -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-50 dark:bg-blue-500/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-lg">category</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Kategori Utama</h3>
                        <p class="text-sm text-gray-500 dark:text-zinc-400">{{ $parentCategories->total() }} kategori utama</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-zinc-800/50 border-b border-gray-200 dark:border-zinc-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Sub Kategori</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-800">
                    @forelse($parentCategories as $category)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($category->image)
                                        <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}"
                                             class="w-10 h-10 object-cover rounded-lg shadow-md flex-shrink-0">
                                    @else
                                        <div class="w-10 h-10 bg-gradient-to-br from-soft-green to-primary rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-md flex-shrink-0">
                                            {{ strtoupper(substr($category->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $category->name }}</p>
                                        <p class="text-xs text-blue-500 dark:text-blue-400 mt-0.5">Kategori Utama</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-sm">link</span>
                                    <code class="text-sm text-gray-600 dark:text-zinc-300 bg-gray-100 dark:bg-zinc-800 px-2 py-1 rounded">{{ $category->slug }}</code>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-50 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400 rounded-full text-xs font-semibold">
                                    <span class="material-symbols-outlined text-sm">account_tree</span>
                                    {{ $category->children->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 rounded-full text-xs font-semibold">
                                    <span class="material-symbols-outlined text-sm">inventory_2</span>
                                    {{ $category->products()->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($category->is_active)
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 rounded-full text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400 rounded-full text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors">
                                        <span class="material-symbols-outlined text-sm">edit</span>Edit
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus kategori {{ $category->name }}? Sub-kategori di dalamnya harus dihapus terlebih dahulu.')"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-500/10 rounded-lg hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors">
                                            <span class="material-symbols-outlined text-sm">delete</span>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center">
                                <p class="text-sm text-gray-500 dark:text-zinc-400">Belum ada kategori utama</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($parentCategories->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-800/30">
                {{ $parentCategories->links() }}
            </div>
        @endif
    </div>

    <!-- ===================== TABEL SUB KATEGORI ===================== -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-purple-50 dark:bg-purple-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-lg">account_tree</span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sub Kategori</h3>
                    <p class="text-sm text-gray-500 dark:text-zinc-400">{{ $subCategories->total() }} sub kategori</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-zinc-800/50 border-b border-gray-200 dark:border-zinc-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Kategori Utama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-800">
                    @forelse($subCategories as $sub)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($sub->image)
                                        <img src="{{ Storage::url($sub->image) }}" alt="{{ $sub->name }}"
                                             class="w-10 h-10 object-cover rounded-lg shadow-md flex-shrink-0">
                                    @else
                                        <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-md flex-shrink-0">
                                            {{ strtoupper(substr($sub->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $sub->name }}</p>
                                        <p class="text-xs text-purple-500 dark:text-purple-400 mt-0.5">Sub Kategori</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 rounded-full text-xs font-medium">
                                    <span class="material-symbols-outlined text-sm">category</span>
                                    {{ $sub->parent->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-sm">link</span>
                                    <code class="text-sm text-gray-600 dark:text-zinc-300 bg-gray-100 dark:bg-zinc-800 px-2 py-1 rounded">{{ $sub->slug }}</code>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 rounded-full text-xs font-semibold">
                                    <span class="material-symbols-outlined text-sm">inventory_2</span>
                                    {{ $sub->products()->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($sub->is_active)
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 rounded-full text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400 rounded-full text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $sub) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors">
                                        <span class="material-symbols-outlined text-sm">edit</span>Edit
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $sub) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus sub-kategori {{ $sub->name }}?')"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-500/10 rounded-lg hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors">
                                            <span class="material-symbols-outlined text-sm">delete</span>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center">
                                <p class="text-sm text-gray-500 dark:text-zinc-400">Belum ada sub kategori</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subCategories->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-800/30">
                {{ $subCategories->links() }}
            </div>
        @endif
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const successAlert = document.getElementById('alert-success');
        const errorAlert = document.getElementById('alert-error');

        [successAlert, errorAlert].forEach(alert => {
            if (alert) {
                setTimeout(() => {
                    alert.style.transition = "opacity 0.5s ease-out, transform 0.5s ease-out";
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => alert.remove(), 500);
                }, 2000);
            }
        });

        @if(session('success'))
            Swal.fire({ icon: 'success', title: '{{ session("success") }}', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
        @endif

        @if(session('error'))
            Swal.fire({ icon: 'error', title: '{{ session("error") }}', toast: true, position: 'top-end', timer: 3000, showConfirmButton: false });
        @endif
    });
</script>

@endsection