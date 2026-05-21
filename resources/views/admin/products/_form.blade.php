{{-- resources/views/admin/products/_form.blade.php --}}
<form id="product-form" action="{{ $action }}" method="POST" enctype="multipart/form-data">
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
                    <div class="relative">
                        <textarea name="description"
                                id="description"
                                rows="4"
                                maxlength="1000"
                                placeholder="Masukkan deskripsi detail produk..."
                                class="block w-full px-4 py-2.5 pb-7 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors resize-none">{{ old('description', $product->description ?? '') }}</textarea>
                        {{-- Counter --}}
                        <span id="desc-counter"
                            class="absolute bottom-2 right-3 text-xs text-gray-400 dark:text-zinc-500 pointer-events-none transition-colors">
                            0 / 1000
                        </span>
                    </div>
                </div>

            </div>
        </div>
        {{-- ↑ TUTUP section-info di sini --}}

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
                            id="price_input"
                            value="{{ old('price', $product->price ?? '') }}"
                            required min="0" step="1000" placeholder="0"
                            class="block w-full pl-10 pr-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                    </div>
                    {{-- Preview format rupiah --}}
                    <p id="price-display" class="mt-1.5 text-xs font-semibold text-soft-green flex items-center gap-1 min-h-[1.25rem]"></p>
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
        {{-- ↑ TUTUP section-price di sini --}}

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
        {{-- ↑ TUTUP section-availability di sini --}}

        <!-- Galeri Gambar Section -->
        <div class="space-y-4 pt-6 border-t border-gray-200 dark:border-zinc-800">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-soft-green">photo_library</span>
                Galeri Gambar Produk
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Upload Gambar Utama --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Gambar Utama
                        <span class="text-gray-400 text-xs font-normal">(Tampil di katalog)</span>
                    </label>
                    <label for="main_image"
                        class="group relative flex flex-col items-center justify-center gap-3
                                h-40 border-2 border-dashed border-gray-300 dark:border-zinc-700
                                rounded-xl cursor-pointer
                                hover:border-soft-green hover:bg-soft-green/5
                                dark:hover:border-soft-green dark:hover:bg-soft-green/5
                                transition-all duration-200">
                        <input type="file" name="image" id="main_image"
                            accept="image/jpeg,image/png,image/jpg" class="hidden"
                            onchange="handleMainImage(event)">
                        <div class="w-12 h-12 rounded-full bg-soft-green/10 dark:bg-soft-green/20
                                    flex items-center justify-center
                                    group-hover:bg-soft-green/20 transition-colors">
                            <span class="material-symbols-outlined text-soft-green text-2xl">add_photo_alternate</span>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-700 dark:text-zinc-300
                                    group-hover:text-soft-green transition-colors">
                                Klik untuk upload
                            </p>
                            <p class="text-xs text-gray-400 dark:text-zinc-500 mt-0.5">JPG, PNG — Max 5MB</p>
                        </div>
                        {{-- Badge UTAMA --}}
                        <span class="absolute top-2 left-2 px-2 py-0.5 bg-soft-green text-white text-[10px] font-bold rounded-full">
                            UTAMA
                        </span>
                    </label>
                </div>

                {{-- Upload Galeri --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Gambar Tambahan
                        <span class="text-gray-400 text-xs font-normal">(Bisa pilih banyak)</span>
                    </label>
                    <label for="gallery_images"
                        class="group relative flex flex-col items-center justify-center gap-3
                                h-40 border-2 border-dashed border-gray-300 dark:border-zinc-700
                                rounded-xl cursor-pointer
                                hover:border-blue-500 hover:bg-blue-500/5
                                dark:hover:border-blue-400 dark:hover:bg-blue-500/5
                                transition-all duration-200">
                        <input type="file" name="gallery_images[]" id="gallery_images"
                            accept="image/jpeg,image/png,image/jpg" multiple class="hidden"
                            onchange="handleGalleryImages(event)">
                        <div class="w-12 h-12 rounded-full bg-blue-500/10 dark:bg-blue-500/20
                                    flex items-center justify-center
                                    group-hover:bg-blue-500/20 transition-colors">
                            <span class="material-symbols-outlined text-blue-500 dark:text-blue-400 text-2xl">collections</span>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-700 dark:text-zinc-300
                                    group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors">
                                Klik untuk upload
                            </p>
                            <p class="text-xs text-gray-400 dark:text-zinc-500 mt-0.5">JPG, PNG — Max 5MB per file</p>
                        </div>
                        {{-- Badge GALERI --}}
                        <span class="absolute top-2 left-2 px-2 py-0.5 bg-blue-500 text-white text-[10px] font-bold rounded-full">
                            GALERI
                        </span>
                    </label>
                </div>

            </div>

            @error('image')
                <div class="flex items-center gap-1 mt-1 text-xs text-red-600 dark:text-red-400">
                    <span class="material-symbols-outlined text-sm">error</span>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            <!-- Preview Gallery -->
            <div id="images-preview-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @if(isset($product) && !empty($product->images))
                    @foreach($product->images as $index => $image)
                        <div class="relative group image-item" data-existing="true"
                            onmouseenter="this.querySelector('.remove-btn').style.opacity='1'"
                            onmouseleave="this.querySelector('.remove-btn').style.opacity='0'">
                            <div class="relative aspect-square rounded-xl overflow-hidden shadow-sm"
                                style="border: 2px solid {{ $index === 0 ? '#7BB661' : '#374151' }}">
                                <img src="{{ asset('storage/' . $image) }}"
                                alt="Image {{ $index + 1 }}"
                                class="w-full h-full object-cover"
                                style="cursor:zoom-in; transition: transform 0.3s ease;"
                                onclick="openImageModal('{{ asset('storage/' . $image) }}', 'Gambar {{ $index + 1 }}')"
                                onmouseover="this.style.transform='scale(1.08)'"
                                onmouseout="this.style.transform='scale(1)'">
                                <div class="absolute top-2 left-2 px-2 py-0.5 text-white text-[10px] font-bold rounded-full shadow"
                                    style="background: {{ $index === 0 ? '#7BB661' : 'rgba(0,0,0,0.5)' }}">
                                    {{ $index === 0 ? 'UTAMA' : '#' . ($index + 1) }}
                                </div>
                            </div>
                            {{-- Tombol hapus existing --}}
                            <label class="remove-btn" style="position:absolute; top:8px; right:8px; cursor:pointer; opacity:0; transition:opacity 0.2s;">
                                <input type="checkbox" name="remove_images[]" value="{{ $image }}"
                                    class="peer hidden" onchange="toggleRemoveImage(this)">
                                <div style="width:24px; height:24px; background:#ef4444; border-radius:50%;
                                            color:white; display:flex; align-items:center; justify-content:center;
                                            box-shadow:0 2px 6px rgba(0,0,0,0.3);">
                                    <span class="material-symbols-outlined" style="font-size:14px">close</span>
                                </div>
                            </label>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Info & Tips --}}
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
        {{-- ↑ TUTUP section-gallery di sini --}}

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
        {{-- ↑ TUTUP section-cert di sini --}}

        {{-- Action Buttons --}}
        <div class="pt-6 border-t border-gray-200 dark:border-zinc-800 mt-6">
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="flex items-center gap-2 px-6 py-2.5
                               bg-gradient-to-r from-soft-green to-primary
                               text-white font-medium rounded-lg
                               hover:shadow-lg hover:scale-[1.02] transition-all">
                    <span class="material-symbols-outlined text-lg">save</span>
                    {{ $buttonText ?? 'Simpan Produk' }}
                </button>
                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-2 px-6 py-2.5 border border-gray-300 dark:border-zinc-700
                          rounded-lg text-gray-700 dark:text-zinc-300
                          bg-white dark:bg-zinc-800
                          hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
                    <span class="material-symbols-outlined text-lg">close</span>
                    Batal
                </a>
                <p class="ml-auto text-xs text-gray-500 dark:text-zinc-400 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm text-yellow-500">info</span>
                    Field bertanda <span class="text-red-500 font-bold mx-1">*</span> wajib diisi
                </p>
            </div>
        </div>

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
            subSelect.innerHTML = `<option value="${this.value}" selected>— (Gunakan kategori ini langsung) —</option>`;
        } else {
            populateSub(children);
        }
    });

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
const weightInput       = document.getElementById('weight');
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
    reader.onload = e => addImagePreview(e.target.result, 'UTAMA', true);
    reader.readAsDataURL(file);
}

