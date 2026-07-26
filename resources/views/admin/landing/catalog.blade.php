@extends('layouts.admin')

@section('title', 'CMS Catalog')

@section('content')
@php $inputClass = 'w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-green-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-800 dark:text-white'; @endphp
<div class="mx-auto max-w-7xl space-y-6">
    <div>
        <p class="text-xs font-bold uppercase tracking-[0.15em] text-green-600">CMS Landing Page</p>
        <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">Catalog</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">Catalog landing ini terpisah dari produk, harga, stok, dan transaksi e-commerce. Urutan dimulai dari 1 dan posisi lain akan bergeser otomatis.</p>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700 dark:border-green-900 dark:bg-green-950/40 dark:text-green-300">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300">
            <p class="font-bold">Periksa kembali data berikut:</p><ul class="mt-2 list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <nav class="flex flex-wrap gap-2 rounded-2xl border border-gray-200 bg-white p-2 dark:border-zinc-800 dark:bg-zinc-900">
        @foreach(['content' => 'Konten Halaman', 'categories' => 'Kategori & Family', 'animals' => 'Hewan & Galeri'] as $key => $label)
            <a href="{{ route('admin.landing.catalog.index', ['tab' => $key]) }}" class="rounded-xl px-4 py-2.5 text-sm font-bold {{ $tab === $key ? 'bg-green-600 text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">{{ $label }}</a>
        @endforeach
    </nav>

    @if($tab === 'content')
        <form action="{{ route('admin.landing.pages.settings.update', 'catalog') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @foreach($definition['settings'] as $group)
                <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <h2 class="border-b border-gray-100 px-6 py-4 font-bold text-gray-900 dark:border-zinc-800 dark:text-white">{{ $group['label'] }}</h2>
                    <div class="grid gap-5 p-6 md:grid-cols-2">
                        @foreach($group['fields'] as $key => $field)
                            @php $setting = $settings->get($key); @endphp
                            <label class="block {{ $field['type'] === 'textarea' ? 'md:col-span-2' : '' }}">
                                <span class="mb-1.5 block text-xs font-bold text-gray-600 dark:text-zinc-400">{{ $field['label'] }}</span>
                                @if($field['type'] === 'textarea')
                                    <textarea name="settings[{{ $key }}]" rows="4" class="{{ $inputClass }}">{{ old("settings.$key", $setting?->value_en) }}</textarea>
                                @elseif($field['type'] === 'image')
                                    @if($setting?->asset_url)<img src="{{ $setting->asset_url }}" alt="" class="mb-3 h-32 w-full rounded-xl object-cover">@endif
                                    <input type="file" name="assets[{{ $key }}]" accept="image/jpeg,image/png,image/webp" class="block w-full text-xs text-gray-500 file:rounded-lg file:border-0 file:bg-green-50 file:px-3 file:py-2 file:font-bold file:text-green-700">
                                    <span class="mt-1 block text-[11px] text-gray-400">JPEG, PNG, atau WebP. Maksimal 3 MB.</span>
                                @else
                                    <input name="settings[{{ $key }}]" value="{{ old("settings.$key", $setting?->value_en) }}" class="{{ $inputClass }}">
                                @endif
                                @if(str_ends_with($key, '_alt'))
                                    <span class="mt-1 block text-[11px] text-gray-400">Bisa diubah. Tidak tampil sebagai caption; digunakan pembaca layar dan saat gambar gagal dimuat.</span>
                                @endif
                            </label>
                        @endforeach
                    </div>
                </section>
            @endforeach
            <div class="flex justify-end"><button class="rounded-xl bg-green-600 px-6 py-3 text-sm font-bold text-white hover:bg-green-700">Simpan Konten Halaman</button></div>
        </form>
    @endif

    @if($tab === 'categories')
        <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <h2 class="font-bold text-gray-900 dark:text-white">Tambah Kategori</h2>
            <form action="{{ route('admin.landing.catalog.categories.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 grid gap-4 md:grid-cols-2">
                @csrf
                <label><span class="mb-1 block text-xs font-bold">Nama *</span><input name="name_en" required class="{{ $inputClass }}"></label>
                <label><span class="mb-1 block text-xs font-bold">Alt gambar *</span><input name="image_alt_en" required class="{{ $inputClass }}"><small class="mt-1 block text-gray-400">Bisa diubah. Tidak tampil sebagai caption; untuk pembaca layar dan gambar gagal dimuat.</small></label>
                <label class="md:col-span-2"><span class="mb-1 block text-xs font-bold">Deskripsi</span><textarea name="description_en" rows="3" class="{{ $inputClass }}"></textarea></label>
                <label><span class="mb-1 block text-xs font-bold">Gambar * (maks. 3 MB)</span><input type="file" name="image" required accept="image/jpeg,image/png,image/webp"></label>
                <label><span class="mb-1 block text-xs font-bold">Urutan *</span><input type="number" min="1" name="sort_order" value="{{ $categories->count() + 1 }}" required class="{{ $inputClass }}"></label>
                <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" checked> Aktif</label>
                <div class="md:col-span-2 flex justify-end"><button class="rounded-xl bg-green-600 px-5 py-2.5 text-sm font-bold text-white">Tambah Kategori</button></div>
            </form>
        </section>

        <section class="space-y-4">
            @foreach($categories as $category)
                <article class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex flex-wrap items-center gap-3">
                        <img src="{{ $category->image_url }}" alt="" class="h-16 w-16 rounded-xl object-cover">
                        <div class="flex-1"><h3 class="font-bold text-gray-900 dark:text-white">{{ $category->name_en }}</h3><p class="text-xs text-gray-400">/{{ $category->slug }} · {{ $category->families->count() }} family · {{ $category->animals_count }} hewan</p></div>
                        <form action="{{ route('admin.landing.catalog.categories.toggle', $category) }}" method="POST">@csrf @method('PATCH')<button class="rounded-lg border px-3 py-2 text-xs font-bold">{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button></form>
                        <form action="{{ route('admin.landing.catalog.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">@csrf @method('DELETE')<button class="rounded-lg border border-red-200 px-3 py-2 text-xs font-bold text-red-600">Hapus</button></form>
                    </div>
                    <details class="mt-4"><summary class="cursor-pointer text-xs font-bold text-blue-600">Edit kategori</summary>
                        <form action="{{ route('admin.landing.catalog.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="mt-3 grid gap-3 rounded-xl bg-gray-50 p-4 md:grid-cols-2 dark:bg-zinc-800/60">@csrf @method('PUT')
                            <label><span class="text-xs font-bold">Nama</span><input name="name_en" value="{{ $category->name_en }}" class="{{ $inputClass }}"></label>
                            <label><span class="text-xs font-bold">Alt gambar</span><input name="image_alt_en" value="{{ $category->image_alt_en }}" class="{{ $inputClass }}"><small class="mt-1 block text-gray-400">Bisa diubah dan tidak tampil sebagai caption.</small></label>
                            <label class="md:col-span-2"><span class="text-xs font-bold">Deskripsi</span><textarea name="description_en" class="{{ $inputClass }}">{{ $category->description_en }}</textarea></label>
                            <label><span class="text-xs font-bold">Ganti gambar</span><input type="file" name="image" accept="image/jpeg,image/png,image/webp"></label>
                            <label><span class="text-xs font-bold">Urutan</span><input type="number" min="1" name="sort_order" value="{{ $category->sort_order }}" class="{{ $inputClass }}"></label>
                            <label><input type="checkbox" name="is_active" value="1" @checked($category->is_active)> Aktif</label>
                            <div class="md:col-span-2 flex justify-end"><button class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-bold text-white">Simpan</button></div>
                        </form>
                    </details>
                </article>
            @endforeach
        </section>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-zinc-800 dark:bg-zinc-900">
            <h2 class="font-bold text-gray-900 dark:text-white">Tambah Family</h2>
            <form action="{{ route('admin.landing.catalog.families.store') }}" method="POST" class="mt-4 grid gap-4 md:grid-cols-4">@csrf
                <select name="category_id" required class="{{ $inputClass }}"><option value="">Pilih kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name_en }}</option>@endforeach</select>
                <input name="name_en" required placeholder="Nama family" class="{{ $inputClass }}">
                <input type="number" min="1" name="sort_order" value="1" required class="{{ $inputClass }}">
                <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" checked> Aktif</label>
                <div class="md:col-span-4 flex justify-end"><button class="rounded-xl bg-green-600 px-5 py-2.5 text-sm font-bold text-white">Tambah Family</button></div>
            </form>
        </section>
        <section class="grid gap-3 md:grid-cols-2">
            @foreach($families as $family)
                <article class="rounded-xl border border-gray-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-center justify-between gap-2"><div><b>{{ $family->name_en }}</b><p class="text-xs text-gray-400">{{ $family->category->name_en }} · {{ $family->animals_count }} hewan · /{{ $family->slug }}</p></div>
                    <div class="flex gap-1"><form action="{{ route('admin.landing.catalog.families.toggle', $family) }}" method="POST">@csrf @method('PATCH')<button class="rounded border px-2 py-1 text-xs">{{ $family->is_active ? 'Off' : 'On' }}</button></form><form action="{{ route('admin.landing.catalog.families.destroy', $family) }}" method="POST" onsubmit="return confirm('Hapus family ini?')">@csrf @method('DELETE')<button class="rounded border border-red-200 px-2 py-1 text-xs text-red-600">Hapus</button></form></div></div>
                    <details class="mt-3"><summary class="cursor-pointer text-xs font-bold text-blue-600">Edit</summary><form action="{{ route('admin.landing.catalog.families.update', $family) }}" method="POST" class="mt-3 grid gap-2">@csrf @method('PUT')
                        <select name="category_id" class="{{ $inputClass }}">@foreach($categories as $category)<option value="{{ $category->id }}" @selected($family->category_id === $category->id)>{{ $category->name_en }}</option>@endforeach</select>
                        <input name="name_en" value="{{ $family->name_en }}" class="{{ $inputClass }}"><input type="number" min="1" name="sort_order" value="{{ $family->sort_order }}" class="{{ $inputClass }}"><label><input type="checkbox" name="is_active" value="1" @checked($family->is_active)> Aktif</label><button class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-bold text-white">Simpan</button>
                    </form></details>
                </article>
            @endforeach
        </section>
    @endif

    @if($tab === 'animals')
        <section class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-zinc-800 dark:bg-zinc-900">
            <h2 class="font-bold text-gray-900 dark:text-white">Tambah Hewan</h2>
            <form action="{{ route('admin.landing.catalog.animals.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 grid gap-4 md:grid-cols-2">@csrf
                @include('admin.landing.partials.catalog-animal-fields', ['animal' => null, 'inputClass' => $inputClass])
                <div class="md:col-span-2 flex justify-end"><button class="rounded-xl bg-green-600 px-5 py-2.5 text-sm font-bold text-white">Tambah Hewan</button></div>
            </form>
        </section>
        <section class="space-y-4">
            @foreach($animals as $animal)
                <article class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex flex-wrap items-center gap-3"><img src="{{ $animal->main_image_url }}" alt="" class="h-20 w-24 rounded-xl object-cover"><div class="flex-1"><h3 class="font-bold">{{ $animal->name_en }}</h3><p class="text-xs text-gray-400">{{ $animal->category->name_en }} · {{ $animal->family->name_en }} · /{{ $animal->slug }} · {{ $animal->images->count() }} foto</p></div>
                        <form action="{{ route('admin.landing.catalog.animals.toggle', $animal) }}" method="POST">@csrf @method('PATCH')<button class="rounded-lg border px-3 py-2 text-xs font-bold">{{ $animal->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button></form>
                        <form action="{{ route('admin.landing.catalog.animals.destroy', $animal) }}" method="POST" onsubmit="return confirm('Hapus hewan dan semua galerinya?')">@csrf @method('DELETE')<button class="rounded-lg border border-red-200 px-3 py-2 text-xs font-bold text-red-600">Hapus</button></form>
                    </div>
                    <details class="mt-4"><summary class="cursor-pointer text-xs font-bold text-blue-600">Edit hewan</summary><form action="{{ route('admin.landing.catalog.animals.update', $animal) }}" method="POST" enctype="multipart/form-data" class="mt-3 grid gap-4 rounded-xl bg-gray-50 p-4 md:grid-cols-2 dark:bg-zinc-800/60">@csrf @method('PUT')
                        @include('admin.landing.partials.catalog-animal-fields', ['animal' => $animal, 'inputClass' => $inputClass])
                        <div class="md:col-span-2 flex justify-end"><button class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-bold text-white">Simpan Hewan</button></div>
                    </form></details>
                    <details class="mt-3"><summary class="cursor-pointer text-xs font-bold text-green-600">Kelola galeri ({{ $animal->images->count() }})</summary>
                        <form action="{{ route('admin.landing.catalog.images.store', $animal) }}" method="POST" enctype="multipart/form-data" class="mt-3 grid gap-3 rounded-xl border border-dashed p-4 md:grid-cols-4">@csrf
                            <input type="file" name="image" required accept="image/jpeg,image/png,image/webp"><input name="alt_en" required placeholder="Alt gambar" class="{{ $inputClass }}"><input type="number" min="1" name="sort_order" value="{{ $animal->images->count() + 1 }}" class="{{ $inputClass }}"><button class="rounded-lg bg-green-600 px-3 py-2 text-xs font-bold text-white">Tambah Foto</button>
                        </form>
                        <div class="mt-3 grid gap-3 md:grid-cols-2">@foreach($animal->images as $galleryImage)<form action="{{ route('admin.landing.catalog.images.update', [$animal, $galleryImage]) }}" method="POST" enctype="multipart/form-data" class="rounded-xl bg-gray-50 p-3 dark:bg-zinc-800/60">@csrf @method('PUT')
                            <img src="{{ $galleryImage->image_url }}" alt="" class="mb-2 h-28 w-full rounded-lg object-cover"><input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="text-xs"><input name="alt_en" value="{{ $galleryImage->alt_en }}" class="mt-2 {{ $inputClass }}"><input type="number" min="1" name="sort_order" value="{{ $galleryImage->sort_order }}" class="mt-2 {{ $inputClass }}"><div class="mt-2 flex gap-2"><button class="rounded bg-blue-600 px-3 py-2 text-xs font-bold text-white">Simpan</button><button type="submit" form="delete-image-{{ $galleryImage->id }}" class="rounded border border-red-200 px-3 py-2 text-xs text-red-600">Hapus</button></div>
                        </form><form id="delete-image-{{ $galleryImage->id }}" action="{{ route('admin.landing.catalog.images.destroy', [$animal, $galleryImage]) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">@csrf @method('DELETE')</form>@endforeach</div>
                    </details>
                </article>
            @endforeach
        </section>
    @endif
</div>
@endsection
