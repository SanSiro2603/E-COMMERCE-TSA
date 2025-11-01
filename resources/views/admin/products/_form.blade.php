{{-- resources/views/admin/products/_form.blade.php --}}
<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($product)) @method('PUT') @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-charcoal">Kategori</label>
            <select name="category_id" required class="mt-1 block w-full rounded-lg border-gray-300">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-charcoal">Nama Hewan</label>
            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required
                   class="mt-1 block w-full rounded-lg border-gray-300">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-charcoal">Deskripsi</label>
            <textarea name="description" rows="3" class="mt-1 block w-full rounded-lg border-gray-300">{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-charcoal">Harga (Rp)</label>
            <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" required
                   class="mt-1 block w-full rounded-lg border-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-charcoal">Stok</label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required
                   class="mt-1 block w-full rounded-lg border-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-charcoal">Tersedia Dari</label>
            <input type="date" name="available_from" value="{{ old('available_from', $product->available_from ?? '') }}"
                   class="mt-1 block w-full rounded-lg border-gray-300">
        </div>

        <div>
            <label class="flex items-center gap-2 mt-4">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                       class="rounded text-soft-green">
                <span class="text-sm">Aktif (Tampil di toko)</span>
            </label>
        </div>

        <div>
            <label class="block text-sm font-medium text-charcoal">Gambar Hewan</label>
            <input type="file" name="image" accept="image/*"
                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-soft-green/10 file:text-soft-green hover:file:bg-soft-green/20">
            @if(isset($product) && $product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="mt-2 h-20 w-20 object-cover rounded-lg">
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium text-charcoal">Sertifikat Kesehatan (PDF)</label>
            <input type="file" name="health_certificate" accept=".pdf"
                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-soft-green/10 file:text-soft-green hover:file:bg-soft-green/20">
            @if(isset($product) && $product->health_certificate)
                <a href="{{ asset('storage/' . $product->health_certificate) }}" target="_blank"
                   class="inline-block mt-2 text-xs text-soft-green underline">Lihat Sertifikat</a>
            @endif
        </div>
    </div>

    <div class="mt-6 flex gap-3">
        <button type="submit"
                class="px-6 py-2 bg-gradient-to-r from-soft-green to-[#8fcf72] text-white font-medium rounded-lg hover:shadow-lg transition">
            {{ $buttonText ?? 'Simpan' }}
        </button>
        <a href="{{ route('admin.products.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-charcoal hover:bg-gray-50">
            Batal
        </a>
    </div>
</form>