function handleGalleryImages(event) {
    const files = Array.from(event.target.files);
    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => addImagePreview(e.target.result, `#${previewImageCount + index + 1}`, false);
        reader.readAsDataURL(file);
    });
    previewImageCount += files.length;
}

function addImagePreview(imageSrc, label, isMain = false) {
    const container = document.getElementById('images-preview-container');
    const div = document.createElement('div');
    div.className = 'relative group image-item';

    const borderColor = isMain ? '#7BB661' : '#6b7280';
    const badgeColor  = isMain ? '#7BB661' : 'rgba(0,0,0,0.5)';

    div.innerHTML = `
    <div class="relative aspect-square rounded-xl overflow-hidden shadow-sm"
         style="border: 2px solid ${borderColor}">
            <img src="${imageSrc}" alt="${label}"
                class="w-full h-full object-cover"
                style="cursor:zoom-in; transition: transform 0.3s ease;"
                onclick="openImageModal('${imageSrc}', '${label}')"
                onmouseover="this.style.transform='scale(1.08)'"
                onmouseout="this.style.transform='scale(1)'">
            <div class="absolute top-2 left-2 px-2 py-0.5 text-white text-[10px] font-bold rounded-full shadow"
                 style="background:${badgeColor}">
                ${label}
            </div>
            {{-- Overlay --}}
            <div class="absolute inset-0 pointer-events-none"
                 style="background:rgba(0,0,0,0); transition: background 0.2s"
                 onmouseover="this.style.background='rgba(0,0,0,0.15)'"
                 onmouseout="this.style.background='rgba(0,0,0,0)'">
            </div>
        </div>
        {{-- Tombol hapus pakai inline style --}}
        <button type="button"
                onclick="removeNewPreview(this)"
                style="position:absolute; top:8px; right:8px;
                       width:24px; height:24px;
                       background:#ef4444; border:none; border-radius:50%;
                       color:white; cursor:pointer;
                       display:flex; align-items:center; justify-content:center;
                       opacity:0; transition:opacity 0.2s; box-shadow:0 2px 6px rgba(0,0,0,0.3);"
                onmouseover="this.style.background='#dc2626'"
                onmouseout="this.style.background='#ef4444'">
            <span class="material-symbols-outlined" style="font-size:14px">close</span>
        </button>
    `;

    // Show/hide tombol hapus saat hover
    div.addEventListener('mouseenter', () => {
        div.querySelector('button').style.opacity = '1';
    });
    div.addEventListener('mouseleave', () => {
        div.querySelector('button').style.opacity = '0';
    });

    container.appendChild(div);
}

