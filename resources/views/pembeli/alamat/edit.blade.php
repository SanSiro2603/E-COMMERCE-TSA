{{-- resources/views/pembeli/alamat/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Alamat')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Alamat</h1>

        <form action="{{ route('pembeli.alamat.update', $alamat) }}" method="POST" id="addressForm">
            @csrf @method('PUT')

            <!-- Sama seperti create, tapi value dari $alamat -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">
                    Label Alamat <span class="text-red-500">*</span>
                </label>
                <input type="text" name="label" required maxlength="50"
                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-zinc-600 rounded-lg 
                              focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:bg-zinc-700 dark:text-white"
                       value="{{ old('label', $alamat->label) }}">
                @error('label') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">
                        Nama Penerima <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="recipient_name" required
                           class="w-full px-4 py-2.5 border border-gray-300 dark:border-zinc-600 rounded-lg 
                                  focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:bg-zinc-700 dark:text-white"
                           value="{{ old('recipient_name', $alamat->recipient_name) }}">
                    @error('recipient_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">
                        No. Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="recipient_phone" required
                           class="w-full px-4 py-2.5 border border-gray-300 dark:border-zinc-600 rounded-lg 
                                  focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:bg-zinc-700 dark:text-white"
                           value="{{ old('recipient_phone', $alamat->recipient_phone) }}">
                    @error('recipient_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">
                        Provinsi <span class="text-red-500">*</span>
                    </label>
                    <select name="province_id" id="province" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-zinc-600 rounded-lg 
                                   focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:bg-zinc-700 dark:text-white">
                        <option value="">Pilih Provinsi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">
                        Kota/Kabupaten <span class="text-red-500">*</span>
                    </label>
                    <select name="city_id" id="city" required disabled
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-zinc-600 rounded-lg 
                                   focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:bg-zinc-700 dark:text-white">
                        <option value="">Pilih Kota/Kabupaten</option>
                    </select>
                </div>
            </div>

            <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name', $alamat->province_name) }}">
            <input type="hidden" name="city_name" id="city_name" value="{{ old('city_name', $alamat->city_name) }}">
            <input type="hidden" name="city_type" id="city_type" value="{{ old('city_type', $alamat->city_type) }}">

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Kode Pos</label>
                <input type="text" name="postal_code" maxlength="10"
                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-zinc-600 rounded-lg 
                              focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:bg-zinc-700 dark:text-white"
                       value="{{ old('postal_code', $alamat->postal_code) }}">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">
                    Alamat Lengkap <span class="text-red-500">*</span>
                </label>
                <textarea name="full_address" rows="4" required
                          class="w-full px-4 py-2.5 border border-gray-300 dark:border-zinc-600 rounded-lg 
                                 focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:bg-zinc-700 dark:text-white">{{ old('full_address', $alamat->full_address) }}</textarea>
                @error('full_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_default" value="1" {{ old('is_default', $alamat->is_default) ? 'checked' : '' }}
                           class="w-5 h-5 text-soft-green rounded focus:ring-soft-green">
                    <span class="text-sm font-medium text-gray-700 dark:text-zinc-300">Jadikan alamat utama</span>
                </label>
            </div>

            <div class="flex gap-3 justify-end">
                <a href="{{ route('pembeli.alamat.index') }}"
                   class="px-5 py-2.5 border border-gray-300 dark:border-zinc-600 rounded-lg text-gray-700 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-700 transition">
                    Batal
                </a>
                <button type="submit"
                        class="bg-gradient-to-r from-soft-green to-primary text-white px-6 py-2.5 rounded-lg font-medium hover:shadow-lg transition-shadow flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">update</span>
                    Update Alamat
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const province = document.getElementById('province');
    const city = document.getElementById('city');
    const provinceName = document.getElementById('province_name');
    const cityName = document.getElementById('city_name');
    const cityType = document.getElementById('city_type');

    /**
     * ======================================================
     * 1. LOAD PROVINCE (dengan selected otomatis)
     * ======================================================
     */
    fetch('{{ route("pembeli.rajaongkir.provinces") }}')
        .then(r => r.json())
        .then(provinces => {
            provinces.forEach(p => {
                const opt = new Option(p.name, p.province_id);
                opt.dataset.name = p.name;

                if (p.province_id == '{{ old('province_id', $alamat->province_id) }}') {
                    opt.selected = true;
                }

                province.appendChild(opt);
            });

            // Trigger load cities
            province.dispatchEvent(new Event('change'));
        });


    /**
     * ======================================================
     * 2. LOAD CITY (FIX: gunakan c.city_name & c.type)
     * ======================================================
     */
    province.addEventListener('change', function () {
        const id = this.value;
        city.innerHTML = '<option value="">Memuat...</option>';
        city.disabled = true;

        if (!id) return;

        fetch(`{{ route("pembeli.rajaongkir.cities") }}?province_id=${id}`)
            .then(r => r.json())
            .then(cities => {
                city.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';

                cities.forEach(c => {
                    // FIX: gunakan c.city_name, bukan c.name
                    const opt = new Option(c.city_name, c.city_id);

                    // FIX: type sesuai dari controller (Kota / Kabupaten)
                    opt.dataset.type = c.type;

                    // Selected jika sedang edit alamat
                    if (c.city_id == '{{ old('city_id', $alamat->city_id) }}') {
                        opt.selected = true;
                    }

                    city.appendChild(opt);
                });

                city.disabled = false;

                // Agar hidden field ikut terisi
                city.dispatchEvent(new Event('change'));
            });
    });


    /**
     * ======================================================
     * 3. SIMPAN HIDDEN FIELD
     * ======================================================
     */
    province.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        provinceName.value = selected.dataset.name || selected.text;
    });

    city.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        cityName.value = selected.text;         // Nama kota/kabupaten
        cityType.value = selected.dataset.type; // Kota / Kabupaten
    });

});
</script>
@endpush
@endsection