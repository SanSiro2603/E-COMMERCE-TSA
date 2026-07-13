@extends('layouts.admin')

@section('title', 'CMS — Halaman Home')

@section('content')
<div class="space-y-8">

    {{-- ── Page Header ── --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-400 dark:text-zinc-500 mb-1">
                <span>CMS</span>
                <span class="material-symbols-outlined text-[12px]">chevron_right</span>
                <span class="text-soft-green font-semibold">Halaman Home</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">CMS — Halaman Home</h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Kelola konten yang tampil di halaman utama landing page.</p>
        </div>
        <a href="{{ route('landing') }}" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl border border-gray-200 dark:border-zinc-700 text-gray-600 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
            <span class="material-symbols-outlined text-[18px]">open_in_new</span>
            Lihat Landing Page
        </a>
    </div>

    {{-- ══════════════════════════════════════════════════════
         SECTION 1 — HERO SLIDES
    ══════════════════════════════════════════════════════ --}}
    <div x-data="{
            open: false,
            editing: false,
            deleteOpen: false,
            deleteId: null,
            slideId: null,
            imagePreview: null,
            form: { title_top: '', title_bottom: '', copy: '', bg_position: 'center center', sort_order: 0 },
            openAdd() {
                this.editing = false; this.slideId = null; this.imagePreview = null;
                this.form = { title_top: '', title_bottom: '', copy: '', bg_position: 'center center', sort_order: 0 };
                this.open = true;
            },
            openEdit(s) {
                this.editing = true; this.slideId = s.id; this.imagePreview = s.image_url;
                this.form = { title_top: s.title_top, title_bottom: s.title_bottom, copy: s.copy, bg_position: s.bg_position, sort_order: s.sort_order };
                this.open = true;
            },
            previewImage(e) {
                const f = e.target.files[0];
                if (!f) return;
                const r = new FileReader(); r.onload = ev => { this.imagePreview = ev.target.result; }; r.readAsDataURL(f);
            },
            get formAction() {
                return this.editing ? '/admin/landing/home/slides/' + this.slideId : '/admin/landing/home/slides';
            }
        }" class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">

        {{-- Header section --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-500 text-[20px]">slideshow</span>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Hero Slider</h2>
                    <p class="text-xs text-gray-400 dark:text-zinc-500">Slide gambar di bagian paling atas halaman home</p>
                </div>
            </div>
            <button @click="openAdd()"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-soft-green hover:bg-green-600 text-white text-sm font-semibold rounded-xl transition-colors">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Slide
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider w-12">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Preview</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Judul</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider hidden md:table-cell">Deskripsi</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider w-20">Urutan</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider w-24">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                    @forelse($slides as $slide)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/40 transition-colors">
                        <td class="px-4 py-3 text-gray-500 dark:text-zinc-400 font-mono text-xs">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            <img src="{{ $slide->image_url }}" alt="slide"
                                 class="h-14 w-24 object-cover rounded-lg border border-gray-200 dark:border-zinc-700">
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $slide->title_top }}</p>
                            <p class="text-soft-green font-bold text-sm">{{ $slide->title_bottom }}</p>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            <p class="text-gray-500 dark:text-zinc-400 text-xs line-clamp-2 max-w-xs">{{ $slide->copy }}</p>
                        </td>
                        <td class="px-4 py-3 text-center text-gray-600 dark:text-zinc-300 font-mono text-sm">{{ $slide->sort_order }}</td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('admin.landing.home.slides.toggle', $slide) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold transition-colors
                                               {{ $slide->is_active ? 'bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 hover:bg-green-100' : 'bg-gray-100 dark:bg-zinc-700 text-gray-500 dark:text-zinc-400 hover:bg-gray-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $slide->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                    {{ $slide->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1">
                                <button @click="openEdit({
                                            id: {{ $slide->id }},
                                            title_top: @js($slide->title_top),
                                            title_bottom: @js($slide->title_bottom),
                                            copy: @js($slide->copy),
                                            bg_position: @js($slide->bg_position),
                                            sort_order: {{ $slide->sort_order }},
                                            image_url: @js($slide->image_url)
                                        })"
                                        class="p-2 rounded-lg text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                <button @click="deleteOpen = true; deleteId = {{ $slide->id }}"
                                        class="p-2 rounded-lg text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-400 dark:text-zinc-500">
                            <span class="material-symbols-outlined text-4xl block mb-2">slideshow</span>
                            Belum ada slide. Klik "Tambah Slide" untuk mulai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Modal Add/Edit Slide ── --}}
        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" style="display:none">
            <div @click.outside="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-zinc-800">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white" x-text="editing ? 'Edit Slide' : 'Tambah Slide'"></h3>
                    <button @click="open = false" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors">
                        <span class="material-symbols-outlined text-gray-400 text-[20px]">close</span>
                    </button>
                </div>

                <form :action="formAction" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    <template x-if="editing"><input type="hidden" name="_method" value="PUT"></template>

                    {{-- Image Upload --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">
                            Gambar Background <span x-show="!editing" class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <template x-if="imagePreview">
                                <img :src="imagePreview" class="w-full h-40 object-cover rounded-xl mb-2 border border-gray-200 dark:border-zinc-700">
                            </template>
                            <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-200 dark:border-zinc-700 rounded-xl cursor-pointer hover:border-soft-green hover:bg-green-50/30 dark:hover:bg-green-500/5 transition-colors">
                                <span class="material-symbols-outlined text-gray-400 text-2xl">cloud_upload</span>
                                <span class="text-xs text-gray-400 mt-1" x-text="imagePreview ? 'Ganti gambar' : 'Pilih gambar (max 3MB)'"></span>
                                <input type="file" name="image" accept="image/*" class="hidden" @change="previewImage($event)">
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">Judul Baris 1 <span class="text-red-400">*</span></label>
                            <input type="text" name="title_top" x-model="form.title_top" required maxlength="100"
                                   class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-soft-green/50 focus:border-soft-green"
                                   placeholder="PT. Tunas Sejahtera">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">Judul Baris 2 (Hijau) <span class="text-red-400">*</span></label>
                            <input type="text" name="title_bottom" x-model="form.title_bottom" required maxlength="100"
                                   class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-soft-green/50 focus:border-soft-green"
                                   placeholder="Adhiperkasa">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">Teks Deskripsi <span class="text-red-400">*</span></label>
                        <textarea name="copy" x-model="form.copy" required maxlength="500" rows="3"
                                  class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-soft-green/50 focus:border-soft-green resize-none"
                                  placeholder="Deskripsi singkat..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">Posisi Gambar</label>
                            <input type="text" name="bg_position" x-model="form.bg_position" maxlength="50"
                                   class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-soft-green/50 focus:border-soft-green"
                                   placeholder="center center">
                            <p class="text-[10px] text-gray-400 mt-1">Contoh: 62% center, top center</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">Urutan</label>
                            <input type="number" name="sort_order" x-model="form.sort_order" min="0" max="255"
                                   class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-soft-green/50 focus:border-soft-green">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="open = false"
                                class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl border border-gray-200 dark:border-zinc-700 text-gray-600 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl bg-soft-green hover:bg-green-600 text-white transition-colors">
                            <span x-text="editing ? 'Simpan Perubahan' : 'Tambah Slide'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Confirm Delete Slide ── --}}
        <div x-show="deleteOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" style="display:none">
            <div @click.outside="deleteOpen = false" class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-red-50 dark:bg-red-500/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-red-500 text-[20px]">delete</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Hapus Slide?</h3>
                        <p class="text-sm text-gray-500 dark:text-zinc-400">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>
                <form :action="'/admin/landing/home/slides/' + deleteId" method="POST" class="flex gap-3">
                    @csrf @method('DELETE')
                    <button type="button" @click="deleteOpen = false"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl border border-gray-200 dark:border-zinc-700 text-gray-600 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl bg-red-500 hover:bg-red-600 text-white transition-colors">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════
         SECTION 2 — CATALOG CARDS
    ══════════════════════════════════════════════════════ --}}
    <div x-data="{
            open: false,
            editing: false,
            deleteOpen: false,
            deleteId: null,
            cardId: null,
            imagePreview: null,
            form: { title: '', description: '', catalog_key: '', sort_order: 0 },
            openAdd() {
                this.editing = false; this.cardId = null; this.imagePreview = null;
                this.form = { title: '', description: '', catalog_key: '', sort_order: 0 };
                this.open = true;
            },
            openEdit(c) {
                this.editing = true; this.cardId = c.id; this.imagePreview = c.image_url;
                this.form = { title: c.title, description: c.description, catalog_key: c.catalog_key, sort_order: c.sort_order };
                this.open = true;
            },
            previewImage(e) {
                const f = e.target.files[0];
                if (!f) return;
                const r = new FileReader(); r.onload = ev => { this.imagePreview = ev.target.result; }; r.readAsDataURL(f);
            },
            get formAction() {
                return this.editing ? '/admin/landing/home/catalog-cards/' + this.cardId : '/admin/landing/home/catalog-cards';
            }
        }" class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-amber-500 text-[20px]">grid_view</span>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Catalog Cards</h2>
                    <p class="text-xs text-gray-400 dark:text-zinc-500">Kartu kategori di bawah hero slider</p>
                </div>
            </div>
            <button @click="openAdd()"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-soft-green hover:bg-green-600 text-white text-sm font-semibold rounded-xl transition-colors">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Card
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider w-12">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Preview</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Judul</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider hidden md:table-cell">Deskripsi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider hidden lg:table-cell">Catalog Key</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider w-20">Urutan</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider w-24">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                    @forelse($catalogCards as $card)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/40 transition-colors">
                        <td class="px-4 py-3 text-gray-500 dark:text-zinc-400 font-mono text-xs">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            <img src="{{ $card->image_url }}" alt="card"
                                 class="h-14 w-14 object-cover rounded-xl border border-gray-200 dark:border-zinc-700">
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-bold text-gray-900 dark:text-white uppercase text-sm">{{ $card->title }}</p>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            <p class="text-gray-500 dark:text-zinc-400 text-xs line-clamp-2 max-w-xs">{{ $card->description }}</p>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <span class="px-2 py-1 bg-gray-100 dark:bg-zinc-700 text-gray-600 dark:text-zinc-300 text-xs font-mono rounded-lg">{{ $card->catalog_key }}</span>
                        </td>
                        <td class="px-4 py-3 text-center text-gray-600 dark:text-zinc-300 font-mono text-sm">{{ $card->sort_order }}</td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('admin.landing.home.catalog-cards.toggle', $card) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold transition-colors
                                               {{ $card->is_active ? 'bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 hover:bg-green-100' : 'bg-gray-100 dark:bg-zinc-700 text-gray-500 dark:text-zinc-400 hover:bg-gray-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $card->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                    {{ $card->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1">
                                <button @click="openEdit({
                                            id: {{ $card->id }},
                                            title: @js($card->title),
                                            description: @js($card->description),
                                            catalog_key: @js($card->catalog_key),
                                            sort_order: {{ $card->sort_order }},
                                            image_url: @js($card->image_url)
                                        })"
                                        class="p-2 rounded-lg text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                <button @click="deleteOpen = true; deleteId = {{ $card->id }}"
                                        class="p-2 rounded-lg text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-gray-400 dark:text-zinc-500">
                            <span class="material-symbols-outlined text-4xl block mb-2">grid_view</span>
                            Belum ada card. Klik "Tambah Card" untuk mulai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Modal Add/Edit Card ── --}}
        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" style="display:none">
            <div @click.outside="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-zinc-800">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white" x-text="editing ? 'Edit Catalog Card' : 'Tambah Catalog Card'"></h3>
                    <button @click="open = false" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors">
                        <span class="material-symbols-outlined text-gray-400 text-[20px]">close</span>
                    </button>
                </div>

                <form :action="formAction" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    <template x-if="editing"><input type="hidden" name="_method" value="PUT"></template>

                    {{-- Image --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">
                            Gambar Card <span x-show="!editing" class="text-red-400">*</span>
                        </label>
                        <template x-if="imagePreview">
                            <img :src="imagePreview" class="w-full h-40 object-cover rounded-xl mb-2 border border-gray-200 dark:border-zinc-700">
                        </template>
                        <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-200 dark:border-zinc-700 rounded-xl cursor-pointer hover:border-soft-green hover:bg-green-50/30 transition-colors">
                            <span class="material-symbols-outlined text-gray-400 text-2xl">cloud_upload</span>
                            <span class="text-xs text-gray-400 mt-1" x-text="imagePreview ? 'Ganti gambar' : 'Pilih gambar (max 3MB)'"></span>
                            <input type="file" name="image" accept="image/*" class="hidden" @change="previewImage($event)">
                        </label>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">Judul <span class="text-red-400">*</span></label>
                        <input type="text" name="title" x-model="form.title" required maxlength="100"
                               class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-soft-green/50 focus:border-soft-green"
                               placeholder="Contoh: Aves">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">Deskripsi <span class="text-red-400">*</span></label>
                        <textarea name="description" x-model="form.description" required maxlength="500" rows="3"
                                  class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-soft-green/50 focus:border-soft-green resize-none"
                                  placeholder="Deskripsi singkat kategori..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">
                                Catalog Key <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="catalog_key" x-model="form.catalog_key" required maxlength="50"
                                   class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-soft-green/50 focus:border-soft-green font-mono"
                                   placeholder="aves">
                            <p class="text-[10px] text-gray-400 mt-1">Slug untuk link ke catalog (huruf kecil)</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">Urutan</label>
                            <input type="number" name="sort_order" x-model="form.sort_order" min="0" max="255"
                                   class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-soft-green/50 focus:border-soft-green">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="open = false"
                                class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl border border-gray-200 dark:border-zinc-700 text-gray-600 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl bg-soft-green hover:bg-green-600 text-white transition-colors">
                            <span x-text="editing ? 'Simpan Perubahan' : 'Tambah Card'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Confirm Delete Card ── --}}
        <div x-show="deleteOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" style="display:none">
            <div @click.outside="deleteOpen = false" class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-red-50 dark:bg-red-500/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-red-500 text-[20px]">delete</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Hapus Card?</h3>
                        <p class="text-sm text-gray-500 dark:text-zinc-400">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>
                <form :action="'/admin/landing/home/catalog-cards/' + deleteId" method="POST" class="flex gap-3">
                    @csrf @method('DELETE')
                    <button type="button" @click="deleteOpen = false"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl border border-gray-200 dark:border-zinc-700 text-gray-600 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl bg-red-500 hover:bg-red-600 text-white transition-colors">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════
         SECTION 3 — INFORMASI & KONTAK
    ══════════════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-zinc-800">
            <div class="w-9 h-9 rounded-xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-purple-500 text-[20px]">settings</span>
            </div>
            <div>
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Informasi & Kontak</h2>
                <p class="text-xs text-gray-400 dark:text-zinc-500">Teks section, kontak, dan link media sosial yang tampil di footer</p>
            </div>
        </div>

        <form action="{{ route('admin.landing.home.settings.update') }}" method="POST" class="p-6 space-y-6">
            @csrf

            {{-- Section Catalog Heading --}}
            <div>
                <h3 class="text-sm font-bold text-gray-700 dark:text-zinc-300 mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px] text-amber-500">title</span>
                    Teks Section Catalog
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">Label Kecil</label>
                        <input type="text" name="home_catalog_label"
                               value="{{ $settings['home_catalog_label'] ?? 'Our Catalog' }}"
                               maxlength="100"
                               class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-soft-green/50 focus:border-soft-green"
                               placeholder="Our Catalog">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">Heading Utama</label>
                        <input type="text" name="home_catalog_heading"
                               value="{{ $settings['home_catalog_heading'] ?? 'Explore Our Main Categories' }}"
                               maxlength="200"
                               class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-soft-green/50 focus:border-soft-green"
                               placeholder="Explore Our Main Categories">
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 dark:border-zinc-800"></div>

            {{-- Kontak --}}
            <div>
                <h3 class="text-sm font-bold text-gray-700 dark:text-zinc-300 mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px] text-blue-500">contact_phone</span>
                    Kontak
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">Telepon 1</label>
                        <input type="text" name="site_phone_1"
                               value="{{ $settings['site_phone_1'] ?? '' }}"
                               class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-soft-green/50 focus:border-soft-green"
                               placeholder="+62721 8050354">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">Telepon 2</label>
                        <input type="text" name="site_phone_2"
                               value="{{ $settings['site_phone_2'] ?? '' }}"
                               class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-soft-green/50 focus:border-soft-green"
                               placeholder="+6282183948148">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">Email</label>
                        <input type="email" name="site_email"
                               value="{{ $settings['site_email'] ?? '' }}"
                               class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-soft-green/50 focus:border-soft-green"
                               placeholder="pt.tsalampung@gmail.com">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">WhatsApp (nomor saja)</label>
                        <input type="text" name="social_whatsapp"
                               value="{{ $settings['social_whatsapp'] ?? '' }}"
                               class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-soft-green/50 focus:border-soft-green"
                               placeholder="6282183948148">
                        <p class="text-[10px] text-gray-400 mt-1">Dipakai di tombol floating WA & footer</p>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">Alamat</label>
                        <textarea name="site_address" rows="2" maxlength="500"
                                  class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-soft-green/50 focus:border-soft-green resize-none"
                                  placeholder="Jl. ...">{{ $settings['site_address'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 dark:border-zinc-800"></div>

            {{-- Social Media --}}
            <div>
                <h3 class="text-sm font-bold text-gray-700 dark:text-zinc-300 mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px] text-pink-500">share</span>
                    Media Sosial
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">Facebook URL</label>
                        <input type="text" name="social_facebook"
                               value="{{ $settings['social_facebook'] ?? '' }}"
                               class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-soft-green/50 focus:border-soft-green"
                               placeholder="https://facebook.com/...">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">Instagram URL</label>
                        <input type="text" name="social_instagram"
                               value="{{ $settings['social_instagram'] ?? '' }}"
                               class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-soft-green/50 focus:border-soft-green"
                               placeholder="https://instagram.com/...">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-zinc-400 mb-1.5">YouTube URL</label>
                        <input type="text" name="social_youtube"
                               value="{{ $settings['social_youtube'] ?? '' }}"
                               class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-soft-green/50 focus:border-soft-green"
                               placeholder="https://youtube.com/...">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-soft-green hover:bg-green-600 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
@endsection