function removeNewPreview(btn) {
    btn.closest('.image-item').remove();
    previewImageCount = Math.max(0, previewImageCount - 1);
}

function toggleRemoveImage(checkbox) {
    const imageItem = checkbox.closest('.image-item');
    const img       = imageItem.querySelector('img');
    let badge       = imageItem.querySelector('.will-delete-badge');

    if (checkbox.checked) {
        // Efek pudar + grayscale
        imageItem.style.opacity = '0.5';
        img.style.filter        = 'grayscale(100%)';

        // Tambah badge "Akan Dihapus" kalau belum ada
        if (!badge) {
            badge = document.createElement('div');
            badge.className = 'will-delete-badge';
            badge.style.cssText = `
                position: absolute; inset: 0;
                display: flex; flex-direction: column;
                align-items: center; justify-content: center;
                gap: 4px; pointer-events: none;
                background: rgba(239,68,68,0.15);
                border-radius: 10px;
            `;
            badge.innerHTML = `
                <span class="material-symbols-outlined" style="color:#ef4444; font-size:28px;">delete</span>
                <span style="color:#ef4444; font-size:10px; font-weight:700; 
                             background:rgba(239,68,68,0.9); color:white;
                             padding:2px 8px; border-radius:99px;">
                    Akan Dihapus
                </span>
            `;
            imageItem.querySelector('div').appendChild(badge);
        }
        badge.style.display = 'flex';

    } else {
        // Batalkan — kembalikan normal
        imageItem.style.opacity = '1';
        img.style.filter        = '';
        if (badge) badge.style.display = 'none';
    }
}

