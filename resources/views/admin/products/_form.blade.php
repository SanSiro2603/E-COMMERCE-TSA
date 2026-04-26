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

                <!-- Kategori Utama -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Kategori Utama <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500 text-xl">category</span>
                        <select name="parent_category_id"
                                id="parent_category_id"
                                class="block w-full pl-10 pr-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                            <option value="">— Pilih Kategori Utama —</option>
                            @foreach($parentCategories ?? [] as $parent)
                                <option value="{{ $parent->id }}"
                                        data-children="{{ $parent->children->toJson() }}"
                                        {{ (old('parent_category_id', $product->category->parent_id ?? ($product->category_id ?? ''))) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Sub Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Sub Kategori <span class="text-gray-400 text-xs">(Pilih kategori utama dulu)</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500 text-xl">account_tree</span>
                        <select name="category_id"
                                id="sub_category_id"
                                class="block w-full pl-10 pr-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                            <option value="">— Pilih Sub Kategori —</option>
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
                           placeholder="Contoh: Madi Kelam"
                           class="block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                    @error('name')
                        <div class="flex items-center gap-1 mt-2 text-xs text-red-600 dark:text-red-400">
                            <span class="material-symbols-outlined text-sm">error</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Unit -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Satuan <span class="text-gray-400 text-xs">(Opsional)</span>
                    </label>
                    <input type="text"
                           name="unit"
                           value="{{ old('unit', $product->unit ?? '') }}"
                           placeholder="Contoh: ekor, pasang, kg"
                           class="block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Deskripsi <span class="text-gray-400 text-xs">(Opsional)</span>
                    </label>
                    <textarea name="description"
                              rows="4"
                              placeholder="Masukkan deskripsi detail produk..."
                              class="block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors resize-none">{{ old('description', $product->description ?? '') }}</textarea>
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
                               required min="0" step="1000" placeholder="0"
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
                               required min="0" placeholder="0"
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
                               required min="1" step="1" placeholder="1000"
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

            <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/30 rounded-lg p-3">
                <div class="flex gap-2">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-lg">info</span>
                    <div>
                        <p class="text-xs text-blue-700 dark:text-blue-300 font-medium">Informasi Berat Produk</p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-0.5">Berat digunakan untuk menghitung ongkos kirim otomatis. 1 kg = 1000 gram.</p>
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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                            <span class="block text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Produk ditampilkan di toko</span>
                        </label>
                    </div>
                </div>

                <!-- Rekomendasi -->
                <div class="flex items-center">
                    <div class="flex items-start gap-3 p-4 bg-yellow-50 dark:bg-yellow-500/10 rounded-lg border border-yellow-200 dark:border-yellow-500/30 w-full">
                        <input type="checkbox"
                               name="is_featured"
                               id="is_featured"
                               value="1"
                               {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}
                               class="w-4 h-4 mt-0.5 text-soft-green bg-white dark:bg-zinc-700 border-gray-300 dark:border-zinc-600 rounded focus:ring-2 focus:ring-soft-green transition-colors">
                        <label for="is_featured" class="flex-1 cursor-pointer">
                            <span class="block text-sm font-medium text-gray-900 dark:text-white flex items-center gap-1">
                                <span class="material-symbols-outlined text-yellow-500 text-base">star</span>
                                Rekomendasi
                            </span>
                            <span class="block text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Tampil di "Rekomendasi Hewan" dashboard pembeli</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Galeri Gambar Section -->
        <div class="space-y-4 pt-6 border-t border-gray-200 dark:border-zinc-800">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-soft-green">photo_library</span>
                Galeri Gambar Produk
            </h3>

            <div>
                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Upload Gambar <span class="text-gray-400 text-xs">(Gambar pertama akan menjadi gambar utama)</span>
                </label>

                <div class="border-2 border-dashed border-gray-300 dark:border-zinc-700 rounded-lg p-6 hover:border-soft-green transition-colors bg-gray-50 dark:bg-zinc-800/30">
                    <input type="file" name="image" id="main_image"
                           accept="image/jpeg,image/png,image/jpg" class="hidden"
                           onchange="handleMainImage(event)">
                    <input type="file" name="gallery_images[]" id="gallery_images"
                           accept="image/jpeg,image/png,image/jpg" multiple class="hidden"
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
            </div>

            <!-- Preview Gallery -->
            <div id="images-preview-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                @if(isset($product) && !empty($product->images))
                    @foreach($product->images as $index => $image)
                        <div class="relative group image-item" data-existing="true">
                            <div class="relative aspect-square rounded-lg overflow-hidden border-2 {{ $index === 0 ? 'border-blue-500' : 'border-gray-200 dark:border-zinc-700' }}">
                                <img src="{{ asset('storage/' . $image) }}" alt="Image {{ $index + 1 }}" class="w-full h-full object-cover">
                                <div class="absolute top-2 left-2 px-2 py-1 {{ $index === 0 ? 'bg-blue-500' : 'bg-gray-700/80' }} text-white text-[10px] font-bold rounded-full shadow-lg">
                                    {{ $index === 0 ? 'UTAMA' : '#' . ($index + 1) }}
                                </div>
                            </div>
                            <label class="absolute top-2 right-2 cursor-pointer">
                                <input type="checkbox" name="remove_images[]" value="{{ $image }}" class="peer hidden" onchange="toggleRemoveImage(this)">
                                <div class="w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 peer-checked:opacity-100 peer-checked:bg-red-600 transition-all shadow-lg">
                                    <span class="material-symbols-outlined text-sm">close</span>
                                </div>
                            </label>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="bg-purple-50 dark:bg-purple-500/10 border border-purple-200 dark:border-purple-500/30 rounded-lg p-3">
                <div class="flex gap-2">
                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-lg">lightbulb</span>
                    <div>
                        <p class="text-xs text-purple-700 dark:text-purple-300 font-medium">Tips Upload Gambar</p>
                        <ul class="text-xs text-purple-600 dark:text-purple-400 mt-1 space-y-1 list-disc list-inside">
                            <li>Gambar pertama akan menjadi gambar utama di katalog</li>
                            <li>Upload beberapa gambar untuk galeri produk yang lebih menarik</li>
                            <li>Gunakan gambar dengan pencahayaan yang baik dan jelas</li>
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
                <div class="border-2 border-dashed border-gray-300 dark:border-zinc-700 rounded-lg p-4 hover:border-soft-green transition-colors">
                    <input type="file" name="health_certificate" id="health_certificate" accept=".pdf" class="hidden">
                    <label for="health_certificate" class="cursor-pointer block">
                        <div class="flex flex-col items-center justify-center py-3">
                            <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-4xl mb-2">description</span>
                            <span class="text-sm font-medium text-gray-700 dark:text-zinc-300" id="cert-label">Klik untuk upload PDF</span>
                            <span class="text-xs text-gray-500 dark:text-zinc-400 mt-1">atau drag & drop di sini</span>
                        </div>
                    </label>
                </div>

                @if(isset($product) && $product->health_certificate)
                    <a href="{{ asset('storage/' . $product->health_certificate) }}" target="_blank"
                       class="inline-flex items-center gap-2 mt-3 px-3 py-2 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-xs font-medium rounded-lg hover:bg-blue-100 transition-colors">
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
    input[type="checkbox"]:checked { background-color: #7BB661; border-color: #7BB661; }
    .image-item.removing { opacity: 0.5; transition: opacity 0.3s; }
    .image-item.removing img { filter: grayscale(100%); }
</style>

<script>
// ===================== KATEGORI =====================
(function() {
    const parentSelect  = document.getElementById('parent_category_id');
    const subSelect     = document.getElementById('sub_category_id');
    const savedSubId    = "{{ old('category_id', $product->category_id ?? '') }}";
    const savedParentId = "{{ old('parent_category_id', isset($product) ? ($product->category->parent_id ?? $product->category_id) : '') }}";

    function populateSub(children, selectedId = null) {
        subSelect.innerHTML = '';

        if (!children || children.length === 0) {
            subSelect.innerHTML = '<option value="">Tidak ada sub kategori</option>';
            return;
        }

        const defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.textContent = '— Pilih Sub Kategori —';
        subSelect.appendChild(defaultOpt);

        children.forEach(child => {
            const opt = document.createElement('option');
            opt.value = child.id;
            opt.textContent = child.name;
            if (String(child.id) === String(selectedId)) opt.selected = true;
            subSelect.appendChild(opt);
        });
    }

    parentSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const children = selected.dataset.children ? JSON.parse(selected.dataset.children) : [];

        if (children.length === 0 && this.value) {
            // Tidak ada sub → pakai parent langsung
            subSelect.innerHTML = `<option value="${this.value}" selected>— (Gunakan kategori ini langsung) —</option>`;
        } else {
            populateSub(children);
        }
    });

    // Auto restore saat edit
    if (savedParentId) {
        const parentOption = parentSelect.querySelector(`option[value="${savedParentId}"]`);
        if (parentOption) {
            parentOption.selected = true;
            const children = parentOption.dataset.children ? JSON.parse(parentOption.dataset.children) : [];

            if (children.length === 0 && savedParentId) {
                subSelect.innerHTML = `<option value="${savedParentId}" selected>— (Gunakan kategori ini langsung) —</option>`;
            } else {
                populateSub(children, savedSubId);
            }
        }
    }
})();

// ===================== BERAT =====================
const weightInput      = document.getElementById('weight');
const weightInKgDisplay = document.getElementById('weightInKg');

function updateWeightDisplay() {
    const grams = parseFloat(weightInput.value) || 0;
    weightInKgDisplay.textContent = `≈ ${(grams / 1000).toFixed(2)} kg`;
}
weightInput?.addEventListener('input', updateWeightDisplay);
updateWeightDisplay();

// ===================== GAMBAR =====================
let previewImageCount = {{ isset($product) && !empty($product->images) ? count($product->images) : 0 }};

function handleMainImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => addImagePreview(e.target.result, 'Utama (Baru)', true);
    reader.readAsDataURL(file);
}

function handleGalleryImages(event) {
    const files = Array.from(event.target.files);
    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => addImagePreview(e.target.result, `Baru #${previewImageCount + index + 1}`, false);
        reader.readAsDataURL(file);
    });
    previewImageCount += files.length;
}

function addImagePreview(imageSrc, label, isMain = false) {
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

function toggleRemoveImage(checkbox) {
    const imageItem = checkbox.closest('.image-item');
    imageItem.classList.toggle('removing', checkbox.checked);
}

// ===================== SERTIFIKAT =====================
document.getElementById('health_certificate')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) document.getElementById('cert-label').textContent = `📄 ${file.name}`;
});
</script>