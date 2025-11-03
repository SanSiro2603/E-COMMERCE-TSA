{{-- resources/views/admin/products/_form.blade.php --}}
<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($product)) @method('PUT') @endif

    <div class="space-y-6">
        
        <!-- Informasi Dasar Section -->
        <div class="space-y-4">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-soft-green">info</span>
                Informasi Dasar
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500 text-xl">category</span>
                        <select name="category_id" 
                                required
                                class="block w-full pl-10 pr-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('category_id')
                        <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                            <span class="material-symbols-outlined text-sm">error</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Nama Produk -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $product->name ?? '') }}" 
                           required
                           placeholder="Contoh: Kambing Etawa Jantan"
                           class="block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                    @error('name')
                        <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                            <span class="material-symbols-outlined text-sm">error</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Deskripsi (Full Width) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Deskripsi <span class="text-gray-400 text-xs">(Opsional)</span>
                    </label>
                    <textarea name="description" 
                              rows="4"
                              placeholder="Masukkan deskripsi detail produk..."
                              class="block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors resize-none">{{ old('description', $product->description ?? '') }}</textarea>
                    @error('description')
                        <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                            <span class="material-symbols-outlined text-sm">error</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Harga & Stok Section -->
        <div class="space-y-4 pt-6 border-t border-gray-200 dark:border-zinc-800">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-soft-green">payments</span>
                Harga & Stok
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Harga -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Harga (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-zinc-400 text-sm font-medium">Rp</span>
                        <input type="number" 
                               name="price" 
                               value="{{ old('price', $product->price ?? '') }}" 
                               required
                               min="0"
                               step="1000"
                               placeholder="0"
                               class="block w-full pl-10 pr-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                    </div>
                    @error('price')
                        <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                            <span class="material-symbols-outlined text-sm">error</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Stok -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Stok <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500 text-xl">inventory</span>
                        <input type="number" 
                               name="stock" 
                               value="{{ old('stock', $product->stock ?? '') }}" 
                               required
                               min="0"
                               placeholder="0"
                               class="block w-full pl-10 pr-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                    </div>
                    @error('stock')
                        <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                            <span class="material-symbols-outlined text-sm">error</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Ketersediaan & Status Section -->
        <div class="space-y-4 pt-6 border-t border-gray-200 dark:border-zinc-800">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-soft-green">schedule</span>
                Ketersediaan & Status
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Tersedia Dari -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Tersedia Dari <span class="text-gray-400 text-xs">(Opsional)</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500 text-xl">calendar_month</span>
                        <input type="date" 
                               name="available_from" 
                               value="{{ old('available_from', $product->available_from ?? '') }}"
                               class="block w-full pl-10 pr-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                    </div>
                    @error('available_from')
                        <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                            <span class="material-symbols-outlined text-sm">error</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Status Aktif -->
                <div class="flex items-center">
                    <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg border border-gray-200 dark:border-zinc-700 w-full">
                        <input type="checkbox" 
                               name="is_active" 
                               id="is_active"
                               value="1"
                               {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 mt-0.5 text-soft-green bg-white dark:bg-zinc-700 border-gray-300 dark:border-zinc-600 rounded focus:ring-2 focus:ring-soft-green transition-colors">
                        <label for="is_active" class="flex-1 cursor-pointer">
                            <span class="block text-sm font-medium text-gray-900 dark:text-white">Aktif</span>
                            <span class="block text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Produk akan ditampilkan di toko</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Media Section -->
        <div class="space-y-4 pt-6 border-t border-gray-200 dark:border-zinc-800">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-soft-green">cloud_upload</span>
                Upload Media
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Gambar Produk -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Gambar Produk <span class="text-gray-400 text-xs">(JPG, PNG - Max  10MB)</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-zinc-700 rounded-lg p-4 hover:border-soft-green dark:hover:border-soft-green transition-colors">
                        <input type="file" 
                               name="image" 
                               id="image"
                               accept="image/*"
                               class="hidden">
                        <label for="image" class="cursor-pointer block">
                            <div class="flex flex-col items-center justify-center py-3">
                                <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-4xl mb-2">image</span>
                                <span class="text-sm font-medium text-gray-700 dark:text-zinc-300">Klik untuk upload gambar</span>
                                <span class="text-xs text-gray-500 dark:text-zinc-400 mt-1">atau drag & drop di sini</span>
                            </div>
                        </label>
                    </div>
                    @if(isset($product) && $product->image)
                        <div class="mt-3 relative inline-block">
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 class="h-24 w-24 object-cover rounded-lg border-2 border-gray-200 dark:border-zinc-700">
                            <span class="absolute -top-2 -right-2 bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">Current</span>
                        </div>
                    @endif
                    @error('image')
                        <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                            <span class="material-symbols-outlined text-sm">error</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Sertifikat Kesehatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Sertifikat Kesehatan <span class="text-gray-400 text-xs">(PDF - Max 5MB)</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-zinc-700 rounded-lg p-4 hover:border-soft-green dark:hover:border-soft-green transition-colors">
                        <input type="file" 
                               name="health_certificate" 
                               id="health_certificate"
                               accept=".pdf">
                        <label for="health_certificate" class="cursor-pointer block">
                            <div class="flex flex-col items-center justify-center py-3">
                                <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-4xl mb-2">description</span>
                                <span class="text-sm font-medium text-gray-700 dark:text-zinc-300">Klik untuk upload PDF</span>
                                <span class="text-xs text-gray-500 dark:text-zinc-400 mt-1">atau drag & drop di sini</span>
                            </div>
                        </label>
                    </div>
                    @if(isset($product) && $product->health_certificate)
                        <a href="{{ asset('storage/' . $product->health_certificate) }}" 
                           target="_blank"
                           class="inline-flex items-center gap-2 mt-3 px-3 py-2 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-xs font-medium rounded-lg hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors">
                            <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                            Lihat Sertifikat Saat Ini
                        </a>
                    @endif
                    @error('health_certificate')
                        <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                            <span class="material-symbols-outlined text-sm">error</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>
        </div>

    </div>

    <!-- Action Buttons -->
    <div class="mt-8 flex items-center gap-3 pt-6 border-t border-gray-200 dark:border-zinc-800">
        <button type="submit"
                class="flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-soft-green to-primary text-white font-medium rounded-lg hover:shadow-lg hover:scale-[1.02] transition-all">
            <span class="material-symbols-outlined text-lg">save</span>
            {{ $buttonText ?? 'Simpan Produk' }}
        </button>
        <a href="{{ route('admin.products.index') }}" 
           class="flex items-center gap-2 px-6 py-2.5 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
            <span class="material-symbols-outlined text-lg">close</span>
            Batal
        </a>
    </div>
</form>

<style>
    /* Custom checkbox styling */
    input[type="checkbox"]:checked {
        background-color: #7BB661;
        border-color: #7BB661;
    }
    
    /* File input styling */
    input[type="file"] {
        cursor: pointer;
    }
</style>

<script>
    // Preview image before upload
    document.getElementById('image')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('img');
                preview.src = e.target.result;
                preview.className = 'mt-3 h-24 w-24 object-cover rounded-lg border-2 border-gray-200 dark:border-zinc-700';
                
                const container = document.getElementById('image').closest('div');
                const existingPreview = container.querySelector('img');
                if (existingPreview) {
                    existingPreview.replaceWith(preview);
                } else {
                    container.appendChild(preview);
                }
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Show selected PDF filename
    document.getElementById('health_certificate')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const label = this.nextElementSibling.querySelector('.text-sm');
            label.textContent = file.name;
        }
    });
</script>