// ===================== KARAKTER COUNTER DESKRIPSI =====================
const descTextarea = document.getElementById('description');
const descCounter  = document.getElementById('desc-counter');

function updateDescCounter() {
    const len = descTextarea?.value.length ?? 0;
    if (!descCounter) return;
    descCounter.textContent = `${len} / 1000`;
    if (len >= 1000) {
        descCounter.className = 'absolute bottom-2 right-3 text-xs pointer-events-none transition-colors text-red-500 font-semibold';
    } else if (len >= 900) {
        descCounter.className = 'absolute bottom-2 right-3 text-xs pointer-events-none transition-colors text-yellow-500';
    } else {
        descCounter.className = 'absolute bottom-2 right-3 text-xs pointer-events-none transition-colors text-gray-400 dark:text-zinc-500';
    }
}
descTextarea?.addEventListener('input', updateDescCounter);
updateDescCounter();

// ===================== FORMAT RUPIAH =====================
const priceInput   = document.getElementById('price_input');
const priceDisplay = document.getElementById('price-display');

function formatRupiah(angka) {
    if (!angka || isNaN(angka)) return '';
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}

function updatePriceDisplay() {
    if (!priceDisplay) return;
    const val = priceInput?.value;
    if (val && parseInt(val) > 0) {
        priceDisplay.innerHTML = `
            <span class="material-symbols-outlined text-xs">payments</span>
            ${formatRupiah(val)}
        `;
    } else {
        priceDisplay.textContent = '';
    }
}
priceInput?.addEventListener('input', updatePriceDisplay);
updatePriceDisplay();

// ===================== SERTIFIKAT =====================
document.getElementById('health_certificate')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) document.getElementById('cert-label').textContent = `📄 ${file.name}`;
});

// ===================== ZOOM GAMBAR MODAL =====================
function openImageModal(src, name) {
    // Buat modal kalau belum ada
    if (!document.getElementById('img-zoom-modal')) {
        const modal = document.createElement('div');
        modal.id = 'img-zoom-modal';
        modal.style.cssText = `
            position: fixed; inset: 0; z-index: 9999;
            background: rgba(0,0,0,0.8);
            backdrop-filter: blur(4px);
            display: flex; align-items: center; justify-content: center;
            padding: 16px; cursor: zoom-out;
        `;
        modal.innerHTML = `
            <div style="position:relative; max-width:700px; width:100%;" onclick="event.stopPropagation()">
                <div style="background:#18181b; border-radius:16px; overflow:hidden; box-shadow:0 25px 60px rgba(0,0,0,0.5);">
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; border-bottom:1px solid #3f3f46;">
                        <p id="img-zoom-title" style="font-size:14px; font-weight:600; color:white; margin:0;"></p>
                        <button onclick="closeImageModal()"
                                style="background:transparent; border:none; cursor:pointer; color:#a1a1aa; padding:4px;">
                            <span class="material-symbols-outlined" style="font-size:20px">close</span>
                        </button>
                    </div>
                    <div style="background:#09090b; display:flex; align-items:center; justify-content:center; min-height:300px;">
                        <img id="img-zoom-src" src="" alt=""
                             style="max-height:70vh; max-width:100%; object-fit:contain;">
                    </div>
                </div>
            </div>
        `;
        modal.addEventListener('click', closeImageModal);
        document.body.appendChild(modal);
    }

    document.getElementById('img-zoom-title').textContent = name;
    document.getElementById('img-zoom-src').src = src;
    document.getElementById('img-zoom-modal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('img-zoom-modal');
    if (modal) modal.style.display = 'none';
    document.body.style.overflow = '';
}

// Tutup dengan ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeImageModal();
});

// ===================== KONFIRMASI LEAVE PAGE =====================
let formChanged = false;

document.getElementById('product-form')?.addEventListener('input', () => {
    formChanged = true;
});
document.getElementById('product-form')?.addEventListener('change', () => {
    formChanged = true;
});

window.addEventListener('beforeunload', function(e) {
    if (formChanged) {
        e.preventDefault();
        e.returnValue = '';
    }
});

// Reset flag saat form di-submit supaya tidak muncul konfirmasi
document.getElementById('product-form')?.addEventListener('submit', () => {
    formChanged = false;
});

