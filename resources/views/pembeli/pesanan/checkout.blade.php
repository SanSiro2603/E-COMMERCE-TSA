@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="max-w-6xl mx-auto p-4 space-y-6">
    <h1 class="text-2xl font-bold text-gray-800">Checkout</h1>

    {{-- ERROR VALIDASI --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg">
            <p class="font-semibold mb-2">Ada kesalahan:</p>
            <ul class="list-disc pl-5 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pembeli.pesanan.store') }}" method="POST" id="checkoutForm">
        @csrf

        {{-- Hidden Inputs untuk RajaOngkir --}}
        <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name') }}">
        <input type="hidden" name="city_name" id="city_name" value="{{ old('city_name') }}">
        <input type="hidden" name="city_type" id="city_type" value="{{ old('city_type') }}">

        <div class="grid md:grid-cols-3 gap-6">
            <!-- KIRI: FORM INPUT -->
            <div class="md:col-span-2 space-y-6">

                <!-- 1. PENERIMA -->
                <div class="bg-white p-6 rounded-lg shadow-sm border">
                    <h2 class="font-semibold text-lg mb-4 text-gray-700">Informasi Penerima</h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="recipient_name" required
                                   class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('recipient_name') border-red-500 @enderror"
                                   value="{{ old('recipient_name') }}" placeholder="Contoh: Budi Santoso">
                            @error('recipient_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                No. Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="recipient_phone" required
                                   class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('recipient_phone') border-red-500 @enderror"
                                   value="{{ old('recipient_phone') }}" placeholder="081234567890">
                            @error('recipient_phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 2. ALAMAT PENGIRIMAN -->
                <div class="bg-white p-6 rounded-lg shadow-sm border">
                    <h2 class="font-semibold text-lg mb-4 text-gray-700">Alamat Pengiriman</h2>
                    <div class="space-y-4">

                        <!-- Provinsi & Kota -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Provinsi <span class="text-red-500">*</span>
                                </label>
                                <select name="province_id" id="province" required
                                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-500 @error('province_id') border-red-500 @enderror">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                                @error('province_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Kota/Kabupaten <span class="text-red-500">*</span>
                                </label>
                                <select name="city_id" id="city" required disabled
                                        class="w-full p-3 border rounded-lg @error('city_id') border-red-500 @enderror">
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                                @error('city_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Kode Pos -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                            <input type="text" name="postal_code"
                                   class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-500"
                                   value="{{ old('postal_code') }}" placeholder="35142">
                        </div>

                        <!-- Alamat Lengkap -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea name="shipping_address" rows="3" required
                                      class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-500 @error('shipping_address') border-red-500 @enderror"
                                      placeholder="Jalan, RT/RW, Kelurahan, Kecamatan">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kurir -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kurir <span class="text-red-500">*</span>
                            </label>
                            <select name="courier" id="courier" required
                                    class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-500 @error('courier') border-red-500 @enderror">
                                <option value="">Pilih Kurir</option>
                                <option value="jne">JNE</option>
                                <option value="pos">POS Indonesia</option>
                                <option value="tiki">TIKI</option>
                                <option value="jnt">J&T Express</option>
                                <option value="sicepat">SiCepat</option>
                                <option value="anteraja">AnterAja</option>
                            </select>
                            @error('courier')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Loading & Hasil Ongkir -->
                        <div id="shippingLoading" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-center space-x-2">
                            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.3 0 0 5.3 0 12h4z"></path>
                            </svg>
                            <span class="text-blue-700 font-medium">Menghitung ongkir...</span>
                        </div>

                        <div id="shippingResult" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-green-800" id="serviceName">JNE REG</p>
                                    <p class="text-xs text-green-600" id="etd">Estimasi 1-2 hari</p>
                                </div>
                                <p class="text-xl font-bold text-green-600" id="costDisplay">Rp 0</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. PRODUK DIPESAN -->
                <div class="bg-white p-6 rounded-lg shadow-sm border">
                    <h2 class="font-semibold text-lg mb-4 text-gray-700">Produk Dipesan ({{ $carts->count() }})</h2>
                    <div class="space-y-3">
                        @foreach($carts as $cart)
                            <div class="flex justify-between items-start py-3 border-b last:border-0">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800">{{ $cart->product->name }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $cart->quantity }} × Rp {{ number_format($cart->product->price, 0, ',', '.') }}
                                        @if($cart->product->weight)
                                            ({{ $cart->product->weight * $cart->quantity }} gram)
                                        @endif
                                    </p>
                                </div>
                                <p class="font-semibold text-gray-800">
                                    Rp {{ number_format($cart->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- KANAN: RINGKASAN -->
            <div>
                <div class="bg-white p-6 rounded-lg shadow-sm border sticky top-4">
                    <h2 class="font-semibold text-lg mb-4 text-gray-700">Ringkasan Pesanan</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal Produk</span>
                            <span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Berat</span>
                            <span class="font-medium">{{ number_format($totalWeight / 1000, 2) }} kg</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ongkos Kirim</span>
                            <span id="summaryCost" class="font-medium text-gray-500">Pilih tujuan</span>
                        </div>
                        <div class="border-t pt-3 mt-3">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total Bayar</span>
                                <span class="text-green-600" id="grandTotal">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="submitBtn"
                            class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition disabled:bg-gray-400 disabled:cursor-not-allowed"
                            disabled>
                        Buat Pesanan & Lanjut Pembayaran
                    </button>

                    <a href="{{ route('pembeli.keranjang.index') }}"
                       class="block text-center mt-3 text-sm text-gray-600 hover:text-gray-800">
                        ← Kembali ke Keranjang
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const province = document.getElementById('province');
    const city = document.getElementById('city');
    const courier = document.getElementById('courier');
    const submitBtn = document.getElementById('submitBtn');
    const provinceName = document.getElementById('province_name');
    const cityName = document.getElementById('city_name');
    const cityType = document.getElementById('city_type');
    const loading = document.getElementById('shippingLoading');
    const result = document.getElementById('shippingResult');
    const serviceName = document.getElementById('serviceName');
    const etd = document.getElementById('etd');
    const costDisplay = document.getElementById('costDisplay');
    const summaryCost = document.getElementById('summaryCost');
    const grandTotal = document.getElementById('grandTotal');
    const subtotal = {{ $subtotal }};
    const totalWeight = {{ $totalWeight }};

    function formatRupiah(n) {
        return 'Rp ' + parseInt(n).toLocaleString('id-ID');
    }

    // ONGKIR BERDASARKAN PROVINCE_ID
    const ongkirMap = {
        '31': 15000, // Lampung
        '1': 40000, '2': 40000, '3': 40000, '4': 40000, '5': 40000, '6': 40000, // Jawa
        '32': 30000, '33': 35000, '34': 40000, '35': 35000, '36': 40000, '37': 35000, '38': 30000, '39': 30000, // Sumatera
        '61': 70000, '62': 75000, '63': 75000, '64': 80000, '65': 85000, // Kalimantan
        '71': 70000, '72': 75000, '73': 75000, '74': 80000, '75': 80000, '76': 75000, // Sulawesi
        '51': 60000, '52': 90000, '53': 95000, // Bali & NTT
        '81': 120000, '82': 125000, '91': 130000, '92': 130000 // Maluku & Papua
    };

    function calculateShipping() {
        if (!province.value || !city.value || !courier.value) return;

        loading.classList.remove('hidden');
        result.classList.add('hidden');
        submitBtn.disabled = true;

        setTimeout(() => {
            const weightKg = Math.ceil(totalWeight / 1000);
            const provinceId = province.value;
            const provinceText = province.options[province.selectedIndex].dataset.name || province.options[province.selectedIndex].text;

            const baseCost = ongkirMap[provinceId] || 60000;
            const totalCost = baseCost + (weightKg > 1 ? (weightKg - 1) * 10000 : 0);

            provinceName.value = provinceText;
            const cityOpt = city.options[city.selectedIndex];
            cityName.value = cityOpt.text;
            cityType.value = cityOpt.dataset.type || 'Kota';

            serviceName.textContent = `${courier.value.toUpperCase()} REG`;
            etd.textContent = weightKg <= 1 ? 'Estimasi 1-2 hari' : 'Estimasi 2-5 hari';
            costDisplay.textContent = formatRupiah(totalCost);
            summaryCost.textContent = formatRupiah(totalCost);
            grandTotal.textContent = formatRupiah(subtotal + totalCost);

            loading.classList.add('hidden');
            result.classList.remove('hidden');
            submitBtn.disabled = false;
        }, 500);
    }

    const trigger = () => {
        if (province.value && city.value && courier.value) calculateShipping();
    };

    province.addEventListener('change', trigger);
    city.addEventListener('change', trigger);
    courier.addEventListener('change', trigger);

    // Load Provinces
    fetch('{{ route("pembeli.rajaongkir.provinces") }}')
        .then(r => r.json())
        .then(data => {
            data.forEach(p => {
                const opt = new Option(p.name, p.province_id);
                opt.dataset.name = p.name;
                if ("{{ old('province_id') }}" === p.province_id) opt.selected = true;
                province.appendChild(opt);
            });
            if ("{{ old('province_id') }}") province.dispatchEvent(new Event('change'));
        });

    // Load Cities
    province.addEventListener('change', function () {
        const id = this.value;
        city.innerHTML = '<option>Memuat...</option>';
        city.disabled = true;

        fetch(`{{ route("pembeli.rajaongkir.cities") }}?province_id=${id}`)
            .then(r => r.json())
            .then(data => {
                city.innerHTML = '<option value="">Pilih Kota</option>';
                data.forEach(c => {
                    const opt = new Option(c.name, c.city_id);
                    opt.dataset.type = c.type || 'Kota';
                    if ("{{ old('city_id') }}" === c.city_id) opt.selected = true;
                    city.appendChild(opt);
                });
                city.disabled = false;
                if ("{{ old('city_id') }}") trigger();
            });
    });

    if ("{{ old('province_id') }}") {
        setTimeout(() => province.dispatchEvent(new Event('change')), 300);
    }
});
</script>
@endsection