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
        <input type="hidden" name="city_name" id="city_name" value="{{ old('city_name') }}">
        <input type="hidden" name="city_type" id="city_type" value="{{ old('city_type') }}">
        <input type="hidden" name="shipping_cost" id="shipping_cost" value="{{ old('shipping_cost', 0) }}">
        <input type="hidden" name="shipping_service" id="shipping_service" value="{{ old('shipping_service') }}">

        <div class="grid md:grid-cols-3 gap-6">
            <!-- LEFT SIDE -->
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
                            <input type="text" name="postal_code" class="w-full p-2 border rounded @error('postal_code') border-red-500 @enderror" value="{{ old('postal_code') }}" placeholder="Otomatis atau isi manual">
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
                            <select name="courier" id="courier" required class="w-full p-2 border rounded @error('courier') border-red-500 @enderror">
                                <option value="">Pilih Kurir</option>
                                <option value="jne" {{ old('courier') == 'jne' ? 'selected' : '' }}>JNE</option>
                                <option value="pos" {{ old('courier') == 'pos' ? 'selected' : '' }}>POS Indonesia</option>
                                <option value="tiki" {{ old('courier') == 'tiki' ? 'selected' : '' }}>TIKI</option>
                                <option value="jnt" {{ old('courier') == 'jnt' ? 'selected' : '' }}>J&T Express</option>
                                <option value="sicepat" {{ old('courier') == 'sicepat' ? 'selected' : '' }}>SiCepat</option>
                                <option value="anteraja" {{ old('courier') == 'anteraja' ? 'selected' : '' }}>AnterAja</option>
                            </select>
                            @error('courier')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Loading Ongkir -->
                        <div id="shippingLoading" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center space-x-3">
                                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.3 0 0 5.3 0 12h4z"></path>
                                </svg>
                                <span class="text-blue-700 text-sm font-medium">Menghitung ongkos kirim...</span>
                            </div>
                        </div>

                        <!-- Error Ongkir -->
                        <div id="shippingError" class="hidden bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-red-700 text-sm" id="shippingErrorText"></p>
                        </div>

                        <!-- Hasil Ongkir -->
                        <div id="shippingResult" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-gray-600">Layanan:</p>
                                    <p class="font-semibold text-gray-800" id="shippingServiceName"></p>
                                    <p class="text-xs text-gray-500" id="shippingEtd"></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Ongkos Kirim:</p>
                                    <p class="text-xl font-bold text-green-600" id="shippingCostDisplay">Rp 0</p>
                                </div>
                            </div>
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

            <!-- RIGHT SIDE -->
            <div>
                <div class="bg-white p-6 rounded-lg shadow sticky top-4">
                    <h2 class="font-semibold mb-4">Ringkasan Pesanan</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Subtotal Produk</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Berat</span>
                            <span id="totalWeightDisplay">{{ number_format($totalWeight / 1000, 2) }} kg</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ongkos Kirim</span>
                            <span id="shippingCostSummary" class="text-gray-400">Pilih tujuan</span>
                        </div>
                        <div class="border-t pt-3 mt-3">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total Bayar</span>
                                <span class="text-green-600" id="grandTotalDisplay">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="submitBtn" class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition disabled:bg-gray-400 disabled:cursor-not-allowed">
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
    const courierSelect = document.getElementById('courier');
    const submitBtn = document.getElementById('submitBtn');
    const provinceNameInput = document.getElementById('province_name');
    const cityNameInput = document.getElementById('city_name');
    const cityTypeInput = document.getElementById('city_type');
    const shippingCostInput = document.getElementById('shipping_cost');
    const shippingServiceInput = document.getElementById('shipping_service');
    const shippingLoading = document.getElementById('shippingLoading');
    const shippingError = document.getElementById('shippingError');
    const shippingErrorText = document.getElementById('shippingErrorText');
    const shippingResult = document.getElementById('shippingResult');
    const subtotal = {{ $subtotal }};
    const totalWeight = {{ $totalWeight }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    submitBtn.disabled = true;

    function formatRupiah(amount) {
        return 'Rp ' + parseInt(amount).toLocaleString('id-ID');
    }

    function updateGrandTotal() {
        const shippingCost = parseInt(shippingCostInput.value) || 0;
        const grandTotal = subtotal + shippingCost;
        document.getElementById('grandTotalDisplay').textContent = formatRupiah(grandTotal);
        document.getElementById('shippingCostSummary').textContent = shippingCost > 0 ? formatRupiah(shippingCost) : 'Pilih tujuan';
    }

    // Hitung Ongkir
  // Hitung Ongkir
function calculateShipping() {
    const cityId = citySelect.value;
    const courier = courierSelect.value;
    const provinceName = provinceSelect.options[provinceSelect.selectedIndex]?.text || '';

    if (!cityId || !courier || !provinceName) return;

    shippingLoading.classList.remove('hidden');
    shippingError.classList.add('hidden');
    shippingResult.classList.add('hidden');
    submitBtn.disabled = true;

    setTimeout(() => {
        shippingLoading.classList.add('hidden');

        let baseCost = 3000; // biaya dasar
        let costPerKg = 3000;
        let weightKg = Math.ceil(totalWeight / 1000);
        let distanceMultiplier = 1; // faktor pengali berdasarkan provinsi

        // Penentuan tarif berdasarkan jarak
        if (provinceName.includes('Lampung')) distanceMultiplier = 1;
        else if (provinceName.match(/Sumatera|Bengkulu|Jambi|Aceh|Riau|Kepulauan|Bangka/i)) distanceMultiplier = 1.5;
        else if (provinceName.match(/Jawa|Jakarta|Banten/i)) distanceMultiplier = 1.5;
        else if (provinceName.match(/Kalimantan|Sulawesi/i)) distanceMultiplier = 3;
        else if (provinceName.match(/NTT|NTB|Maluku/i)) distanceMultiplier = 3.5;
        else if (provinceName.match(/Papua/i)) distanceMultiplier = 5;

        // Hitung total
        let totalCost = (baseCost + (costPerKg * weightKg)) * distanceMultiplier;

        // Tambahan khusus kurir
        switch (courier) {
            case 'jne': totalCost += 1000; break;
            case 'pos': totalCost += 1000; break;
            case 'tiki': totalCost += 1000; break;
            case 'jnt': totalCost += 1000; break;
            case 'sicepat': totalCost += 1000; break;
            case 'anteraja': totalCost += 1000; break;
        }

        // Update tampilan
        shippingCostInput.value = totalCost;
        shippingServiceInput.value = 'Reguler';
        document.getElementById('shippingServiceName').textContent = `${courier.toUpperCase()} - Reguler`;
        document.getElementById('shippingEtd').textContent = `Estimasi: ${2 + Math.floor(Math.random() * 3)} hari`;
        document.getElementById('shippingCostDisplay').textContent = formatRupiah(totalCost);

        shippingResult.classList.remove('hidden');
        submitBtn.disabled = false;
        updateGrandTotal();
    }, 800);
}

    // Load Provinsi
    fetch('{{ route('pembeli.rajaongkir.provinces') }}')
        .then(r => r.json())
        .then(provinces => {
            provinces.forEach(p => {
                provinceSelect.appendChild(new Option(p.province || p.name, p.province_id || p.id));
            });
        })
        .catch(() => alert('Gagal memuat provinsi.'));

    // Province -> City
    provinceSelect.addEventListener('change', function () {
        const id = this.value;
        provinceNameInput.value = this.options[this.selectedIndex]?.text || '';
        citySelect.innerHTML = '<option>Memuat...</option>';
        citySelect.disabled = true;
        fetch(`{{ route('pembeli.rajaongkir.cities') }}?province_id=${id}`)
            .then(r => r.json())
            .then(cities => {
                citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                cities.forEach(c => {
                    const opt = new Option(c.city_name || c.name, c.city_id || c.id);
                    opt.dataset.type = c.type;
                    citySelect.appendChild(opt);
                });
                citySelect.disabled = false;
            });
    });

    citySelect.addEventListener('change', () => calculateShipping());
    courierSelect.addEventListener('change', () => calculateShipping());

    document.getElementById('checkoutForm').addEventListener('submit', e => {
        const shippingCost = parseInt(shippingCostInput.value);
        if (!shippingCost || shippingCost <= 0) {
            e.preventDefault();
            alert('Ongkos kirim belum dihitung.');
        }
    });
});
</script>
@endsection
