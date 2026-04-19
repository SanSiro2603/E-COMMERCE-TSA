<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($category)) @method('PUT') @endif

    <div class="space-y-6">

        @if(session('error'))
            <div class="p-4 text-sm text-red-700 bg-red-100 dark:bg-red-800 dark:text-red-200 rounded-lg flex items-center gap-2">
                <span class="material-symbols-outlined">error</span>
                {{ session('error') }}
            </div>
        @endif

        <!-- Nama Kategori -->
        <div>
            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                Nama Kategori <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required
                   placeholder="Masukkan nama kategori"
                   class="block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
            @error('name')
                <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                    <span class="material-symbols-outlined text-sm">error</span><span>{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Kategori Induk -->
        <div>
            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                Kategori Induk
                <span class="text-gray-400 text-xs">(Kosongkan jika ini Kategori Utama)</span>
            </label>
            <select name="parent_id"
                    class="block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                <option value="">— Tidak ada (Kategori Utama) —</option>
                @foreach($parentCategories ?? [] as $parent)
                    <option value="{{ $parent->id }}"
                        {{ old('parent_id', $category->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                        {{ $parent->name }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1.5">
                Pilih kategori utama jika ini adalah sub kategori (contoh: induk = <em>Aves</em>, sub = <em>Broadbill</em>)
            </p>
            @error('parent_id')
                <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                    <span class="material-symbols-outlined text-sm">error</span><span>{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Deskripsi -->
        <div>
            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                Deskripsi <span class="text-gray-400 text-xs">(Opsional)</span>
            </label>
            <textarea name="description" rows="4" placeholder="Masukkan deskripsi kategori"
                      class="block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors resize-none">{{ old('description', $category->description ?? '') }}</textarea>
            @error('description')
                <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                    <span class="material-symbols-outlined text-sm">error</span><span>{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Gambar Kategori -->
        <div>
            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                Gambar Kategori <span class="text-gray-400 text-xs">(Opsional, maks. 2MB)</span>
            </label>
            <div id="image-preview-container" class="mb-3 {{ isset($category) && $category->image ? '' : 'hidden' }}">
                <div class="relative inline-block">
                    <img id="image-preview" src="{{ isset($category) && $category->image ? Storage::url($category->image) : '' }}" alt="Preview"
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
                 class="{{ isset($category) && $category->image ? 'hidden' : '' }} border-2 border-dashed border-gray-300 dark:border-zinc-700 rounded-xl p-6 text-center hover:border-soft-green transition-colors cursor-pointer"
                 onclick="document.getElementById('image-input').click()">
                <span class="material-symbols-outlined text-4xl text-gray-400 dark:text-zinc-500 mb-2 block">add_photo_alternate</span>
                <p class="text-sm font-medium text-gray-700 dark:text-zinc-300">Klik untuk upload gambar</p>
                <p class="text-xs text-gray-500 dark:text-zinc-500 mt-1">PNG, JPG, WEBP — Maks 2MB</p>
            </div>
            <input type="file" id="image-input" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewImage(event)">
            @error('image')
                <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                    <span class="material-symbols-outlined text-sm">error</span><span>{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Status Aktif -->
        <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg border border-gray-200 dark:border-zinc-700">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                   {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
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
                class="flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-soft-green to-primary text-white font-medium rounded-lg hover:shadow-lg hover:scale-[1.02] transition-all">
            <span class="material-symbols-outlined text-lg">save</span>
            {{ $buttonText ?? 'Simpan Kategori' }}
        </button>
        <a href="{{ route('admin.categories.index') }}"
           class="flex items-center gap-2 px-6 py-2.5 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
            <span class="material-symbols-outlined text-lg">close</span>
            Batal
        </a>
    </div>
</form>

<style>
    input[type="checkbox"]:checked { background-color: #7BB661; border-color: #7BB661; }
    .animate-fade { animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
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