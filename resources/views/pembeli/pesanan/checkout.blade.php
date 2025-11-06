@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="max-w-6xl mx-auto p-4 space-y-6">
    <h1 class="text-2xl font-bold">Checkout</h1>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded">{{ session('error') }}</div>
    @endif

    <form action="{{ route('pembeli.pesanan.store') }}" method="POST" id="checkoutForm">
        @csrf

        <!-- Hidden inputs -->
        <input type="hidden" name="province_name" id="province_name">
        <input type="hidden" name="city_name" id="city_name">
        <input type="hidden" name="city_type" id="city_type">

        <div class="grid md:grid-cols-3 gap-6">
            <!-- Left: Form -->
            <div class="md:col-span-2 space-y-6">
                <!-- Penerima -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="font-semibold mb-4">Informasi Penerima</h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="recipient_name" required class="w-full p-2 border rounded" value="{{ old('recipient_name') }}">
                            @error('recipient_name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block mb-1">No. Telepon <span class="text-red-500">*</span></label>
                            <input type="text" name="recipient_phone" required class="w-full p-2 border rounded" value="{{ old('recipient_phone') }}">
                            @error('recipient_phone') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="font-semibold mb-4">Alamat Pengiriman</h2>
                    <div class="space-y-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-1">Provinsi <span class="text-red-500">*</span></label>
                                <select name="province_id" id="province" required class="w-full p-2 border rounded">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                                @error('province_id') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block mb-1">Kota/Kabupaten <span class="text-red-500">*</span></label>
                                <select name="city_id" id="city" required class="w-full p-2 border rounded" disabled>
                                    <option value="">Pilih Kota</option>
                                </select>
                                @error('city_id') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block mb-1">Kode Pos</label>
                            <input type="text" name="postal_code" class="w-full p-2 border rounded" value="{{ old('postal_code') }}">
                        </div>

                        <div>
                            <label class="block mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="shipping_address" rows="3" required class="w-full p-2 border rounded">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block mb-1">Kurir</label>
                            <select name="courier" class="w-full p-2 border rounded">
                                <option value="JNE" {{ old('courier') == 'JNE' ? 'selected' : '' }}>JNE</option>
                                <option value="JNT" {{ old('courier') == 'JNT' ? 'selected' : '' }}>JNT</option>
                                <option value="SiCepat" {{ old('courier') == 'SiCepat' ? 'selected' : '' }}>SiCepat</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Produk -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="font-semibold mb-4">Produk ({{ $carts->count() }})</h2>
                    <div class="space-y-3">
                        @foreach($carts as $cart)
                            <div class="flex justify-between text-sm">
                                <div>
                                    <p class="font-medium">{{ $cart->product->name }}</p>
                                    <p class="text-gray-500">{{ $cart->quantity }} × Rp {{ number_format($cart->product->price, 0, ',', '.') }}</p>
                                </div>
                                <p>Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right: Ringkasan -->
            <div>
                <div class="bg-white p-6 rounded-lg shadow sticky top-4">
                    <h2 class="font-semibold mb-4">Ringkasan</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ongkir</span>
                            <span>Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t pt-2 font-bold text-lg">
                            <div class="flex justify-between">
                                <span>Total</span>
                                <span class="text-green-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="w-full mt-4 bg-green-600 text-white py-3 rounded-lg font-semibold">
                        Buat Pesanan
                    </button>
                    <a href="{{ route('pembeli.keranjang.index') }}" class="block text-center mt-2 text-sm text-gray-600">
                        Kembali ke Keranjang
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

    // Load Provinces
    fetch('{{ route('pembeli.rajaongkir.provinces') }}')
        .then(r => r.json())
        .then(provinces => {
            provinces.forEach(p => {
                const opt = new Option(p.province, p.province_id);
                provinceSelect.appendChild(opt);
            });

            @if(old('province_id'))
                provinceSelect.value = '{{ old('province_id') }}';
                provinceNameInput.value = '{{ old('province_name') }}';
                provinceSelect.dispatchEvent(new Event('change'));
            @endif
        });

    // Province Change
    provinceSelect.addEventListener('change', function () {
        const provinceId = this.value;
        const selectedText = this.options[this.selectedIndex].text;
        provinceNameInput.value = selectedText;

        citySelect.innerHTML = '<option value="">Memuat...</option>';
        citySelect.disabled = true;
        cityNameInput.value = '';
        cityTypeInput.value = '';

        if (!provinceId) {
            citySelect.innerHTML = '<option value="">Pilih Kota</option>';
            return;
        }

        fetch(`{{ route('pembeli.rajaongkir.cities') }}?province_id=${provinceId}`)
            .then(r => r.json())
            .then(cities => {
                citySelect.innerHTML = '<option value="">Pilih Kota</option>';
                cities.forEach(c => {
                    const label = `${c.type} ${c.city_name}`;
                    const opt = new Option(label, c.city_id);
                    citySelect.appendChild(opt);
                });
                citySelect.disabled = false;

                @if(old('city_id'))
                    setTimeout(() => {
                        citySelect.value = '{{ old('city_id') }}';
                        const sel = citySelect.options[citySelect.selectedIndex];
                        if (sel && sel.value) {
                            const [type, ...name] = sel.text.split(' ');
                            cityTypeInput.value = type;
                            cityNameInput.value = name.join(' ');
                        }
                    }, 100);
                @endif
            });
    });

    // City Change
    citySelect.addEventListener('change', function () {
        const sel = this.options[this.selectedIndex];
        if (sel && sel.value) {
            const [type, ...name] = sel.text.split(' ');
            cityTypeInput.value = type;
            cityNameInput.value = name.join(' ');
        }
    });

    @if(old('province_id'))
        provinceSelect.dispatchEvent(new Event('change'));
    @endif
});
</script>
@endsection