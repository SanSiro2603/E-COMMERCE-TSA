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

    {{-- Alert session ditangani layout global via SweetAlert --}}

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- ===== FORM KATEGORI UTAMA ===== -->
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
                <form id="form-utama" action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="is_active" value="1">
                    <input type="hidden" name="parent_id" value="">
                    <div class="space-y-4">

                        <!-- Nama Kategori -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Nama Kategori <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name-utama" value="{{ old('name') }}" required
                                    placeholder="Masukkan nama kategori utama"
                                    class="block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                            <div id="check-utama" class="hidden mt-1.5 flex items-center gap-1 text-xs"></div>
                            @error('name')
                                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug Auto-generate -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Slug <span class="text-red-500">*</span>
                                <span class="text-gray-400 text-xs font-normal">(otomatis dari nama)</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 dark:text-zinc-500 select-none">/</span>
                                <input type="text" name="slug" id="slug-utama" value="{{ old('slug') }}" required
                                       placeholder="nama-kategori"
                                       class="block w-full pl-6 pr-10 py-2.5 bg-gray-50 dark:bg-zinc-800/60 border border-gray-300 dark:border-zinc-700 rounded-lg text-sm text-gray-700 dark:text-zinc-300 placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors font-mono">
                                <button type="button" onclick="refreshSlugUtama()"
                                        title="Reset slug dari nama"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-soft-green transition-colors">
                                    <span class="material-symbols-outlined text-lg">refresh</span>
                                </button>
                            </div>
                            @error('slug')
                                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gambar -->
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

        <!-- ===== FORM SUB KATEGORI ===== -->
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
                    <form action="{{ route('admin.categories.store') }}" method="POST" id="form-sub">
                        @csrf
                        <input type="hidden" name="single_sub" value="0">

                        <div class="space-y-4">

                            {{-- Pilih Kategori Utama --}}
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

                            {{-- Dynamic Rows --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                    Sub Kategori <span class="text-red-500">*</span>
                                    <span class="text-gray-400 text-xs font-normal">(bisa tambah banyak sekaligus)</span>
                                </label>

                                <div id="sub-rows" class="space-y-2">
                                    <div class="sub-row flex items-start gap-2" data-index="0">
                                        <div class="flex-1 space-y-1.5">
                                            <input type="text" name="sub_names[]"
                                                placeholder="Nama sub kategori"
                                                required
                                                class="sub-name-input block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 dark:text-zinc-500">/</span>
                                                <input type="text" name="sub_slugs[]"
                                                    placeholder="slug-otomatis"
                                                    required
                                                    class="sub-slug-input block w-full pl-6 pr-4 py-2 bg-gray-50 dark:bg-zinc-800/60 border border-gray-300 dark:border-zinc-700 rounded-lg text-xs text-gray-600 dark:text-zinc-400 placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors font-mono">
                                            </div>
                                            <div class="check-result hidden flex items-center gap-1 text-xs"></div>
                                        </div>
                                        <button type="button" disabled
                                                class="mt-2 w-9 h-9 flex items-center justify-center rounded-lg text-gray-300 dark:text-zinc-600 cursor-not-allowed flex-shrink-0">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </div>
                                </div>

                                <button type="button" onclick="addSubRow()"
                                        class="mt-3 w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-purple-600 dark:text-purple-400 border border-dashed border-purple-300 dark:border-purple-700 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-500/10 transition-colors">
                                    <span class="material-symbols-outlined text-lg">add</span>
                                    Tambah Sub Kategori Lagi
                                </button>
                            </div>

                            <p id="sub-counter" class="text-xs text-gray-400 dark:text-zinc-500 text-right">
                                1 sub kategori akan ditambahkan
                            </p>

                            <div class="pt-2">
                                <button type="submit"
                                        class="w-full flex items-center justify-center gap-2 px-6 py-2.5 bg-gradient-to-r from-purple-500 to-purple-700 text-white font-medium rounded-lg hover:shadow-lg hover:scale-[1.01] transition-all">
                                    <span class="material-symbols-outlined text-lg">save</span>
                                    Simpan Semua Sub Kategori
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
// ══════════════════════════════════════════
// SLUG GENERATOR
// ══════════════════════════════════════════
function toSlug(str) {
    return str.toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
}

// ══════════════════════════════════════════
// DUPLICATE CHECK
// ══════════════════════════════════════════
const CHECK_URL = "{{ route('admin.categories.check-name') }}";
let timerUtama  = null;
let timerSub    = null;

function showCheckResult(elId, inputEl, available, message) {
    const el = document.getElementById(elId);
    el.classList.remove('hidden');
    if (available) {
        el.innerHTML = `<span class="material-symbols-outlined text-sm text-green-500">check_circle</span>
                        <span class="text-green-600 dark:text-green-400">${message}</span>`;
        inputEl.classList.remove('border-red-500');
        inputEl.classList.add('border-green-500');
    } else {
        el.innerHTML = `<span class="material-symbols-outlined text-sm text-red-500">cancel</span>
                        <span class="text-red-500">${message}</span>`;
        inputEl.classList.remove('border-green-500');
        inputEl.classList.add('border-red-500');
    }
}

function hideCheck(elId, inputEl) {
    const el = document.getElementById(elId);
    el.classList.add('hidden');
    el.innerHTML = '';
    inputEl.classList.remove('border-green-500', 'border-red-500');
}

function doCheckName(value, elId, inputEl) {
    if (!value.trim()) { hideCheck(elId, inputEl); return; }
    const el = document.getElementById(elId);
    el.classList.remove('hidden');
    el.innerHTML = `<span class="material-symbols-outlined text-sm text-gray-400 animate-spin">progress_activity</span>
                    <span class="text-gray-400">Mengecek...</span>`;
    fetch(`${CHECK_URL}?name=${encodeURIComponent(value)}`)
        .then(r => r.json())
        .then(data => showCheckResult(elId, inputEl, data.available, data.message))
        .catch(() => hideCheck(elId, inputEl));
}

// ══════════════════════════════════════════
// KATEGORI UTAMA
// ══════════════════════════════════════════
const nameUtama = document.getElementById('name-utama');
const slugUtama = document.getElementById('slug-utama');
let slugUtamaEdited = false;

nameUtama.addEventListener('input', function () {
    if (!slugUtamaEdited) slugUtama.value = toSlug(this.value);
    clearTimeout(timerUtama);
    timerUtama = setTimeout(() => doCheckName(this.value, 'check-utama', this), 500);
});

slugUtama.addEventListener('input', function () {
    slugUtamaEdited = this.value !== toSlug(nameUtama.value);
});

function refreshSlugUtama() {
    slugUtama.value = toSlug(nameUtama.value);
    slugUtamaEdited = false;
}

// ══════════════════════════════════════════
// MULTIPLE SUB KATEGORI
// ══════════════════════════════════════════
let subRowCount = 1;
let subTimers   = {};

function updateCounter() {
    const counter = document.getElementById('sub-counter');
    if (!counter) return;
    counter.textContent = subRowCount + ' sub kategori akan ditambahkan';
}

function bindSubRow(row, index) {
    const nameInput = row.querySelector('.sub-name-input');
    const slugInput = row.querySelector('.sub-slug-input');
    const checkEl   = row.querySelector('.check-result');

    nameInput.addEventListener('input', function () {
        slugInput.value = toSlug(this.value);
        clearTimeout(subTimers[index]);
        const val = this.value.trim();
        if (!val) {
            checkEl.classList.add('hidden');
            checkEl.innerHTML = '';
            nameInput.classList.remove('border-green-500', 'border-red-500');
            return;
        }
        checkEl.classList.remove('hidden');
        checkEl.innerHTML = `<span class="material-symbols-outlined text-sm text-gray-400 animate-spin">progress_activity</span>
                             <span class="text-gray-400">Mengecek...</span>`;
        subTimers[index] = setTimeout(() => {
            fetch(`${CHECK_URL}?name=${encodeURIComponent(val)}`)
                .then(r => r.json())
                .then(data => {
                    checkEl.classList.remove('hidden');
                    if (data.available) {
                        checkEl.innerHTML = `<span class="material-symbols-outlined text-sm text-green-500">check_circle</span>
                                             <span class="text-green-600 dark:text-green-400">${data.message}</span>`;
                        nameInput.classList.remove('border-red-500');
                        nameInput.classList.add('border-green-500');
                    } else {
                        checkEl.innerHTML = `<span class="material-symbols-outlined text-sm text-red-500">cancel</span>
                                             <span class="text-red-500">${data.message}</span>`;
                        nameInput.classList.remove('border-green-500');
                        nameInput.classList.add('border-red-500');
                    }
                })
                .catch(() => checkEl.classList.add('hidden'));
        }, 500);
    });
}

function addSubRow() {
    const container = document.getElementById('sub-rows');
    const index     = subRowCount;
    const row = document.createElement('div');
    row.className     = 'sub-row flex items-start gap-2';
    row.dataset.index = index;
    row.innerHTML = `
        <div class="flex-1 space-y-1.5">
            <input type="text" name="sub_names[]" placeholder="Nama sub kategori" required
                   class="sub-name-input block w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 dark:text-zinc-500">/</span>
                <input type="text" name="sub_slugs[]" placeholder="slug-otomatis" required
                       class="sub-slug-input block w-full pl-6 pr-4 py-2 bg-gray-50 dark:bg-zinc-800/60 border border-gray-300 dark:border-zinc-700 rounded-lg text-xs text-gray-600 dark:text-zinc-400 placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors font-mono">
            </div>
            <div class="check-result hidden flex items-center gap-1 text-xs"></div>
        </div>
        <button type="button" onclick="removeSubRow(this)"
                class="mt-2 w-9 h-9 flex items-center justify-center rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors flex-shrink-0">
            <span class="material-symbols-outlined text-lg">delete</span>
        </button>
    `;
    container.appendChild(row);
    bindSubRow(row, index);
    subRowCount++;
    updateCounter();
    row.querySelector('.sub-name-input').focus();
}

function removeSubRow(btn) {
    btn.closest('.sub-row').remove();
    subRowCount--;
    updateCounter();
}

const firstRow = document.querySelector('.sub-row[data-index="0"]');
if (firstRow) bindSubRow(firstRow, 0);

// ══════════════════════════════════════════
// IMAGE PREVIEW
// ══════════════════════════════════════════
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

// ══════════════════════════════════════════
// DRAFT AUTO-SAVE — Kategori Utama
// ══════════════════════════════════════════
(function() {
    const DRAFT_KEY = 'category_draft';
    let draftTimer  = null;

    function collectData() {
        return {
            name    : document.getElementById('name-utama')?.value ?? '',
            slug    : document.getElementById('slug-utama')?.value ?? '',
            savedAt : new Date().toISOString(),
        };
    }

    function hasData(data) {
        return data && data.name.trim() !== '';
    }

    function saveDraft() {
        clearTimeout(draftTimer);
        draftTimer = setTimeout(() => {
            const data = collectData();
            if (!hasData(data)) return;
            try { localStorage.setItem(DRAFT_KEY, JSON.stringify(data)); } catch(e) {}
        }, 1500);
    }

    // Pasang listener
    document.getElementById('name-utama')?.addEventListener('input', saveDraft);
    document.getElementById('slug-utama')?.addEventListener('input', saveDraft);

    // Hapus draft saat submit berhasil
    document.getElementById('form-utama')?.addEventListener('submit', () => {
        try { localStorage.removeItem(DRAFT_KEY); } catch(e) {}
    });

    // Expose untuk restore
    window.categoryDraft = {
        DRAFT_KEY,
        hasData,
        restore(data) {
            if (!data) return;
            const nameEl = document.getElementById('name-utama');
            const slugEl = document.getElementById('slug-utama');
            if (nameEl) nameEl.value = data.name;
            if (slugEl) slugEl.value = data.slug;
        },
        discard() {
            try { localStorage.removeItem(DRAFT_KEY); } catch(e) {}
        }
    };

    // ── Cek draft saat halaman dimuat ──
    document.addEventListener('DOMContentLoaded', () => {
        try {
            const raw = localStorage.getItem(DRAFT_KEY);
            if (!raw) return;
            const data = JSON.parse(raw);
            if (!hasData(data)) return;

            const savedAt = new Date(data.savedAt);
            const timeStr = savedAt.toLocaleDateString('id-ID', {
                day: '2-digit', month: 'short', year: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });

            Swal.fire({
            title: 'Ada draft yang belum selesai',
            html: `
                <div class="text-sm text-gray-600 space-y-2">
                    <p>Ditemukan draft kategori yang tersimpan pada:</p>
                    <p class="font-semibold">${timeStr}</p>
                    ${data.name ? `<p class="text-xs text-gray-500">Nama: <strong>${data.name}</strong></p>` : ''}
                </div>
            `,
            icon: 'info',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonColor: '#7BB661',
            denyButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '✏️ Lanjutkan Draft',
            denyButtonText: '🗑️ Buang Draft',
            cancelButtonText: 'Nanti saja',
        }).then(result => {
            if (result.isConfirmed) {
                window.categoryDraft.restore(data);
                Swal.fire({
                    icon: 'success', title: 'Draft dipulihkan!',
                    toast: true, position: 'top-end',
                    timer: 2000, showConfirmButton: false, timerProgressBar: true,
                });
            } else if (result.isDenied) {
                window.categoryDraft.discard();
                Swal.fire({
                    icon: 'info', title: 'Draft dibuang',
                    toast: true, position: 'top-end',
                    timer: 2000, showConfirmButton: false, timerProgressBar: true,
                });
            }
        });
        } catch(e) {}
    });
})();

// ── Konfirmasi Leave Page untuk form kategori utama ──
let categoryFormChanged = false;

document.getElementById('form-utama')?.addEventListener('input', () => {
    categoryFormChanged = true;
});
document.getElementById('form-utama')?.addEventListener('change', () => {
    categoryFormChanged = true;
});

// Form sub kategori
document.getElementById('form-sub')?.addEventListener('input', () => {
    categoryFormChanged = true;
});
document.getElementById('form-sub')?.addEventListener('change', () => {
    categoryFormChanged = true;
});

window.addEventListener('beforeunload', function(e) {
    if (categoryFormChanged) {
        e.preventDefault();
        e.returnValue = '';
    }
});

// Reset saat submit
document.getElementById('form-utama')?.addEventListener('submit', () => {
    categoryFormChanged = false;
});
document.getElementById('form-sub')?.addEventListener('submit', () => {
    categoryFormChanged = false;
});

</script>

@endsection