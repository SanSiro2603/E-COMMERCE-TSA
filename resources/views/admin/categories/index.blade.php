{{-- resources/views/admin/categories/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Kategori')

@section('content')

<style>
    /* Custom Pagination Styling */
    .pagination {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    
    .pagination .page-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 0.5rem;
        transition: all 0.2s;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(to right, #7BB661, #72e236);
        color: white;
        border: none;
    }
    
    .pagination .page-link:hover:not(.active) {
        background-color: rgba(123, 182, 97, 0.1);
        color: #7BB661;
    }
    
    .dark .pagination .page-link {
        color: #e4e4e7;
        background-color: #27272a;
        border-color: #3f3f46;
    }
    
    .dark .pagination .page-link:hover:not(.active) {
        background-color: rgba(123, 182, 97, 0.15);
        border-color: #7BB661;
    }

    /* Simple fade in animation for alerts */
    .animate-fade {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
     /* Simple fade in animation for alerts */
    .animate-fade {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">Kelola Kategori</h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Manajemen kategori produk toko Anda</p>
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
                    <p class="text-xs text-gray-500 dark:text-zinc-400 font-medium">Total Kategori</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $categories->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-50 dark:bg-green-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-xl">check_circle</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 font-medium">Kategori Aktif</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $categories->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-50 dark:bg-purple-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-xl">inventory_2</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 font-medium">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $categories->sum(fn($c) => $c->products()->count()) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <!-- Table Header with Search -->
        <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Kategori</h3>
                    <p class="text-sm text-gray-500 dark:text-zinc-400 mt-0.5">{{ $categories->total() }} kategori ditemukan</p>
                </div>
                
                <!-- Search Form -->
                <form method="GET" class="w-full sm:w-auto">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500 text-xl">search</span>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Cari nama kategori..."
                            class="pl-10 pr-4 py-2.5 w-full sm:w-64 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-transparent transition-all"
                        >
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-zinc-800/50 border-b border-gray-200 dark:border-zinc-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">
                            Nama
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">
                            Slug
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">
                            Jumlah Produk
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-zinc-300 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-800">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-soft-green to-primary rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-md">
                                        {{ strtoupper(substr($category->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $category->name }}</p>
                                        {{-- <p class="text-xs text-gray-500 dark:text-zinc-400">ID: {{ $category->id }}</p> --}}
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
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 rounded-full text-xs font-semibold">
                                    <span class="material-symbols-outlined text-sm">inventory_2</span>
                                    {{ $category->products()->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($category->is_active)
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 rounded-full text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400 rounded-full text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors"
                                       title="Edit Kategori">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                        @csrf 
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            onclick="return confirm('Yakin ingin menghapus kategori {{ $category->name }}? Semua produk dalam kategori ini akan terpengaruh.')"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-500/10 rounded-lg hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors"
                                            title="Hapus Kategori">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-4xl">category</span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white mb-1">Belum ada kategori</p>
                                    <p class="text-xs text-gray-500 dark:text-zinc-400 mb-4">Mulai dengan menambahkan kategori pertama Anda</p>
                                    <a href="{{ route('admin.categories.create') }}" 
                                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-soft-green to-primary rounded-lg hover:shadow-lg transition-all">
                                        <span class="material-symbols-outlined text-lg">add</span>
                                        Tambah Kategori
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-800/30">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-600 dark:text-zinc-400">
                        Menampilkan <span class="font-semibold text-gray-900 dark:text-white">{{ $categories->firstItem() }}</span> 
                        sampai <span class="font-semibold text-gray-900 dark:text-white">{{ $categories->lastItem() }}</span> 
                        dari <span class="font-semibold text-gray-900 dark:text-white">{{ $categories->total() }}</span> kategori
                    </div>
                    
                    <div class="flex items-center gap-2">
                        {{ $categories->links() }}
                    </div>
                </div>
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
            if(alert) {
                setTimeout(() => {
                    alert.style.transition = "opacity 0.5s ease-out, transform 0.5s ease-out";
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => alert.remove(), 500); // hapus elemen setelah animasi
                }, 2000); // tunggu 2 detik sebelum fade out
            }
        });
    });


@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ session("success") }}',
        toast: true,
        position: 'top-end',
        timer: 2000,
        showConfirmButton: false,
    });
</script>
@endif

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ session("success") }}',
        toast: true,
        position: 'top-end',
        timer: 2000,
        showConfirmButton: false,
    });
</script>
@endif

</script>
@endsection
