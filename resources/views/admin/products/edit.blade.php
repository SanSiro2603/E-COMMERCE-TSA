{{-- resources/views/admin/products/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Produk - E-Commerce TSA')

@section('content')
<div class="space-y-6">
    
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-soft-green transition-colors">Dashboard</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <a href="{{ route('admin.products.index') }}" class="hover:text-soft-green transition-colors">Produk</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-gray-900 dark:text-white font-medium">Edit Produk</span>
    </nav>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">Edit Produk</h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Perbarui informasi produk <span class="font-semibold text-soft-green">{{ $product->name }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.index') }}" 
               class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-zinc-200 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Kembali
            </a>
        </div>
    </div>

    <!-- Success/Error Alerts -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-xl mt-0.5">check_circle</span>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-green-900 dark:text-green-300">Berhasil!</h3>
                    <p class="text-sm text-green-800 dark:text-green-400 mt-1">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-xl mt-0.5">error</span>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-red-900 dark:text-red-300">Gagal!</h3>
                    <p class="text-sm text-red-800 dark:text-red-400 mt-1">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Product Info Banner -->
    <div class="bg-gradient-to-r from-soft-green/10 to-primary/10 dark:from-soft-green/5 dark:to-primary/5 border border-soft-green/20 dark:border-soft-green/10 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 bg-soft-green/20 dark:bg-soft-green/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-soft-green text-xl">inventory_2</span>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Informasi Produk Saat Ini</h3>
                <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-sm">
                    <div>
                        <span class="text-gray-500 dark:text-zinc-400">Kategori:</span>
                        <span class="ml-2 text-gray-900 dark:text-white font-medium">{{ $product->category->name ?? '-' }}</span>
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
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Informasi Produk</h2>
                    <p class="text-sm text-gray-500 dark:text-zinc-400">Update data produk di form bawah ini</p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            @include('admin.products._form', [
                'action' => route('admin.products.update', $product),
                'product' => $product,
                'categories' => $categories,
                'buttonText' => 'Update Produk'
            ])
        </div>
    </div>
</div>
@endsection