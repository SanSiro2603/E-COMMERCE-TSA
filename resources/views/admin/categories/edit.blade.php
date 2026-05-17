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
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" id="delete-form">
                @csrf @method('DELETE')
                <button type="button"
                        onclick="confirmDelete()"
                        class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-lg">delete</span>Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
            <div class="flex items-center justify-between gap-3">
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

                {{-- Badge field wajib (seragam dengan halaman produk) --}}
                <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5
                            bg-red-50 dark:bg-red-500/10
                            border border-red-200 dark:border-red-500/20
                            text-red-600 dark:text-red-400
                            text-xs font-medium rounded-full flex-shrink-0">
                    <span class="text-red-500 font-bold text-sm leading-none">*</span>
                    Field wajib diisi
                </span>
            </div>
        </div>

        <div class="p-6">
            @include('admin.categories._form', [
                'action'           => route('admin.categories.update', $category),
                'category'         => $category,
                'buttonText'       => 'Update ' . ($category->isChild() ? 'Sub Kategori' : 'Kategori Utama'),
                'parentCategories' => $parentCategories,
            ])
        </div>
    </div>

    {{-- ── INFO SUB KATEGORI (hanya untuk kategori utama) ── --}}
    @if(!$category->isChild())
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-50 dark:bg-purple-500/10 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-xl">account_tree</span>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Sub Kategori</h2>
                            <p class="text-sm text-gray-500 dark:text-zinc-400">
                                {{ $category->children->count() }} sub kategori dalam <span class="font-medium text-soft-green">{{ $category->name }}</span>
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin.categories.create') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-gradient-to-r from-soft-green to-primary rounded-lg hover:shadow-md transition-all">
                        <span class="material-symbols-outlined text-sm">add</span>
                        Tambah Sub
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($category->children->isEmpty())
                    <div class="flex flex-col items-center justify-center py-6 text-center">
                        <div class="w-12 h-12 bg-gray-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-2xl">folder_open</span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-zinc-400">Belum ada sub kategori</p>
                        <a href="{{ route('admin.categories.create') }}"
                           class="mt-3 text-xs text-soft-green hover:underline">
                            + Tambahkan sekarang
                        </a>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach($category->children as $sub)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-zinc-800/50 rounded-lg border border-gray-200 dark:border-zinc-700 hover:border-purple-300 dark:hover:border-purple-700 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($sub->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $sub->name }}</p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <code class="text-[10px] text-gray-500 dark:text-zinc-400 bg-gray-100 dark:bg-zinc-700 px-1.5 py-0.5 rounded">/{{ $sub->slug }}</code>
                                            <span class="inline-flex items-center gap-1 text-[10px] text-purple-600 dark:text-purple-400">
                                                <span class="material-symbols-outlined text-xs">inventory_2</span>
                                                {{ $sub->products_count ?? 0 }} produk
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($sub->is_active)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 rounded-full text-[10px] font-semibold">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 dark:bg-zinc-700 text-gray-500 dark:text-zinc-400 rounded-full text-[10px] font-semibold">
                                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>Nonaktif
                                        </span>
                                    @endif
                                    <a href="{{ route('admin.categories.edit', $sub) }}"
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 rounded-lg hover:bg-blue-100 transition-colors">
                                        <span class="material-symbols-outlined text-sm">edit</span>Edit
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- ── TIMESTAMP INFO ── --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-5">
        <div class="flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-lg">schedule</span>
            <h3 class="text-sm font-semibold text-gray-700 dark:text-zinc-300">Informasi Waktu</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-zinc-800/50 rounded-lg">
                <div class="w-8 h-8 bg-blue-50 dark:bg-blue-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-blue-500 text-sm">add_circle</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400">Dibuat</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $category->created_at->locale('id')->isoFormat('D MMMM YYYY') }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-zinc-500">
                        {{ $category->created_at->locale('id')->isoFormat('HH:mm') }} WIB
                        · {{ $category->created_at->locale('id')->diffForHumans() }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-zinc-800/50 rounded-lg">
                <div class="w-8 h-8 bg-green-50 dark:bg-green-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-green-500 text-sm">update</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400">Terakhir Diupdate</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $category->updated_at->locale('id')->isoFormat('D MMMM YYYY') }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-zinc-500">
                        {{ $category->updated_at->locale('id')->isoFormat('HH:mm') }} WIB
                        · {{ $category->updated_at->locale('id')->diffForHumans() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Spacer supaya konten tidak ketutupan sticky bar --}}
<div class="h-20"></div>

{{-- Sticky Action Bar --}}
<div class="fixed bottom-0 left-0 right-0 z-40
            bg-white/80 dark:bg-zinc-900/80
            backdrop-blur-md
            border-t border-gray-200 dark:border-zinc-800
            px-6 py-3 lg:pl-72">
    <div class="max-w-screen-xl mx-auto flex items-center gap-4 min-w-0">

        {{-- Hint kiri --}}
        <p class="text-xs text-gray-500 dark:text-zinc-400 hidden lg:flex items-center gap-1 min-w-0">
            <span class="material-symbols-outlined text-sm text-yellow-500 flex-shrink-0">info</span>
            <span class="truncate">Pastikan semua field wajib (<span class="text-red-500 font-bold">*</span>) sudah terisi</span>
        </p>

        {{-- Tombol kanan --}}
        <div class="flex items-center gap-3 ml-auto">
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-2 px-5 py-2 border border-gray-300 dark:border-zinc-700
                      rounded-lg text-sm text-gray-700 dark:text-zinc-300
                      bg-white dark:bg-zinc-800
                      hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
                <span class="material-symbols-outlined text-base">close</span>
                Batal
            </a>
            <button type="submit" form="category-form"
                    class="flex items-center gap-2 px-6 py-2 text-white text-sm font-medium rounded-lg
                           hover:shadow-lg hover:scale-[1.02] transition-all
                           {{ $category->isChild()
                               ? 'bg-gradient-to-r from-purple-500 to-purple-700'
                               : 'bg-gradient-to-r from-soft-green to-primary' }}">
                <span class="material-symbols-outlined text-base">save</span>
                Update {{ $category->isChild() ? 'Sub Kategori' : 'Kategori Utama' }}
            </button>
        </div>

    </div>
</div>

<script>
function confirmDelete() {
    Swal.fire({
        title: 'Hapus Kategori?',
        html: `Yakin ingin menghapus <strong>{{ $category->name }}</strong>?
            @if(!$category->isChild())
            <br><span class="text-sm text-red-400 mt-1 block">⚠️ Sub-kategorinya harus dihapus dulu.</span>
            @endif`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form').submit();
        }
    });
}
</script>

@endsection