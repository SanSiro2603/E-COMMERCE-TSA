@extends('layouts.admin')

@section('title', 'CMS ' . $definition['label'])

@section('content')
<div class="mx-auto max-w-7xl space-y-6">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.15em] text-green-600">CMS Landing Page</p>
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $definition['label'] }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">Perubahan langsung tampil pada halaman publik. Urutan dimulai dari 1 dan posisi lain bergeser otomatis.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700 dark:border-green-900 dark:bg-green-950/40 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300">
            <p class="font-bold">Periksa kembali data berikut:</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.landing.pages.settings.update', $page) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @foreach($definition['settings'] as $group)
            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="border-b border-gray-100 px-6 py-4 dark:border-zinc-800">
                    <h2 class="font-bold text-gray-900 dark:text-white">{{ $group['label'] }}</h2>
                </div>
                <div class="grid gap-5 p-6 md:grid-cols-2">
                    @foreach($group['fields'] as $key => $field)
                        @php $setting = $settings->get($key); @endphp
                        <div class="{{ $field['type'] === 'textarea' ? 'md:col-span-2' : '' }}">
                            <label class="mb-1.5 block text-xs font-bold text-gray-600 dark:text-zinc-400">{{ $field['label'] }}</label>
                            @if($field['type'] === 'textarea')
                                <textarea name="settings[{{ $key }}]" rows="{{ $key === 'hero_title' ? 2 : 4 }}"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">{{ old("settings.$key", $setting?->value_en) }}</textarea>
                            @elseif($field['type'] === 'image')
                                @if($setting?->asset_url)
                                    <img src="{{ $setting->asset_url }}" alt="Preview {{ $field['label'] }}" class="mb-3 h-32 w-full rounded-xl border border-gray-200 object-cover dark:border-zinc-700">
                                @endif
                                <input type="file" name="assets[{{ $key }}]" accept="image/jpeg,image/png,image/webp"
                                    class="block w-full text-xs text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-green-50 file:px-3 file:py-2 file:font-bold file:text-green-700 dark:text-zinc-400 dark:file:bg-green-950 dark:file:text-green-300">
                                <p class="mt-1 text-[11px] text-gray-400">JPEG, PNG, atau WebP. Maksimal 3 MB.</p>
                            @else
                                <input type="text" name="settings[{{ $key }}]" value="{{ old("settings.$key", $setting?->value_en) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @endif
                            @if(str_ends_with($key, '_alt'))
                                <p class="mt-1 text-[11px] text-gray-400">Bisa diubah. Tidak tampil sebagai caption; digunakan pembaca layar dan saat gambar gagal dimuat.</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach

        <div class="sticky bottom-4 flex justify-end">
            <button class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-6 py-3 text-sm font-bold text-white shadow-lg transition hover:bg-green-700">
                <span class="material-symbols-outlined text-[18px]">save</span>Simpan Konten Halaman
            </button>
        </div>
    </form>

    @foreach($definition['collections'] as $section => $collection)
        @php $fields = $collection['fields']; @endphp
        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-zinc-800">
                <div>
                    <h2 class="font-bold text-gray-900 dark:text-white">{{ $collection['label'] }}</h2>
                    <p class="text-xs text-gray-400">Item aktif ditampilkan berdasarkan nomor urut.</p>
                </div>
                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-bold text-gray-600 dark:bg-zinc-800 dark:text-zinc-300">{{ $collections[$section]->count() }} item</span>
            </div>

            <details class="border-b border-gray-100 p-6 dark:border-zinc-800" {{ $errors->any() ? 'open' : '' }}>
                <summary class="cursor-pointer text-sm font-bold text-green-700 dark:text-green-400">+ Tambah {{ $collection['label'] }}</summary>
                <form action="{{ route('admin.landing.pages.items.store', [$page, $section]) }}" method="POST" enctype="multipart/form-data" class="mt-5 grid gap-4 md:grid-cols-2">
                    @csrf
                    @include('admin.landing.partials.item-fields', ['item' => null])
                    <div class="md:col-span-2 flex justify-end">
                        <button class="rounded-xl bg-green-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-green-700">Tambah Item</button>
                    </div>
                </form>
            </details>

            <div class="divide-y divide-gray-100 dark:divide-zinc-800">
                @forelse($collections[$section] as $item)
                    <article class="p-6">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start">
                            @if($item->image_url)
                                <img src="{{ $item->image_url }}" alt="{{ data_get($item->metadata, 'alt', $item->title_en) }}" class="h-24 w-32 shrink-0 rounded-xl object-cover">
                            @endif
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-600 dark:bg-zinc-800 dark:text-zinc-300">Urutan {{ $item->sort_order }}</span>
                                    <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $item->is_active ? 'bg-green-50 text-green-700 dark:bg-green-950 dark:text-green-300' : 'bg-red-50 text-red-600 dark:bg-red-950 dark:text-red-300' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                </div>
                                @if($item->title_en)<h3 class="mt-2 font-bold text-gray-900 dark:text-white">{{ $item->title_en }}</h3>@endif
                                @if($item->description_en)<p class="mt-1 line-clamp-2 text-sm text-gray-500 dark:text-zinc-400">{{ $item->description_en }}</p>@endif
                            </div>
                            <div class="flex shrink-0 gap-2">
                                <form action="{{ route('admin.landing.pages.items.toggle', [$page, $section, $item]) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-bold text-gray-600 hover:bg-gray-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800">{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                </form>
                                <form action="{{ route('admin.landing.pages.items.destroy', [$page, $section, $item]) }}" method="POST" onsubmit="return confirm('Hapus item ini?')">
                                    @csrf @method('DELETE')
                                    <button class="rounded-lg border border-red-200 px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50 dark:border-red-900 dark:hover:bg-red-950">Hapus</button>
                                </form>
                            </div>
                        </div>

                        <details class="mt-4">
                            <summary class="cursor-pointer text-xs font-bold text-blue-600 dark:text-blue-400">Edit item</summary>
                            <form action="{{ route('admin.landing.pages.items.update', [$page, $section, $item]) }}" method="POST" enctype="multipart/form-data" class="mt-4 grid gap-4 rounded-xl bg-gray-50 p-4 md:grid-cols-2 dark:bg-zinc-800/60">
                                @csrf @method('PUT')
                                @include('admin.landing.partials.item-fields', ['item' => $item])
                                <div class="md:col-span-2 flex justify-end">
                                    <button class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-blue-700">Simpan Item</button>
                                </div>
                            </form>
                        </details>
                    </article>
                @empty
                    <div class="p-10 text-center text-sm text-gray-400">Belum ada item pada section ini.</div>
                @endforelse
            </div>
        </section>
    @endforeach
</div>
@endsection
