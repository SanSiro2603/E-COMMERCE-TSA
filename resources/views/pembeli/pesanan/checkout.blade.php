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

        {{-- HIDDEN INPUTS (diisi via JS saat user pilih layanan) --}}
        <input type="hidden" name="address_id"      id="selectedAddressId"    value="{{ old('address_id') }}">
        <input type="hidden" name="courier"          id="hiddenCourier"        value="{{ old('courier') }}">
        <input type="hidden" name="courier_service"  id="hiddenCourierService" value="{{ old('courier_service') }}">
        <input type="hidden" name="shipping_cost"    id="hiddenShippingCost"   value="{{ old('shipping_cost', 0) }}">

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
                                    <input type="radio" name="address_radio" value="{{ $addr->id }}"
                                           class="mt-1 text-soft-green focus:ring-soft-green address-radio"
                                           {{ $addr->is_default || old('address_id') == $addr->id ? 'checked' : '' }}>
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

                {{-- 2. PILIH KURIR & LAYANAN --}}
                <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
                    <h2 class="font-semibold text-lg mb-4 text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">local_shipping</span>
                        Pilih Kurir
                    </h2>

                    <select id="courierSelect"
                            class="w-full p-3 border border-gray-300 dark:border-zinc-600 rounded-lg
                                   focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:bg-zinc-700 dark:text-white">
                        <option value="">Pilih Kurir</option>
                        <option value="jne">JNE</option>
                        <option value="pos">POS Indonesia</option>
                        <option value="tiki">TIKI</option>
                        <option value="jnt">J&T Express</option>
                        <option value="sicepat">SiCepat</option>
                        <option value="anteraja">AnterAja</option>
                        <option value="ninja">Ninja Express</option>
                        <option value="sap">SAP Express</option>
                        <option value="lion">Lion Parcel</option>
                    </select>

                    {{-- Loading --}}
                    <div id="shippingLoading" class="hidden mt-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4 flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.3 0 0 5.3 0 12h4z"></path>
                        </svg>
                        <span class="text-blue-700 dark:text-blue-300 font-medium">Menghitung ongkir dari Biteship...</span>
                    </div>

                    {{-- Error --}}
                    <div id="shippingError" class="hidden mt-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-lg p-4 text-red-700 dark:text-red-300 text-sm"></div>

                    {{-- Daftar Layanan (pilih satu) --}}
                    <div id="serviceList" class="hidden mt-4 space-y-2">
                        <p class="text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Pilih Layanan Pengiriman:</p>
                    </div>

                    {{-- Detail Layanan Terpilih --}}
                    <div id="shippingResult" class="hidden mt-3 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-green-800 dark:text-green-300" id="serviceName"></p>
                                <p class="text-xs text-green-600 dark:text-green-400" id="etd"></p>
                            </div>
                            <p class="text-xl font-bold text-green-600 dark:text-green-400" id="costDisplay"></p>
                        </div>
                    </div>
                </div>

                {{-- 3. PRODUK DIPESAN --}}
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
                            <span id="summaryCost" class="font-medium text-gray-500 dark:text-zinc-400">Pilih alamat &amp; kurir</span>
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
                        <button type="submit" id="submitBtn"
                                class="w-full mt-6 bg-[#16a34a] hover:bg-[#15803d] text-white py-3 rounded-lg font-semibold hover:shadow-lg hover:shadow-green-500/30 transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                                disabled>
                            <span class="material-symbols-outlined text-lg">payment</span>
                            Buat Pesanan &amp; Bayar
                        </button>
                    @else
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
    const courierSelect        = document.getElementById('courierSelect');
    const submitBtn            = document.getElementById('submitBtn');
    const loading              = document.getElementById('shippingLoading');
    const errorBox             = document.getElementById('shippingError');
    const serviceListDiv       = document.getElementById('serviceList');
    const resultDiv            = document.getElementById('shippingResult');
    const serviceNameEl        = document.getElementById('serviceName');
    const etdEl                = document.getElementById('etd');
    const costDisplayEl        = document.getElementById('costDisplay');
    const summaryCostEl        = document.getElementById('summaryCost');
    const grandTotalEl         = document.getElementById('grandTotal');
    const hiddenCourier        = document.getElementById('hiddenCourier');
    const hiddenCourierService = document.getElementById('hiddenCourierService');
    const hiddenShippingCost   = document.getElementById('hiddenShippingCost');
    const hiddenAddressId      = document.getElementById('selectedAddressId');

    const subtotal    = {{ $subtotal }};
    const totalWeight = {{ $totalWeight > 0 ? $totalWeight : 1000 }};

    function formatRupiah(n) {
        return 'Rp ' + parseInt(n).toLocaleString('id-ID');
    }

    function resetShipping() {
        serviceListDiv.classList.add('hidden');
        serviceListDiv.innerHTML = '<p class="text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Pilih Layanan Pengiriman:</p>';
        resultDiv.classList.add('hidden');
        errorBox.classList.add('hidden');
        hiddenShippingCost.value   = 0;
        hiddenCourierService.value = '';
        hiddenCourier.value        = '';
        summaryCostEl.textContent  = 'Pilih alamat & kurir';
        grandTotalEl.textContent   = formatRupiah(subtotal);
        if (submitBtn) submitBtn.disabled = true;
    }

    function selectService(svc) {
        hiddenCourier.value        = svc.courier;
        hiddenCourierService.value = svc.service;
        hiddenShippingCost.value   = svc.price;

        const label = `${svc.courier_name} ${svc.service}` + (svc.description ? ` – ${svc.description}` : '');
        serviceNameEl.textContent = label;
        etdEl.textContent         = svc.etd ? `Estimasi ${svc.etd} hari` : '';
        costDisplayEl.textContent = formatRupiah(svc.price);
        summaryCostEl.textContent = formatRupiah(svc.price);
        grandTotalEl.textContent  = formatRupiah(subtotal + svc.price);

        resultDiv.classList.remove('hidden');
        if (submitBtn) submitBtn.disabled = false;
    }

    function renderServices(services) {
        serviceListDiv.innerHTML = '<p class="text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Pilih Layanan Pengiriman:</p>';

        services.forEach(svc => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = [
                'w-full text-left p-3 border rounded-lg transition flex justify-between items-start gap-2',
                'border-gray-200 dark:border-zinc-600',
                'hover:border-soft-green hover:bg-green-50 dark:hover:bg-green-900/20',
                'service-btn'
            ].join(' ');

            btn.innerHTML = `
                <div>
                    <span class="font-semibold text-gray-800 dark:text-white">${svc.courier_name} ${svc.service}</span>
                    ${svc.description ? `<span class="ml-1 text-xs text-gray-500 dark:text-zinc-400">${svc.description}</span>` : ''}
                    ${svc.etd ? `<p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Estimasi ${svc.etd} hari</p>` : ''}
                </div>
                <span class="font-bold text-soft-green whitespace-nowrap">${formatRupiah(svc.price)}</span>
            `;

            btn.addEventListener('click', () => {
                document.querySelectorAll('.service-btn').forEach(b => {
                    b.classList.remove('border-soft-green', 'ring-2', 'ring-soft-green', 'bg-green-50');
                });
                btn.classList.add('border-soft-green', 'ring-2', 'ring-soft-green');
                selectService(svc);
            });

            serviceListDiv.appendChild(btn);
        });

        serviceListDiv.classList.remove('hidden');
    }

    function fetchShippingCost() {
        const addressRadio = document.querySelector('input[name="address_radio"]:checked');
        const courier = courierSelect.value;

        resetShipping();
        if (!addressRadio || !courier) return;

        const addressId = addressRadio.value;
        hiddenAddressId.value = addressId;

        loading.classList.remove('hidden');

        fetch('{{ route("pembeli.pesanan.checkout.shipping_cost") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                address_id: addressId,
                courier: courier,
                weight: totalWeight,
            })
        })
        .then(res => res.json())
        .then(data => {
            loading.classList.add('hidden');
            if (!data.success) {
                errorBox.textContent = data.message || 'Gagal mendapatkan ongkir.';
                errorBox.classList.remove('hidden');
                return;
            }
            renderServices(data.services);
        })
        .catch(() => {
            loading.classList.add('hidden');
            errorBox.textContent = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
            errorBox.classList.remove('hidden');
        });
    }

    // Events
    document.querySelectorAll('input[name="address_radio"]').forEach(r => {
        r.addEventListener('change', () => {
            hiddenAddressId.value = r.value;
            fetchShippingCost();
        });
    });

    courierSelect.addEventListener('change', fetchShippingCost);

    // Init: set hidden address dari radio default
    const defaultAddr = document.querySelector('input[name="address_radio"]:checked');
    if (defaultAddr) {
        hiddenAddressId.value = defaultAddr.value;
    }
});
</script>
@endpush
@endsection
