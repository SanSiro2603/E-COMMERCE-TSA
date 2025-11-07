@extends('layouts.app')
@section('title', 'Edit Pesanan - Lembah Hijau')

@section('content')
<div class="max-w-6xl mx-auto p-4 space-y-6">
    <h1 class="text-2xl font-bold">Edit Pesanan</h1>

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

    <form action="{{ route('pembeli.pesanan.update', $order->id) }}" method="POST" id="editForm">
        @csrf
        @method('PUT')

        <!-- Hidden inputs untuk RajaOngkir -->
        <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name', $order->province) }}">
        @error('province_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

        <input type="hidden" name="city_name" id="city_name" value="{{ old('city_name', $order->city_name ?? explode(' ', $order->city)[1] ?? '') }}">
        @error('city_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

        <input type="hidden" name="city_type" id="city_type" value="{{ old('city_type', $order->city_type ?? (str_starts_with($order->city, 'Kota') ? 'Kota' : 'Kabupaten')) }}">
        @error('city_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

        <div class="grid md:grid-cols-3 gap-6">
            <!-- Left: Form -->
            <div class="md:col-span-2 space-y-6">
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
                                @error('province_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block mb-1">Kota/Kabupaten <span class="text-red-500">*</span></label>
                                <select name="city_id" id="city" required class="w-full p-2 border rounded @error('city_id') border-red-500 @enderror" disabled>
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                                @error('city_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block mb-1">Kode Pos</label>
                            <input type="text" name="postal_code" class="w-full p-2 border rounded @error('postal_code') border-red-500 @enderror"
                                   value="{{ old('postal_code', $order->postal_code) }}" placeholder="Otomatis terisi">
                            @error('postal_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="shipping_address" rows="3" required class="w-full p-2 border rounded @error('shipping_address') border-red-500 @enderror"
                                      placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, dll.">{{ old('shipping_address', $order->shipping_address) }}</textarea>
                            @error('shipping_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block mb-1">Kurir Pengiriman <span class="text-red-500">*</span></label>
                            <select name="courier" required class="w-full p-2 border rounded @error('courier') border-red-500 @enderror">
                                <option value="">Pilih Kurir</option>
                                <option value="JNE" {{ old('courier', $order->courier) == 'JNE' ? 'selected' : '' }}>JNE</option>
                                <option value="JNT" {{ old('courier', $order->courier) == 'JNT' ? 'selected' : '' }}>J&T</option>
                                <option value="SiCepat" {{ old('courier', $order->courier) == 'SiCepat' ? 'selected' : '' }}>SiCepat</option>
                                <option value="Anteraja" {{ old('courier', $order->courier) == 'Anteraja' ? 'selected' : '' }}>Anteraja</option>
                            </select>
                            @error('courier') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Daftar Produk -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="font-semibold mb-4">Produk Dipesan ({{ $order->items->count() }})</h2>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex justify-between text-sm pb-3 border-b last:border-0">
                                <div>
                                    <p class="font-medium">{{ $item->product->name }}</p>
                                    <p class="text-gray-500">{{ $item->quantity }} × Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                </div>
                                <p class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
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
                            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ongkos Kirim</span>
                            <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t pt-3 mt-3">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total Bayar</span>
                                <span class="text-green-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition">
                        Perbarui Pesanan
                    </button>

                    <a href="{{ route('pembeli.pesanan.index') }}" class="block text-center mt-3 text-sm text-gray-600 hover:text-gray-800">
                        ← Kembali ke Daftar Pesanan
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
    const cityNameInput = document.getElementById('city_name');
    const cityTypeInput = document.getElementById('city_type');

    function setCityTypeAndName() {
        const selected = citySelect.options[citySelect.selectedIndex];
        if (!selected || !selected.value) {
            cityTypeInput.value = '';
            cityNameInput.value = '';
            return;
        }
        const text = selected.text.trim();
        const parts = text.split(' ');
        let type = '';
        let nameStart = 0;
        if (parts[0] === 'Kota' || parts[0] === 'Kabupaten') {
            type = parts[0];
            nameStart = 1;
        }
        cityTypeInput.value = type;
        cityNameInput.value = parts.slice(nameStart).join(' ');
    }

    // Load Provinces
    fetch('{{ route('pembeli.rajaongkir.provinces') }}')
        .then(r => r.ok ? r.json() : Promise.reject())
        .then(provinces => {
            provinces.forEach(p => {
                const id = p.province_id || p.id;
                const name = p.province || p.name;
                provinceSelect.appendChild(new Option(name, id));
            });

            // Restore province lama
            const oldProvinceId = '{{ old('province_id', $order->province_id) }}';
            if (oldProvinceId) {
                provinceSelect.value = oldProvinceId;
                provinceNameInput.value = '{{ old('province_name', $order->province) }}';
                provinceSelect.dispatchEvent(new Event('change'));
            }
        })
        .catch(() => alert('Gagal memuat provinsi'));

    // Province change
    provinceSelect.addEventListener('change', function () {
        const provinceId = this.value;
        provinceNameInput.value = this.options[this.selectedIndex]?.text || '';

        citySelect.innerHTML = '<option value="">Memuat kota...</option>';
        citySelect.disabled = true;
        cityTypeInput.value = '';
        cityNameInput.value = '';

        if (!provinceId) {
            citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
            citySelect.disabled = false;
            return;
        }

        fetch(`{{ route('pembeli.rajaongkir.cities') }}?province_id=${provinceId}`)
            .then(r => r.ok ? r.json() : Promise.reject())
            .then(cities => {
                citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                cities.forEach(c => {
                    const cityId = c.city_id || c.id;
                    const cityName = c.city_name || c.name;
                    const type = c.type || (c.city_type === 'Kota' ? 'Kota' : 'Kabupaten');
                    const label = type ? `${type} ${cityName}` : cityName;
                    citySelect.appendChild(new Option(label, cityId));
                });
                citySelect.disabled = false;

                // Restore city lama
                const oldCityId = '{{ old('city_id', $order->city_id) }}';
                if (oldCityId) {
                    citySelect.value = oldCityId;
                    setCityTypeAndName();
                }
            })
            .catch(() => {
                citySelect.innerHTML = '<option value="">Gagal memuat kota</option>';
                citySelect.disabled = false;
            });
    });

    // City change
    citySelect.addEventListener('change', setCityTypeAndName);

    // Trigger awal jika sudah ada data
    @if(old('province_id') || $order->province_id)
        provinceSelect.dispatchEvent(new Event('change'));
    @endif
});
</script>
@endsection