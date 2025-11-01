<form action="{{ $action }}" method="POST">
    @csrf
    @if(isset($category)) @method('PUT') @endif

    <div class="space-y-6">
        <!-- Nama Kategori -->
        <div>
            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                Nama Kategori <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="name" 
                   value="{{ old('name', $category->name ?? '') }}" 
                   required
                   placeholder="Masukkan nama kategori"
                   class="block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:focus:ring-soft-green dark:focus:border-soft-green transition-colors">
            @error('name')
                <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                    <span class="material-symbols-outlined text-sm">error</span>
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Deskripsi -->
        <div>
            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                Deskripsi <span class="text-gray-400 text-xs">(Opsional)</span>
            </label>
            <textarea name="description" 
                      rows="4"
                      placeholder="Masukkan deskripsi kategori..."
                      class="block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:focus:ring-soft-green dark:focus:border-soft-green transition-colors resize-none">{{ old('description', $category->description ?? '') }}</textarea>
            @error('description')
                <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                    <span class="material-symbols-outlined text-sm">error</span>
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Status Aktif -->
        <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg border border-gray-200 dark:border-zinc-700">
            <input type="checkbox" 
                   name="is_active" 
                   id="is_active"
                   value="1"
                   {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                   class="w-4 h-4 mt-0.5 text-soft-green bg-white dark:bg-zinc-700 border-gray-300 dark:border-zinc-600 rounded focus:ring-2 focus:ring-soft-green dark:focus:ring-soft-green transition-colors">
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
    /* Custom checkbox styling for better visibility */
    input[type="checkbox"]:checked {
        background-color: #7BB661;
        border-color: #7BB661;
    }
</style>