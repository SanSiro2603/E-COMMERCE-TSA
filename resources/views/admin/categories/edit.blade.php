{{-- resources/views/admin/categories/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Kategori - E-Commerce TSA')

@section('content')
<div class="space-y-6">
    
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-soft-green transition-colors">Dashboard</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <a href="{{ route('admin.categories.index') }}" class="hover:text-soft-green transition-colors">Kategori</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-gray-900 dark:text-white font-medium">Edit Kategori</span>
    </nav>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">Edit Kategori</h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Perbarui informasi kategori <span class="font-semibold text-soft-green">{{ $category->name }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.categories.index') }}" 
               class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-zinc-200 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Kembali
            </a>
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 dark:bg-red-600 dark:hover:bg-red-700 rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-lg">delete</span>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- Category Info Banner -->
    <div class="bg-gradient-to-r from-soft-green/10 to-primary/10 dark:from-soft-green/5 dark:to-primary/5 border border-soft-green/20 dark:border-soft-green/10 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 bg-soft-green/20 dark:bg-soft-green/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-soft-green text-xl">category</span>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Informasi Kategori Saat Ini</h3>
                <div class="mt-2 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                    <div>
                        <span class="text-gray-500 dark:text-zinc-400">Status:</span>
                        <span class="ml-2 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $category->is_active ? 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-500/20 text-gray-700 dark:text-gray-400' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $category->is_active ? 'bg-green-500' : 'bg-gray-500' }}"></span>
                            {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-soft-green to-primary rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-xl">edit</span>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Informasi Kategori</h2>
                    <p class="text-sm text-gray-500 dark:text-zinc-400">Update data kategori di form bawah ini</p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            @include('admin.categories._form', [
                'action' => route('admin.categories.update', $category),
                'category' => $category,
                'buttonText' => 'Update Kategori'
            ])
        </div>
    </div>
</div>
@endsection