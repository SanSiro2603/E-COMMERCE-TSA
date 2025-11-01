{{-- resources/views/admin/categories/_form.blade.php --}}
<form action="{{ $action }}" method="POST">
    @csrf
    @if(isset($category)) @method('PUT') @endif

    <div class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-charcoal">Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required
                   class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-soft-green focus:border-soft-green">
            @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-charcoal">Deskripsi (Opsional)</label>
            <textarea name="description" rows="3"
                      class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-soft-green focus:border-soft-green">{{ old('description', $category->description ?? '') }}</textarea>
        </div>

        <div class="flex items-center gap-3">
            <input type="checkbox" name="is_active" value="1"
                   {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                   class="rounded text-soft-green focus:ring-soft-green">
            <label class="text-sm font-medium text-charcoal">Aktif (Tampil di toko)</label>
        </div>
    </div>

    <div class="mt-6 flex gap-3">
        <button type="submit"
                class="px-6 py-2 bg-gradient-to-r from-soft-green to-[#8fcf72] text-white font-medium rounded-lg hover:shadow-lg transition">
            {{ $buttonText ?? 'Simpan' }}
        </button>
        <a href="{{ route('admin.categories.index') }}"
           class="px-6 py-2 border border-gray-300 rounded-lg text-charcoal hover:bg-gray-50">
            Batal
        </a>
    </div>
</form>