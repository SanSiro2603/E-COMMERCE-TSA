{{-- resources/views/pembeli/pesanan/checkout.blade.php --}}
@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Checkout</h1>

    {{-- ERROR VALIDASI --}}
    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 p-4 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined">error</span>
            <div>
                <p class="font-semibold">Ada kesalahan:</p>
                <ul class="list-disc pl-5 text-sm mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('pembeli.pesanan.store') }}" method="POST" id="checkoutForm">
        @csrf

        {{-- HIDDEN INPUTS --}}
        <input type="hidden" name="address_id" id="selectedAddressId" value="{{ old('address_id') }}">

        <div class="grid md:grid-cols-3 gap-6">
            <!-- KIRI: ALAMAT & KURIR -->
            <div class="md:col-span-2 space-y-6">

                {{-- 1. ALAMAT --}}
                <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-semibold text-lg text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">location_on</span>
                            Alamat Pengiriman
                        </h2>
                        <a href="{{ route('pembeli.alamat.index') }}" class="text-sm text-soft-green hover:underline">
                            Kelola Alamat
                        </a>
                    </div>

                    @if($addresses->isEmpty())
                        <div class="text-center py-8">
                            <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-zinc-600 mb-3">location_off</span>
                            <p class="text-gray-600 dark:text-zinc-400">Belum ada alamat tersimpan.</p>
                            <a href="{{ route('pembeli.alamat.create') }}" class="mt-3 inline-flex items-center gap-2 text-soft-green hover:underline">
                                <span class="material-symbols-outlined text-lg">add</span>
                                Tambah Alamat
                            </a>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($addresses as $addr)
                                <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-zinc-700 transition
                                           {{ $addr->is_default ? 'border-soft-green ring-2 ring-soft-green' : 'border-gray-300 dark:border-zinc-600' }}
                                           {{ old('address_id') == $addr->id ? 'ring-2 ring-soft-green' : '' }}">
                                    <input type="radio" name="address_id" value="{{ $addr->id }}"
                                           class="mt-1 text-soft-green focus:ring-soft-green" required
                                           {{ $addr->is_default || old('address_id') == $addr->id ? 'checked' : '' }}
                                           onchange="selectAddress(this, {{ $addr->id }})">
                                    <div class="ml-3 flex-1">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $addr->label }}</span>
                                            @if($addr->is_default)
                                                <span class="bg-gradient-to-r from-soft-green to-primary text-white text-xs px-3 py-1 rounded-full font-bold flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-sm">star</span> Utama
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-700 dark:text-zinc-300 mt-1">
                                            <strong>{{ $addr->recipient_name }}</strong> ({{ $addr->recipient_phone }})
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-zinc-400 mt-1">
                                            {{ $addr->full_address }}<br>
                                            {{ $addr->city_type }} {{ $addr->city_name }}, {{ $addr->province_name }}
                                            @if($addr->postal_code) • {{ $addr->postal_code }} @endif
                                        </p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- 2. KURIR --}}
                <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
                    <h2 class="font-semibold text-lg mb-4 text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">local_shipping</span>
                        Pilih Kurir
                    </h2>

                    <select name="courier" id="courier" required
                            class="w-full p-3 border border-gray-300 dark:border-zinc-600 rounded-lg 
                                   focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:bg-zinc-700 dark:text-white">
                        <option value="">Pilih Kurir</option>
                        <option value="jne">JNE</option>
                        <option value="pos">POS Indonesia</option>
                        <option value="tiki">TIKI</option>
                        <option value="jnt">J&T Express</option>
                        <option value="sicepat">SiCepat</option>
                        <option value="anteraja">AnterAja</option>
                    </select>

                    <div id="shippingLoading" class="hidden mt-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4 flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.3 0 0 5.3 0 12h4z"></path>
                        </svg>
                        <span class="text-blue-700 dark:text-blue-300 font-medium">Menghitung ongkir...</span>
                    </div>

                    <div id="shippingResult" class="hidden mt-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-green-800 dark:text-green-300" id="serviceName">JNE REG</p>
                                <p class="text-xs text-green-600 dark:text-green-400" id="etd">Estimasi 1-2 hari</p>
                            </div>
                            <p class="text-xl font-bold text-green-600 dark:text-green-400" id="costDisplay">Rp 0</p>
                        </div>
                    </div>
                </div>

                {{-- 3. PRODUK DIPESAN (dengan gambar) --}}
                <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
                    <h2 class="font-semibold text-lg mb-4 text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">inventory_2</span>
                        Produk Dipesan ({{ $carts->count() }})
                    </h2>

                    <div class="space-y-4">
                        @foreach($carts as $cart)
                            <div class="flex gap-4 border-b border-gray-200 dark:border-zinc-700 pb-3 last:border-0">
                                <img src="{{ asset('storage/' . $cart->product->image) }}" 
                                     alt="{{ $cart->product->name }}"
                                     class="w-20 h-20 object-cover rounded-lg border border-gray-200 dark:border-zinc-700">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $cart->product->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-zinc-400">
                                        {{ $cart->quantity }} × Rp {{ number_format($cart->product->price, 0, ',', '.') }}
                                        @if($cart->product->weight)
                                            ({{ $cart->product->weight * $cart->quantity }} gr)
                                        @endif
                                    </p>
                                </div>
                                <p class="font-semibold text-gray-800 dark:text-white whitespace-nowrap">
                                    Rp {{ number_format($cart->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- KANAN: RINGKASAN --}}
            <div>
                <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 sticky top-4">
                    <h2 class="font-semibold text-lg mb-4 text-gray-900 dark:text-white">Ringkasan Pesanan</h2>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-zinc-400">Subtotal Produk</span>
                            <span class="font-medium text-gray-900 dark:text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-zinc-400">Ongkos Kirim</span>
                            <span id="summaryCost" class="font-medium text-gray-500 dark:text-zinc-400">Pilih alamat & kurir</span>
                        </div>
                        <div class="border-t border-gray-200 dark:border-zinc-700 pt-3 mt-3">
                            <div class="flex justify-between text-lg font-bold">
                                <span class="text-gray-900 dark:text-white">Total Bayar</span>
                                <span class="text-soft-green" id="grandTotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- LOGIKA TOMBOL TOKO BUKA/TUTUP --}}
                    @if($shoppingEnabled)
                        {{-- Jika Buka: Tampilkan Tombol Normal --}}
                        <button type="submit" id="submitBtn"
                                class="w-full mt-6 bg-gradient-to-r from-soft-green to-primary text-white py-3 rounded-lg font-semibold hover:shadow-lg transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                                disabled>
                            <span class="material-symbols-outlined text-lg">payment</span>
                            Buat Pesanan & Bayar
                        </button>
                    @else
                        {{-- Jika Tutup: Tampilkan Pesan Error & Disable Tombol --}}
                        <div class="mt-6 p-4 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-lg flex items-start gap-3">
                            <span class="material-symbols-outlined text-red-600 dark:text-red-400">store_off</span>
                            <div>
                                <p class="font-bold text-red-800 dark:text-red-300">Toko Sedang Tutup</p>
                                <p class="text-sm text-red-700 dark:text-red-400">
                                    Mohon maaf, fitur transaksi belanja sedang dinonaktifkan oleh Admin.
                                </p>
                            </div>
                        </div>
                        <button type="button" disabled
                                class="w-full mt-3 bg-gray-400 text-white py-3 rounded-lg font-semibold cursor-not-allowed flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-lg">block</span>
                            Checkout Nonaktif
                        </button>
                    @endif

                    <a href="{{ route('pembeli.keranjang.index') }}"
                       class="block text-center mt-3 text-sm text-gray-600 dark:text-zinc-400 hover:text-soft-green">
                        ← Kembali ke Keranjang
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const courier = document.getElementById('courier');
    const submitBtn = document.getElementById('submitBtn');
    const loading = document.getElementById('shippingLoading');
    const result = document.getElementById('shippingResult');
    const serviceName = document.getElementById('serviceName');
    const etd = document.getElementById('etd');
    const costDisplay = document.getElementById('costDisplay');
    const summaryCost = document.getElementById('summaryCost');
    const grandTotal = document.getElementById('grandTotal');
    const subtotal = {{ $subtotal }};
    const totalWeight = {{ $totalWeight }};
    const addresses = @json($addresses->keyBy('id'));

    const ongkirMap = {
        '31': 15000, '32': 30000, '33': 35000, '34': 40000,
        '35': 35000, '36': 40000, '61': 70000, '62': 75000,
        '63': 75000, '64': 80000, '71': 70000, '72': 75000,
        '73': 75000, '74': 80000, '51': 60000, '52': 90000,
        '53': 95000, '81': 120000, '91': 130000
    };

    function formatRupiah(n) { return 'Rp ' + parseInt(n).toLocaleString('id-ID'); }

    function calculateShipping() {
        const selectedId = document.querySelector('input[name="address_id"]:checked')?.value;
        if (!selectedId || !courier.value) return;
        const addr = addresses[selectedId];
        if (!addr) return;

        loading.classList.remove('hidden');
        result.classList.add('hidden');
        submitBtn.disabled = true;

        setTimeout(() => {
            const weightKg = Math.ceil(totalWeight / 1000);
            const baseCost = ongkirMap[addr.province_id] || 60000;
            const totalCost = baseCost + (weightKg > 1 ? (weightKg - 1) * 10000 : 0);

            serviceName.textContent = `${courier.value.toUpperCase()} REG`;
            etd.textContent = 'Estimasi 2–3 hari';
            costDisplay.textContent = formatRupiah(totalCost);
            summaryCost.textContent = formatRupiah(totalCost);
            grandTotal.textContent = formatRupiah(subtotal + totalCost);

            loading.classList.add('hidden');
            result.classList.remove('hidden');
            submitBtn.disabled = false;
        }, 600);
    }

    document.querySelectorAll('input[name="address_id"]').forEach(r => r.addEventListener('change', calculateShipping));
    courier.addEventListener('change', calculateShipping);
});
function selectAddress(radio, id) {
    document.getElementById('selectedAddressId').value = id;
}
</script>
@endpush
@endsection
