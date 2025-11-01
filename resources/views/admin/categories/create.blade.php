{{-- resources/views/admin/categories/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tambah Kategori - Lembah Hijau')

@section('content')
<div class="space-y-6">
    
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-soft-green transition-colors">Dashboard</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <a href="{{ route('admin.categories.index') }}" class="hover:text-soft-green transition-colors">Kategori</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-gray-900 dark:text-white font-medium">Tambah Kategori</span>
    </nav>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">Tambah Kategori Baru</h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Buat kategori produk baru untuk mengorganisir toko Anda</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" 
           class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-zinc-200 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-soft-green to-primary rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-xl">category</span>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Kategori</h2>
                    <p class="text-sm text-gray-500 dark:text-zinc-400">Lengkapi form di bawah untuk membuat kategori</p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            @include('admin.categories._form', [
                'action' => route('admin.categories.store'),
                'buttonText' => 'Simpan Kategori'
            ])
        </div>
    </div>
</div>
@endsection