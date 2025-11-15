{{-- resources/views/admin/products/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tambah Produk - E-Commerce TSA')

@section('content')
<div class="space-y-6">
    
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-soft-green transition-colors">Dashboard</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <a href="{{ route('admin.products.index') }}" class="hover:text-soft-green transition-colors">Produk</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-gray-900 dark:text-white font-medium">Tambah Produk</span>
    </nav>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">Tambah Produk Baru</h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Buat produk baru untuk ditampilkan di toko Anda</p>
        </div>
        <a href="{{ route('admin.products.index') }}" 
           class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-zinc-200 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Kembali
        </a>
    </div>

    <!-- Alert if no categories -->
    @if($categories->isEmpty())
        <div class="bg-yellow-50 dark:bg-yellow-500/10 border border-yellow-200 dark:border-yellow-500/20 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-xl mt-0.5">warning</span>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-yellow-900 dark:text-yellow-300">Belum Ada Kategori</h3>
                    <p class="text-sm text-yellow-800 dark:text-yellow-400 mt-1">Anda perlu membuat kategori terlebih dahulu sebelum menambahkan produk.</p>
                    <a href="{{ route('admin.categories.create') }}" 
                       class="inline-flex items-center gap-2 mt-3 px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-base">add</span>
                        Buat Kategori Sekarang
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-soft-green to-primary rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-xl">inventory_2</span>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Produk</h2>
                    <p class="text-sm text-gray-500 dark:text-zinc-400">Lengkapi semua informasi produk di bawah ini</p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            @include('admin.products._form', [
                'action' => route('admin.products.store'),
                'categories' => $categories,
                'buttonText' => 'Simpan Produk'
            ])
        </div>
    </div>

   
</div>
@endsection