// ===================== DRAFT AUTO-SAVE =====================
// Hanya aktif di halaman CREATE (bukan edit)
@if(!isset($product))
(function() {
    const DRAFT_KEY = 'product_draft';
    let draftTimer  = null;
    let isSubmitting = false;

    // Kumpulkan semua nilai form saat ini
    function collectFormData() {
        const form = document.getElementById('product-form');
        if (!form) return null;
 
        return {
            parent_category_id : document.getElementById('parent_category_id')?.value ?? '',
            sub_category_id    : document.getElementById('sub_category_id')?.value ?? '',
            name               : form.querySelector('[name="name"]')?.value ?? '',
            unit               : form.querySelector('[name="unit"]')?.value ?? '',
            description        : form.querySelector('[name="description"]')?.value ?? '',
            price              : form.querySelector('[name="price"]')?.value ?? '',
            stock              : form.querySelector('[name="stock"]')?.value ?? '',
            weight             : form.querySelector('[name="weight"]')?.value ?? '',
            available_from     : form.querySelector('[name="available_from"]')?.value ?? '',
            is_active          : form.querySelector('[name="is_active"]')?.checked ?? true,
            is_featured        : form.querySelector('[name="is_featured"]')?.checked ?? false,
            savedAt            : new Date().toISOString(),
        };
    }
 
    // Cek apakah ada data yang sudah diisi (minimal nama atau harga)
    function hasData(data) {
        return data && (data.name.trim() !== '' || data.price !== '' || data.description.trim() !== '');
    }
 
    // Simpan draft ke localStorage (debounce 1.5 detik)
    function saveDraft() {
        if (isSubmitting) return;
        clearTimeout(draftTimer);
        draftTimer = setTimeout(() => {
            const data = collectFormData();
            if (!hasData(data)) return;
            try {
                localStorage.setItem(DRAFT_KEY, JSON.stringify(data));
            } catch(e) {}
        }, 1500);
    }
 
    // Pasang listener ke semua input di form
    const form = document.getElementById('product-form');
    if (form) {
        form.addEventListener('input',  saveDraft);
        form.addEventListener('change', saveDraft);
 
        // Hapus draft saat form berhasil disubmit
        form.addEventListener('submit', () => {
            isSubmitting = true;          // ← set flag dulu
            clearTimeout(draftTimer)
            try { localStorage.removeItem(DRAFT_KEY); } catch(e) {}
        });
    }
 
    // Expose fungsi restore untuk dipakai di create.blade.php
    window.productDraft = {
        DRAFT_KEY,
        collectFormData,
        hasData,
 
        // Restore data ke form
        restore(data) {
            if (!data) return;
            const form = document.getElementById('product-form');
            if (!form) return;
 
            // Nama, unit, deskripsi, harga, stok, berat, tanggal
            const set = (name, val) => {
                const el = form.querySelector(`[name="${name}"]`);
                if (el) el.value = val;
            };
 
            set('name',           data.name);
            set('unit',           data.unit);
            set('description',    data.description);
            set('price',          data.price);
            set('stock',          data.stock);
            set('weight',         data.weight);
            set('available_from', data.available_from);
 
            // Checkbox
            const isActive   = form.querySelector('[name="is_active"]');
            const isFeatured = form.querySelector('[name="is_featured"]');
            if (isActive)   isActive.checked   = data.is_active;
            if (isFeatured) isFeatured.checked  = data.is_featured;
 
            // Kategori utama — trigger change supaya sub kategori ikut muncul
            const parentSelect = document.getElementById('parent_category_id');
            if (parentSelect && data.parent_category_id) {
                parentSelect.value = data.parent_category_id;
                parentSelect.dispatchEvent(new Event('change'));
 
                // Sub kategori butuh sedikit delay karena harus nunggu populate
                setTimeout(() => {
                    const subSelect = document.getElementById('sub_category_id');
                    if (subSelect && data.sub_category_id) {
                        subSelect.value = data.sub_category_id;
                    }
                }, 100);
            }
 
            // Update tampilan turunan (counter, format rupiah, berat)
            updateDescCounter?.();
            updatePriceDisplay?.();
            updateWeightDisplay?.();
        },
 
        // Buang draft
        discard() {
            try { localStorage.removeItem(DRAFT_KEY); } catch(e) {}
        }
    };
})();
@endif
</script>