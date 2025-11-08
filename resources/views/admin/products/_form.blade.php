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
                Harga, Stok & Berat
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

                <!-- Berat -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Berat (gram) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500 text-xl">weight</span>
                        <input type="number" 
                               name="weight" 
                               id="weight"
                               value="{{ old('weight', $product->weight ?? 1000) }}" 
                               required
                               min="1"
                               step="1"
                               placeholder="1000"
                               class="block w-full pl-10 pr-16 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500 text-sm">gram</span>
                    </div>
                    <p class="mt-1.5 text-xs text-gray-500 dark:text-zinc-400 flex items-center gap-1">
                        <span class="material-symbols-outlined text-xs">info</span>
                        <span id="weightInKg">≈ 1 kg</span>
                    </p>
                    @error('weight')
                        <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                            <span class="material-symbols-outlined text-sm">error</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Info Berat -->
            <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/30 rounded-lg p-3">
                <div class="flex gap-2">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-lg">info</span>
                    <div class="flex-1">
                        <p class="text-xs text-blue-700 dark:text-blue-300 font-medium">Informasi Berat Produk</p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-0.5">
                            Berat digunakan untuk menghitung ongkos kirim otomatis. Masukkan berat dalam gram (1 kg = 1000 gram).
                        </p>
                    </div>
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

        <!-- GALERI GAMBAR Section -->
        <div class="space-y-4 pt-6 border-t border-gray-200 dark:border-zinc-800">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-soft-green">photo_library</span>
                Galeri Gambar Produk
            </h3>
            
            <!-- Upload Area -->
            <div>
                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Upload Gambar <span class="text-gray-400 text-xs">(Gambar pertama akan menjadi gambar utama)</span>
                </label>
                
                <div class="border-2 border-dashed border-gray-300 dark:border-zinc-700 rounded-lg p-6 hover:border-soft-green dark:hover:border-soft-green transition-colors bg-gray-50 dark:bg-zinc-800/30">
                    <input type="file" 
                           name="image" 
                           id="main_image"
                           accept="image/jpeg,image/png,image/jpg"
                           class="hidden"
                           onchange="handleMainImage(event)">
                    <input type="file" 
                           name="gallery_images[]" 
                           id="gallery_images"
                           accept="image/jpeg,image/png,image/jpg"
                           multiple
                           class="hidden"
                           onchange="handleGalleryImages(event)">
                    
                    <div class="text-center">
                        <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-5xl mb-3 block">collections</span>
                        <div class="space-y-2">
                            <label for="main_image" class="inline-flex items-center gap-2 px-4 py-2 bg-soft-green text-white rounded-lg hover:bg-soft-green/90 cursor-pointer transition-colors">
                                <span class="material-symbols-outlined text-lg">image</span>
                                <span class="text-sm font-medium">Upload Gambar Utama</span>
                            </label>
                            <span class="mx-2 text-gray-400">atau</span>
                            <label for="gallery_images" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 cursor-pointer transition-colors">
                                <span class="material-symbols-outlined text-lg">add_photo_alternate</span>
                                <span class="text-sm font-medium">Upload Beberapa Gambar</span>
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-3">JPG, PNG (Max 5MB per gambar)</p>
                    </div>
                </div>
                
                @error('image')
                    <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                        <span class="material-symbols-outlined text-sm">error</span>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
                @error('gallery_images.*')
                    <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                        <span class="material-symbols-outlined text-sm">error</span>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <!-- Preview Gallery -->
            <div id="images-preview-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                @if(isset($product) && !empty($product->images))
                    @foreach($product->images as $index => $image)
                        <div class="relative group image-item" data-existing="true">
                            <div class="relative aspect-square rounded-lg overflow-hidden border-2 {{ $index === 0 ? 'border-blue-500' : 'border-gray-200 dark:border-zinc-700' }}">
                                <img src="{{ asset('storage/' . $image) }}" 
                                     alt="Image {{ $index + 1 }}"
                                     class="w-full h-full object-cover">
                                
                                @if($index === 0)
                                    <div class="absolute top-2 left-2 px-2 py-1 bg-blue-500 text-white text-[10px] font-bold rounded-full shadow-lg">
                                        UTAMA
                                    </div>
                                @else
                                    <div class="absolute top-2 left-2 px-2 py-1 bg-gray-700/80 text-white text-[10px] rounded-full">
                                        #{{ $index + 1 }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Remove Button -->
                            <label class="absolute top-2 right-2 cursor-pointer">
                                <input type="checkbox" 
                                       name="remove_images[]" 
                                       value="{{ $image }}" 
                                       class="peer hidden"
                                       onchange="toggleRemoveImage(this)">
                                <div class="w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 peer-checked:opacity-100 peer-checked:bg-red-600 transition-all shadow-lg">
                                    <span class="material-symbols-outlined text-sm">close</span>
                                </div>
                            </label>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Info Tips -->
            <div class="bg-purple-50 dark:bg-purple-500/10 border border-purple-200 dark:border-purple-500/30 rounded-lg p-3">
                <div class="flex gap-2">
                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-lg">lightbulb</span>
                    <div class="flex-1">
                        <p class="text-xs text-purple-700 dark:text-purple-300 font-medium">Tips Upload Gambar</p>
                        <ul class="text-xs text-purple-600 dark:text-purple-400 mt-1 space-y-1 list-disc list-inside">
                            <li>Gambar pertama akan menjadi gambar utama di katalog</li>
                            <li>Upload beberapa gambar untuk galeri produk yang lebih menarik</li>
                            <li>Gunakan gambar dengan pencahayaan yang baik dan jelas</li>
                            <li>Foto dari berbagai sudut akan membantu pembeli</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sertifikat Kesehatan Section -->
        <div class="space-y-4 pt-6 border-t border-gray-200 dark:border-zinc-800">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-soft-green">verified</span>
                Sertifikat Kesehatan
            </h3>
            
            <div>
                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Upload Sertifikat <span class="text-gray-400 text-xs">(PDF - Max 5MB, Opsional)</span>
                </label>
                <div class="border-2 border-dashed border-gray-300 dark:border-zinc-700 rounded-lg p-4 hover:border-soft-green dark:hover:border-soft-green transition-colors">
                    <input type="file" 
                           name="health_certificate" 
                           id="health_certificate"
                           accept=".pdf"
                           class="hidden">
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
    input[type="checkbox"]:checked {
        background-color: #7BB661;
        border-color: #7BB661;
    }
    
    .image-item.removing {
        opacity: 0.5;
        transition: opacity 0.3s;
    }
    
    .image-item.removing img {
        filter: grayscale(100%);
    }
