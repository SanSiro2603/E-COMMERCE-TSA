@if(in_array('title', $fields, true))
    <div>
        <label class="mb-1.5 block text-xs font-bold text-gray-600 dark:text-zinc-400">Judul</label>
        <input type="text" name="title_en" value="{{ $item?->title_en }}" required maxlength="255" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
    </div>
@endif
@if(in_array('category', $fields, true))
    <div>
        <label class="mb-1.5 block text-xs font-bold text-gray-600 dark:text-zinc-400">Category</label>
        <input type="text" name="category" value="{{ data_get($item?->metadata, 'category') }}" required maxlength="100" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
    </div>
@endif
@if(in_array('description', $fields, true))
    <div class="md:col-span-2">
        <label class="mb-1.5 block text-xs font-bold text-gray-600 dark:text-zinc-400">Deskripsi</label>
        <textarea name="description_en" rows="3" required maxlength="5000" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">{{ $item?->description_en }}</textarea>
    </div>
@endif
@if(in_array('image', $fields, true))
    <div>
        <label class="mb-1.5 block text-xs font-bold text-gray-600 dark:text-zinc-400">Gambar {{ $item ? '(kosongkan jika tidak diganti)' : '' }}</label>
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" {{ $item ? '' : 'required' }} class="block w-full text-xs text-gray-500 file:rounded-lg file:border-0 file:bg-green-50 file:px-3 file:py-2 file:font-bold file:text-green-700">
    </div>
@endif
@if(in_array('alt', $fields, true))
    <div>
        <label class="mb-1.5 block text-xs font-bold text-gray-600 dark:text-zinc-400">Alt Gambar</label>
        <input type="text" name="alt" value="{{ data_get($item?->metadata, 'alt') }}" required maxlength="200" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
        <p class="mt-1 text-[11px] text-gray-400">Bisa diubah. Tidak tampil sebagai caption; digunakan pembaca layar dan saat gambar gagal dimuat.</p>
    </div>
@endif
<div>
    <label class="mb-1.5 block text-xs font-bold text-gray-600 dark:text-zinc-400">Nomor Urut</label>
    <input type="number" name="sort_order" value="{{ $item?->sort_order ?? (($collections[$section]->max('sort_order') ?? 0) + 1) }}" required min="1" max="65535" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
</div>
