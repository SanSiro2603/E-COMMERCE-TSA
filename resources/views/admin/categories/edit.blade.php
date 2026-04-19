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
            <form action="{{ route('admin.categories.update', $category) }}" method="POST"
                  enctype="{{ $category->isChild() ? 'application/x-www-form-urlencoded' : 'multipart/form-data' }}">
                @csrf
                @method('PUT')

                @if(session('error'))
                    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 dark:bg-red-800 dark:text-red-200 rounded-lg flex items-center gap-2">
                        <span class="material-symbols-outlined">error</span>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="space-y-5">

                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            Nama {{ $category->isChild() ? 'Sub Kategori' : 'Kategori Utama' }}
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name"
                               value="{{ old('name', $category->name) }}" required
                               placeholder="Masukkan nama kategori"
                               class="block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                        @error('name')
                            <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                                <span class="material-symbols-outlined text-sm">error</span>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Pilih Induk — hanya tampil kalau ini sub kategori -->
                    @if($category->isChild())
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Kategori Utama <span class="text-red-500">*</span>
                            </label>
                            <select name="parent_id" required
                                    class="block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                                <option value="">— Pilih Kategori Utama —</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}"
                                        {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                                    <span class="material-symbols-outlined text-sm">error</span>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    @else
                        <input type="hidden" name="parent_id" value="">
                    @endif

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            Deskripsi <span class="text-gray-400 text-xs">(Opsional)</span>
                        </label>
                        <textarea name="description" rows="4" placeholder="Masukkan deskripsi kategori"
                                  class="block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors resize-none">{{ old('description', $category->description) }}</textarea>
                    </div>

                    <!-- Gambar — hanya untuk kategori utama -->
                    @if(!$category->isChild())
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Gambar <span class="text-gray-400 text-xs">(Opsional, maks. 2MB)</span>
                            </label>
                            <div id="image-preview-container" class="mb-3 {{ $category->image ? '' : 'hidden' }}">
                                <div class="relative inline-block">
                                    <img id="image-preview"
                                         src="{{ $category->image ? Storage::url($category->image) : '' }}"
                                         alt="Preview"
                                         class="w-32 h-32 object-cover rounded-xl border-2 border-soft-green shadow-md">
                                    <button type="button" onclick="removeImage()"
                                            class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-md transition-colors">
                                        <span class="material-symbols-outlined text-sm">close</span>
                                    </button>
                                </div>
                                <p id="preview-filename" class="text-xs text-gray-500 dark:text-zinc-400 mt-1"></p>
                            </div>
                            <input type="hidden" name="remove_image" id="remove_image" value="0">
                            <div id="upload-box"
                                 class="{{ $category->image ? 'hidden' : '' }} border-2 border-dashed border-gray-300 dark:border-zinc-700 rounded-xl p-6 text-center hover:border-soft-green transition-colors cursor-pointer"
                                 onclick="document.getElementById('image-input').click()">
                                <span class="material-symbols-outlined text-4xl text-gray-400 dark:text-zinc-500 mb-2 block">add_photo_alternate</span>
                                <p class="text-sm font-medium text-gray-700 dark:text-zinc-300">Klik untuk upload gambar</p>
                                <p class="text-xs text-gray-500 dark:text-zinc-500 mt-1">PNG, JPG, WEBP — Maks 2MB</p>
                            </div>
                            <input type="file" id="image-input" name="image"
                                   accept="image/jpeg,image/png,image/jpg,image/webp"
                                   class="hidden" onchange="previewImage(event)">
                            @error('image')
                                <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                                    <span class="material-symbols-outlined text-sm">error</span>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    @endif

                    <!-- Status Aktif -->
                    <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg border border-gray-200 dark:border-zinc-700">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 mt-0.5 text-soft-green bg-white dark:bg-zinc-700 border-gray-300 dark:border-zinc-600 rounded focus:ring-2 focus:ring-soft-green transition-colors">
                        <label for="is_active" class="flex-1 cursor-pointer">
                            <span class="block text-sm font-medium text-gray-900 dark:text-white">Aktif</span>
                            <span class="block text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Kategori akan ditampilkan di toko</span>
                        </label>
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex items-center gap-3 pt-6 border-t border-gray-200 dark:border-zinc-800">
                    <button type="submit"
                            class="flex items-center gap-2 px-6 py-2.5 text-white font-medium rounded-lg hover:shadow-lg hover:scale-[1.02] transition-all
                                {{ $category->isChild() ? 'bg-gradient-to-r from-purple-500 to-purple-700' : 'bg-gradient-to-r from-soft-green to-primary' }}">
                        <span class="material-symbols-outlined text-lg">save</span>
                        Update {{ $category->isChild() ? 'Sub Kategori' : 'Kategori Utama' }}
                    </button>
                    <a href="{{ route('admin.categories.index') }}"
                       class="flex items-center gap-2 px-6 py-2.5 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
                        <span class="material-symbols-outlined text-lg">close</span>
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
    input[type="checkbox"]:checked { background-color: #7BB661; border-color: #7BB661; }
</style>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('image-preview').src = e.target.result;
            document.getElementById('preview-filename').textContent = file.name;
            document.getElementById('image-preview-container').classList.remove('hidden');
            document.getElementById('upload-box').classList.add('hidden');
            document.getElementById('remove_image').value = '0';
        };
        reader.readAsDataURL(file);
    }
    function removeImage() {
        document.getElementById('image-input').value = '';
        document.getElementById('image-preview').src = '';
        document.getElementById('preview-filename').textContent = '';
        document.getElementById('image-preview-container').classList.add('hidden');
        document.getElementById('upload-box').classList.remove('hidden');
        document.getElementById('remove_image').value = '1';
    }
</script>

@endsection