{{-- resources/views/pembeli/alamat/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Tambah Alamat')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Tambah Alamat Baru</h1>

        <form action="{{ route('pembeli.alamat.store') }}" method="POST" id="addressForm">
            @csrf

            <!-- Label Alamat -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">
                    Label Alamat <span class="text-red-500">*</span>
                </label>
                <input type="text" name="label" required maxlength="50"
                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-zinc-600 rounded-lg 
                              focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:bg-zinc-700 dark:text-white"
                       placeholder="Contoh: Rumah, Kantor, Kos" value="{{ old('label') }}">
                @error('label') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Nama Penerima & Telepon -->
            <div class="grid md:grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">
                        Nama Penerima <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="recipient_name" required
                           class="w-full px-4 py-2.5 border border-gray-300 dark:border-zinc-600 rounded-lg 
                                  focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:bg-zinc-700 dark:text-white"
                           placeholder="Budi Santoso" value="{{ old('recipient_name') }}">
                    @error('recipient_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">
                        No. Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="recipient_phone" required
                           class="w-full px-4 py-2.5 border border-gray-300 dark:border-zinc-600 rounded-lg 
                                  focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:bg-zinc-700 dark:text-white"
                           placeholder="081234567890" value="{{ old('recipient_phone') }}">
                    @error('recipient_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Provinsi & Kota -->
            <div class="grid md:grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">
                        Provinsi <span class="text-red-500">*</span>
                    </label>
                    <select name="province_id" id="province" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-zinc-600 rounded-lg 
                                   focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:bg-zinc-700 dark:text-white">
                        <option value="">Memuat provinsi...</option>
                    </select>
                    @error('province_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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
                    @error('city_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Hidden Fields -->
            <input type="hidden" name="province_name" id="province_name">
            <input type="hidden" name="city_name" id="city_name">
            <input type="hidden" name="city_type" id="city_type">

            <!-- Kode Pos & Alamat -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Kode Pos</label>
                <input type="text" name="postal_code" maxlength="10"
                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-zinc-600 rounded-lg 
                              focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:bg-zinc-700 dark:text-white"
                       placeholder="Contoh: 35111" value="{{ old('postal_code') }}">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">
                    Alamat Lengkap <span class="text-red-500">*</span>
                </label>
                <textarea name="full_address" rows="4" required
                          class="w-full px-4 py-2.5 border border-gray-300 dark:border-zinc-600 rounded-lg 
                                 focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:bg-zinc-700 dark:text-white"
                          placeholder="Jalan, nomor rumah, RT/RW, kelurahan, kecamatan...">{{ old('full_address') }}</textarea>
                @error('full_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Jadikan Utama -->
            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_default" value="1" class="w-5 h-5 text-soft-green rounded focus:ring-soft-green">
                    <span class="text-sm font-medium text-gray-700 dark:text-zinc-300">Jadikan alamat utama</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 justify-end">
                <a href="{{ route('pembeli.alamat.index') }}"
                   class="px-5 py-2.5 border border-gray-300 dark:border-zinc-600 rounded-lg text-gray-700 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-700 transition">
                    Batal
                </a>
                <button type="submit"
                        class="bg-gradient-to-r from-soft-green to-primary text-white px-6 py-2.5 rounded-lg font-medium hover:shadow-lg transition-shadow flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Simpan Alamat
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

    // =====================================================
    // LOAD PROVINSI
    // =====================================================
    fetch('{{ route("pembeli.rajaongkir.provinces") }}')
        .then(r => r.json())
        .then(data => {

            province.innerHTML = '<option value="">Pilih Provinsi</option>';

            data.forEach(p => {
                const opt = new Option(p.name, p.province_id);
                opt.dataset.name = p.name;        // simpan nama provinsi
                province.appendChild(opt);
            });

        })
        .catch(() => {
            province.innerHTML = '<option value="">Gagal memuat provinsi</option>';
        });


    // =====================================================
    // LOAD KOTA SETELAH PROVINSI DIPILIH
    // =====================================================
    province.addEventListener('change', function () {
        const id = this.value;

        // isi hidden province_name
        const selected = this.options[this.selectedIndex];
        provinceName.value = selected.dataset.name ?? selected.text;

        city.innerHTML = '<option value="">Memuat kota...</option>';
        city.disabled = true;

        if (!id) return;

        fetch(`{{ route("pembeli.rajaongkir.cities") }}?province_id=${id}`)
            .then(r => r.json())
            .then(data => {

                city.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';

                data.forEach(kota => {

                    const fullName = `${kota.type} ${kota.city_name}`;

                    const opt = new Option(fullName, kota.city_id);
                    opt.dataset.type = kota.type;          // Kota / Kabupaten
                    opt.dataset.name = kota.city_name;      // Nama kota murni
                    city.appendChild(opt);
                });

                city.disabled = false;

            })
            .catch(() => {
                city.innerHTML = '<option value="">Gagal memuat kota</option>';
            });
    });


    // =====================================================
    // ISI HIDDEN INPUT SAAT KOTA DIPILIH
    // =====================================================
    city.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];

        if (!selected) return;

        cityName.value = selected.dataset.name;   // contoh: "Lampung Selatan"
        cityType.value = selected.dataset.type;   // "Kabupaten" atau "Kota"
    });

});
</script>
@endpush
@endsection