</style>

<script>
// Weight conversion display
const weightInput = document.getElementById('weight');
const weightInKgDisplay = document.getElementById('weightInKg');

function updateWeightDisplay() {
    const grams = parseFloat(weightInput.value) || 0;
    const kg = (grams / 1000).toFixed(2);
    weightInKgDisplay.textContent = `≈ ${kg} kg`;
}

weightInput?.addEventListener('input', updateWeightDisplay);
updateWeightDisplay();

// Image preview counter
let previewImageCount = {{ isset($product) && !empty($product->images) ? count($product->images) : 0 }};

// Handle main image upload
function handleMainImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        addImagePreview(e.target.result, 'Utama (Baru)', true, true);
    };
    reader.readAsDataURL(file);
}

// Handle multiple gallery images upload
function handleGalleryImages(event) {
    const files = Array.from(event.target.files);
    
    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imageNum = previewImageCount + index + 1;
            addImagePreview(e.target.result, `Baru #${imageNum}`, false, true);
        };
        reader.readAsDataURL(file);
    });
    
    previewImageCount += files.length;
}

// Add image preview to gallery
function addImagePreview(imageSrc, label, isMain = false, isNew = false) {
    const container = document.getElementById('images-preview-container');
    
    const div = document.createElement('div');
    div.className = 'relative group image-item';
    div.innerHTML = `
        <div class="relative aspect-square rounded-lg overflow-hidden border-2 ${isMain ? 'border-blue-500' : 'border-green-500'}">
            <img src="${imageSrc}" alt="${label}" class="w-full h-full object-cover">
            <div class="absolute top-2 left-2 px-2 py-1 ${isMain ? 'bg-blue-500' : 'bg-green-500'} text-white text-[10px] font-bold rounded-full shadow-lg">
                ${label}
            </div>
        </div>
    `;
    
    container.appendChild(div);
}

// Toggle remove image visual feedback
function toggleRemoveImage(checkbox) {
    const imageItem = checkbox.closest('.image-item');
    if (checkbox.checked) {
        imageItem.classList.add('removing');
    } else {
        imageItem.classList.remove('removing');
    }
}

// Show selected PDF filename
document.getElementById('health_certificate')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const label = this.nextElementSibling.querySelector('.text-sm');
        label.textContent = `📄 ${file.name}`;
    }
});
</script>