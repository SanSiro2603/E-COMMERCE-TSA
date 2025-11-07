@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="max-w-6xl mx-auto p-4 space-y-6">
    <h1 class="text-2xl font-bold">Checkout</h1>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded-lg border border-red-300">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-lg border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('pembeli.pesanan.store') }}" method="POST" id="checkoutForm">
        @csrf

        <!-- Hidden inputs untuk RajaOngkir -->
        <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name') }}">
        @error('province_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

        <input type="hidden" name="city_name" id="city_name" value="{{ old('city_name') }}">
        @error('city_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

        <input type="hidden" name="city_type" id="city_type" value="{{ old('city_type') }}">
        @error('city_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

        <div class="grid md:grid-cols-3 gap-6">
            <!-- Left: Form -->
            <div class="md:col-span-2 space-y-6">
                <!-- Informasi Penerima -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="font-semibold mb-4">Informasi Penerima</h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="recipient_name" required class="w-full p-2 border rounded @error('recipient_name') border-red-500 @enderror" value="{{ old('recipient_name') }}" placeholder="Masukkan nama lengkap">
                            @error('recipient_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block mb-1">No. Telepon <span class="text-red-500">*</span></label>
                            <input type="text" name="recipient_phone" required class="w-full p-2 border rounded @error('recipient_phone') border-red-500 @enderror" value="{{ old('recipient_phone') }}" placeholder="08xxxxxxxxxx">
                            @error('recipient_phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Alamat Pengiriman -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="font-semibold mb-4">Alamat Pengiriman</h2>
                    <div class="space-y-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-1">Provinsi <span class="text-red-500">*</span></label>
                                <select name="province_id" id="province" required class="w-full p-2 border rounded @error('province_id') border-red-500 @enderror">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                                @error('province_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block mb-1">Kota/Kabupaten <span class="text-red-500">*</span></label>
                                <select name="city_id" id="city" required class="w-full p-2 border rounded @error('city_id') border-red-500 @enderror" disabled>
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                                @error('city_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block mb-1">Kode Pos</label>
                            <input type="text" name="postal_code" class="w-full p-2 border rounded @error('postal_code') border-red-500 @enderror" value="{{ old('postal_code') }}" placeholder="Otomatis terisi atau isi manual">
                            @error('postal_code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="shipping_address" rows="3" required class="w-full p-2 border rounded @error('shipping_address') border-red-500 @enderror" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, dll.">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-1">Kurir Pengiriman <span class="text-red-500">*</span></label>
                            <select name="courier" required class="w-full p-2 border rounded @error('courier') border-red-500 @enderror">
                                <option value="">Pilih Kurir</option>
                                <option value="JNE" {{ old('courier') == 'JNE' ? 'selected' : '' }}>JNE</option>
                                <option value="JNT" {{ old('courier') == 'JNT' ? 'selected' : '' }}>J&T</option>
                                <option value="SiCepat" {{ old('courier') == 'SiCepat' ? 'selected' : '' }}>SiCepat</option>
                                <option value="Anteraja" {{ old('courier') == 'Anteraja' ? 'selected' : '' }}>Anteraja</option>
                            </select>
                            @error('courier')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Daftar Produk -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="font-semibold mb-4">Produk Dipesan ({{ $carts->count() }})</h2>
                    <div class="space-y-3">
                        @foreach($carts as $cart)
                            <div class="flex justify-between text-sm pb-3 border-b last:border-0">
                                <div>
                                    <p class="font-medium">{{ $cart->product->name }}</p>
                                    <p class="text-gray-500">{{ $cart->quantity }} × Rp {{ number_format($cart->product->price, 0, ',', '.') }}</p>
                                </div>
                                <p class="font-medium">Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right: Ringkasan Pesanan -->
            <div>
                <div class="bg-white p-6 rounded-lg shadow sticky top-4">
                    <h2 class="font-semibold mb-4">Ringkasan Pesanan</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Subtotal Produk</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ongkos Kirim</span>
                            <span>Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t pt-3 mt-3">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total Bayar</span>
                                <span class="text-green-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition">
                        Buat Pesanan & Lanjut Pembayaran
                    </button>

                    <a href="{{ route('pembeli.keranjang.index') }}" class="block text-center mt-3 text-sm text-gray-600 hover:text-gray-800">
                        ← Kembali ke Keranjang
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const provinceNameInput = document.getElementById('province_name');
    const cityNameInput =  document.getElementById('city_name');
    const cityTypeInput = document.getElementById('city_type'); // tetap kirim type ke backend kalau perlu

    // FUNGSI CLEAN NAME: Hilangkan "Kabupaten"/"Kota " di depan & belakang
    function cleanCityName(rawName) {
        return rawName
            .replace(/^(Kabupaten|Kota)\s+/i, '')  // Hilangkan di depan
            .replace(/\s+(Kabupaten|Kota)$/i, '')  // Hilangkan di belakang (jarang)
            .trim();
    }

    // Load Provinces
    fetch('{{ route('pembeli.rajaongkir.provinces') }}')
        .then(r => r.ok ? r.json() : Promise.reject('Gagal load provinsi'))
        .then(provinces => {
            provinces.forEach(p => {
                const id = p.province_id || p.id;
                const name = p.province || p.name;
                provinceSelect.appendChild(new Option(name, id));
            });

            @if(old('province_id'))
                provinceSelect.value = '{{ old('province_id') }}';
                provinceNameInput.value = '{{ old('province_name') }}';
                provinceSelect.dispatchEvent(new Event('change'));
            @endif
        })
        .catch(err => {
            console.error(err);
            alert('Gagal memuat provinsi. Cek koneksi atau API Key!');
        });

    // Province → City
    provinceSelect.addEventListener('change', function () {
        const provinceId = this.value;
        provinceNameInput.value = this.options[this.selectedIndex]?.text || '';

        citySelect.innerHTML = '<option value="">Memuat kota...</option>';
        citySelect.disabled = true;

        if (!provinceId) {
            citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
            citySelect.disabled = false;
            return;
        }

        fetch(`{{ route('pembeli.rajaongkir.cities') }}?province_id=${provinceId}`)
            .then(r => r.ok ? r.json() : Promise.reject('Gagal load kota'))
            .then(cities => {
                citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                cities.forEach(c => {
                    const cityId = c.city_id || c.id;
                    let rawName = c.city_name || c.name || '';
                    const type = (c.type || '').toLowerCase() === 'kota' ? 'Kota' : 'Kabupaten';

                    // CLEAN NAME: HILANGKAN "Kabupaten"/"Kota"
                    const cleanName = cleanCityName(rawName);

                    // Simpan type di dataset, tapi tampilan HANYA NAMA KOTA
                    const opt = new Option(cleanName, cityId);
                    opt.dataset.type = type;        // untuk backend
                    opt.dataset.raw = rawName;      // cadangan
                    citySelect.appendChild(opt);
                });
                citySelect.disabled = false;

                // Restore old value
                @if(old('city_id'))
                    citySelect.value = '{{ old('city_id') }}';
                    updateCityHiddenFields();
                @endif
            })
            .catch(err => {
                console.error(err);
                citySelect.innerHTML = '<option value="">Gagal memuat kota</option>';
                citySelect.disabled = false;
            });
    });

    // Update hidden fields saat city berubah
    function updateCityHiddenFields() {
        const selected = citySelect.options[citySelect.selectedIndex];
        if (!selected || !selected.value) {
            cityNameInput.value = '';
            cityTypeInput.value = '';
            return;
        }

        const cleanName = selected.text; // sudah clean
        const type = selected.dataset.type || '';

        cityNameInput.value = cleanName;
        cityTypeInput.value = type; // tetap kirim "Kota" atau "Kabupaten" ke backend kalau perlu
    }

    citySelect.addEventListener('change', updateCityHiddenFields);

    // Trigger pertama kalau ada old('city_id')
    @if(old('city_id'))
        const waitForCities = setInterval(() => {
            if (citySelect.options.length > 1) {
                citySelect.value = '{{ old('city_id') }}';
                updateCityHiddenFields();
                clearInterval(waitForCities);
            }
        }, 100);
    @endif
});
</script>
@endsection