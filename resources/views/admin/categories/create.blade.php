{{-- resources/views/admin/categories/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tambah Kategori - E-Commerce TSA')

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
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Buat kategori utama lalu tambahkan sub kategorinya</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" 
           class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-zinc-200 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Kembali
        </a>
    </div>

    <!-- Alert sukses simpan kategori utama -->
    @if(session('success_parent'))
        <div class="p-4 text-sm text-green-700 bg-green-100 dark:bg-green-800/40 dark:text-green-300 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success_parent') }}
        </div>
    @endif

    @if(session('success'))
        <div class="p-4 text-sm text-green-700 bg-green-100 dark:bg-green-800/40 dark:text-green-300 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- ===================== FORM KATEGORI UTAMA ===================== -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 dark:bg-blue-500/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-xl">category</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Kategori Utama</h2>
                        <p class="text-sm text-gray-500 dark:text-zinc-400">Contoh: Aves, Reptilia</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="is_active" value="1">
                    <input type="hidden" name="parent_id" value="">
                    <div class="space-y-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Nama Kategori <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   placeholder="Masukkan nama kategori utama"
                                   class="block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                            @error('name')
                                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Deskripsi <span class="text-gray-400 text-xs">(Opsional)</span>
                            </label>
                            <textarea name="description" rows="3" placeholder="Masukkan deskripsi kategori"
                                      class="block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors resize-none">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Gambar <span class="text-gray-400 text-xs">(Opsional, maks. 2MB)</span>
                            </label>
                            <div id="upload-box-utama"
                                 class="border-2 border-dashed border-gray-300 dark:border-zinc-700 rounded-xl p-5 text-center hover:border-soft-green transition-colors cursor-pointer"
                                 onclick="document.getElementById('image-input-utama').click()">
                                <span class="material-symbols-outlined text-3xl text-gray-400 dark:text-zinc-500 mb-1 block">add_photo_alternate</span>
                                <p class="text-xs text-gray-500 dark:text-zinc-500">PNG, JPG, WEBP — Maks 2MB</p>
                            </div>
                            <div id="preview-container-utama" class="hidden mt-2">
                                <div class="relative inline-block">
                                    <img id="preview-img-utama" src="" alt="Preview"
                                         class="w-24 h-24 object-cover rounded-lg border-2 border-soft-green shadow-md">
                                    <button type="button" onclick="removeImageUtama()"
                                            class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow transition-colors">
                                        <span class="material-symbols-outlined text-xs">close</span>
                                    </button>
                                </div>
                                <p id="preview-name-utama" class="text-xs text-gray-500 mt-1"></p>
                            </div>
                            <input type="file" id="image-input-utama" name="image"
                                   accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden"
                                   onchange="previewImageUtama(event)">
                            @error('image')
                                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 px-6 py-2.5 bg-gradient-to-r from-soft-green to-primary text-white font-medium rounded-lg hover:shadow-lg hover:scale-[1.01] transition-all">
                                <span class="material-symbols-outlined text-lg">save</span>
                                Simpan Kategori Utama
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <!-- ===================== FORM SUB KATEGORI ===================== -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden
            {{ $parentCategories->isEmpty() ? 'opacity-60 pointer-events-none' : '' }}">
            <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-50 dark:bg-purple-500/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-xl">account_tree</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Sub Kategori</h2>
                        <p class="text-sm text-gray-500 dark:text-zinc-400">
                            @if($parentCategories->isEmpty())
                                Simpan kategori utama dulu sebelum menambah sub kategori
                            @else
                                Contoh: Broadbill (induk: Aves)
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                @if($parentCategories->isEmpty())
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <div class="w-14 h-14 bg-gray-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-3xl">lock</span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-zinc-400">Tambahkan kategori utama terlebih dahulu</p>
                    </div>
                @else
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="is_active" value="1">
                        <div class="space-y-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                    Nama Sub Kategori <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" required
                                       placeholder="Masukkan nama sub kategori"
                                       class="block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                    Kategori Utama <span class="text-red-500">*</span>
                                </label>
                                <select name="parent_id" required
                                        class="block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                                    <option value="">— Pilih Kategori Utama —</option>
                                    @foreach($parentCategories as $parent)
                                        <option value="{{ $parent->id }}"
                                            {{ session('last_parent_id') == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                    Deskripsi <span class="text-gray-400 text-xs">(Opsional)</span>
                                </label>
                                <textarea name="description" rows="3" placeholder="Masukkan deskripsi sub kategori"
                                          class="block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors resize-none"></textarea>
                            </div>

                            <div class="pt-2">
                                <button type="submit"
                                        class="w-full flex items-center justify-center gap-2 px-6 py-2.5 bg-gradient-to-r from-purple-500 to-purple-700 text-white font-medium rounded-lg hover:shadow-lg hover:scale-[1.01] transition-all">
                                    <span class="material-symbols-outlined text-lg">save</span>
                                    Simpan Sub Kategori
                                </button>
                            </div>

                        </div>
                    </form>
                @endif
            </div>
        </div>

    </div>
</div>

<script>
    function previewImageUtama(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('preview-img-utama').src = e.target.result;
            document.getElementById('preview-name-utama').textContent = file.name;
            document.getElementById('preview-container-utama').classList.remove('hidden');
            document.getElementById('upload-box-utama').classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
    function removeImageUtama() {
        document.getElementById('image-input-utama').value = '';
        document.getElementById('preview-img-utama').src = '';
        document.getElementById('preview-container-utama').classList.add('hidden');
        document.getElementById('upload-box-utama').classList.remove('hidden');
    }
</script>

@endsection