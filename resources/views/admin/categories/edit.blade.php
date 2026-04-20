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
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">
                Perbarui informasi
                <span class="font-semibold {{ $category->isChild() ? 'text-purple-500' : 'text-soft-green' }}">
                    {{ $category->name }}
                </span>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-zinc-200 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Kembali
            </a>
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                  onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-lg">delete</span>Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center
                    {{ $category->isChild() ? 'bg-purple-50 dark:bg-purple-500/10' : 'bg-blue-50 dark:bg-blue-500/10' }}">
                    <span class="material-symbols-outlined text-xl
                        {{ $category->isChild() ? 'text-purple-600 dark:text-purple-400' : 'text-blue-600 dark:text-blue-400' }}">
                        {{ $category->isChild() ? 'account_tree' : 'category' }}
                    </span>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Edit {{ $category->isChild() ? 'Sub Kategori' : 'Kategori Utama' }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-zinc-400">Update data di form bawah ini</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            @include('admin.categories._form', [
                'action' => route('admin.categories.update', $category),
                'category' => $category,
                'buttonText' => 'Update ' . ($category->isChild() ? 'Sub Kategori' : 'Kategori Utama'),
                'parentCategories' => $parentCategories,
            ])
        </div>
    </div>
</div>
@endsection