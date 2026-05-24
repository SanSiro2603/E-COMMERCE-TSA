{{-- resources/views/pembeli/pesanan/checkout.blade.php --}}
@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Checkout</h1>

        {{-- ERROR VALIDASI --}}
        @if($errors->any())
            <div
                class="bg-red-50 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 p-4 rounded-lg flex items-center gap-2">
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
            <input type="hidden" name="address_id" id="selectedAddressId" value="{{ old('address_id') }}">
            <input type="hidden" name="courier" id="hiddenCourier" value="{{ old('courier') }}">
            <input type="hidden" name="courier_service" id="hiddenCourierService" value="{{ old('courier_service') }}">
            <input type="hidden" name="shipping_cost" id="hiddenShippingCost" value="{{ old('shipping_cost', 0) }}">

            <div class="grid md:grid-cols-3 gap-6">
                <!-- KIRI: ALAMAT & KURIR -->
                <div class="md:col-span-2 space-y-6">

                    {{-- 1. ALAMAT --}}
                    <div
                        class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
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
                                <span
                                    class="material-symbols-outlined text-6xl text-gray-300 dark:text-zinc-600 mb-3">location_off</span>
                                <p class="text-gray-600 dark:text-zinc-400">Belum ada alamat tersimpan.</p>
                                <a href="{{ route('pembeli.alamat.create') }}"
                                    class="mt-3 inline-flex items-center gap-2 text-soft-green hover:underline">
                                    <span class="material-symbols-outlined text-lg">add</span>
                                    Tambah Alamat
                                </a>
                            </div>
                        @else
                            <!-- Compact Address Selector (Shopee Style Dropdown) -->
                            <div class="space-y-3">
                                <div class="relative">
                                    <label for="addressSelect"
                                        class="block text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider mb-1.5">
                                        Pilih Alamat Pengiriman
                                    </label>
                                    <select id="addressSelect"
                                        class="w-full bg-gray-50 dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 text-gray-900 dark:text-white text-sm rounded-xl focus:ring-primary focus:border-primary block p-3 pr-10 cursor-pointer transition appearance-none">
                                        @foreach($addresses as $addr)
                                            <option value="{{ $addr->id }}" data-label="{{ $addr->label }}"
                                                data-recipient="{{ $addr->recipient_name }}"
                                                data-phone="{{ $addr->recipient_phone }}"
                                                data-details="{{ $addr->full_address }}, {{ $addr->city_type }} {{ $addr->city_name }}, {{ $addr->province_name }} {{ $addr->postal_code ? '• ' . $addr->postal_code : '' }}"
                                                {{ $addr->is_default || old('address_id') == $addr->id ? 'selected' : '' }}>
                                                [{{ $addr->label }}] {{ $addr->recipient_name }} – {{ $addr->city_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 pt-6 text-gray-500">
                                        <span class="material-symbols-outlined text-xl">unfold_more</span>
                                    </div>
                                </div>

                                <!-- Selected Address Details Display Box -->
                                <div
                                    class="p-3.5 bg-gray-50/50 dark:bg-zinc-900/50 border border-gray-150 dark:border-zinc-800 rounded-xl flex items-start gap-2.5">
                                    <span
                                        class="material-symbols-outlined text-primary text-xl mt-0.5 shrink-0">location_on</span>
                                    <div class="flex-1 min-w-0 text-xs sm:text-sm">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span
                                                class="font-bold text-[9px] uppercase px-1.5 py-0.5 rounded bg-primary/10 text-primary"
                                                id="card-addr-label">-</span>
                                            <span class="font-semibold text-gray-900 dark:text-white"
                                                id="card-addr-recipient">-</span>
                                            <span class="text-gray-500 dark:text-zinc-400" id="card-addr-phone">-</span>
                                        </div>
                                        <p class="text-gray-600 dark:text-zinc-400 mt-1 leading-relaxed" id="card-addr-details">
                                            -</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- 2. PILIH KURIR & LAYANAN --}}
                    <div
                        class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
                        <h2 class="font-semibold text-lg mb-4 text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">local_shipping</span>
                            Pilih Kurir
                        </h2>

                        <select id="courierSelect"
                            class="w-full p-3 border border-gray-300 dark:border-zinc-600 rounded-lg
                                       focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:bg-zinc-700 dark:text-white">
                            <option value="">Pilih Kurir</option>
                            <option value="jne">JNE</option>
                            <option value="tiki">TIKI</option>
                            <option value="jnt">J&T Express</option>
                            <option value="sicepat">SiCepat</option>
                            <option value="anteraja">AnterAja</option>
                            <option value="ninja">Ninja Express</option>
                            <option value="sap">SAP Express</option>
                            <option value="lion">Lion Parcel</option>
                        </select>

                        {{-- Loading --}}
                        <div id="shippingLoading"
                            class="hidden mt-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4 flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-blue-600 dark:text-blue-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.3 0 0 5.3 0 12h4z">
                                </path>
                            </svg>
                            <span class="text-blue-700 dark:text-blue-300 font-medium">Menghitung ongkir dari
                                Biteship...</span>
                        </div>

                        {{-- Error --}}
                        <div id="shippingError"
                            class="hidden mt-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-lg p-4 text-red-700 dark:text-red-300 text-sm">
                        </div>

                        {{-- Daftar Layanan (pilih satu) --}}
                        <div id="serviceList" class="hidden mt-4 space-y-2">
                            <p class="text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Pilih Layanan Pengiriman:
                            </p>
                        </div>

                        {{-- Detail Layanan Terpilih --}}
                        <div id="shippingResult"
                            class="hidden mt-3 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg p-4">
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
                    <div
                        class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
                        <h2 class="font-semibold text-lg mb-4 text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">inventory_2</span>
                            Produk Dipesan ({{ $carts->count() }})
                        </h2>

                        <div class="space-y-4">
                            @foreach($carts as $cart)
                                <div
                                    class="flex gap-3 sm:gap-4 border-b border-gray-150 dark:border-zinc-700/50 pb-3 last:border-0 items-start">
                                    <img src="{{ asset('storage/' . $cart->product->image) }}" alt="{{ $cart->product->name }}"
                                        class="w-16 h-16 object-cover rounded-lg border border-gray-200 dark:border-zinc-700 shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="font-medium text-xs sm:text-base text-gray-900 dark:text-white line-clamp-1 sm:line-clamp-2 leading-tight">
                                            {{ $cart->product->name }}</p>
                                        <p class="text-[10px] sm:text-xs text-gray-400 dark:text-zinc-500 mt-0.5 sm:mt-1">
                                            {{ $cart->product->category->name ?? 'Uncategorized' }}
                                            @if($cart->product->weight)
                                                • {{ $cart->product->weight * $cart->quantity }} gr
                                            @endif
                                        </p>
                                        <div class="flex justify-between items-center mt-1 sm:mt-2">
                                            <p class="text-xs sm:text-base font-bold text-primary">
                                                Rp {{ number_format($cart->product->price, 0, ',', '.') }}
                                            </p>
                                            <p class="text-xs sm:text-sm text-gray-500 dark:text-zinc-400">
                                                x{{ $cart->quantity }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- KANAN: RINGKASAN --}}
                <div>
                    <div
                        class="bg-white dark:bg-zinc-800 p-4 sm:p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 sticky top-4">
                        <h2 class="font-semibold text-lg mb-4 text-gray-900 dark:text-white">Ringkasan Pesanan</h2>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-zinc-400">Subtotal Produk</span>
                                <span class="font-medium text-gray-900 dark:text-white">Rp
                                    {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-zinc-400">Ongkos Kirim</span>
                                <span id="summaryCost" class="font-medium text-gray-500 dark:text-zinc-400">Pilih alamat
                                    &amp; kurir</span>
                            </div>
                            <div class="border-t border-gray-200 dark:border-zinc-700 pt-3 mt-3">
                                <div class="flex justify-between text-lg font-bold">
                                    <span class="text-gray-900 dark:text-white">Total Bayar</span>
                                    <span class="text-soft-green" id="grandTotal">Rp
                                        {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="hidden sm:block">
                            {{-- LOGIKA TOMBOL TOKO BUKA/TUTUP --}}
                            @if($shoppingEnabled)
                                <button type="submit" id="submitBtn"
                                    class="w-full mt-6 bg-[#16a34a] hover:bg-[#15803d] text-white py-3 rounded-lg font-semibold hover:shadow-lg hover:shadow-green-500/30 transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                                    disabled>
                                    <span class="material-symbols-outlined text-lg">payment</span>
                                    Buat Pesanan &amp; Bayar
                                </button>
                            @else
                                <div
                                    class="mt-6 p-4 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-lg flex items-start gap-3">
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
                                class="mt-4 flex items-center justify-center gap-2 w-full py-2.5 border border-gray-200 dark:border-zinc-700 hover:border-gray-300 dark:hover:border-zinc-600 text-xs font-bold text-gray-650 dark:text-zinc-300 rounded-xl transition-all duration-200 bg-gray-50/30 hover:bg-gray-50 dark:bg-transparent dark:hover:bg-zinc-800/50">
                                <span class="material-symbols-outlined text-sm">arrow_back</span>
                                Kembali ke Keranjang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Sticky Mobile Checkout Bar (Shopee Style) -->
        <div
            class="sm:hidden fixed bottom-0 left-0 right-0 z-40 bg-white dark:bg-zinc-900 border-t border-gray-200 dark:border-zinc-800 shadow-2xl px-4 py-3 flex items-center justify-between pb-safe">
            <div class="text-left">
                <p class="text-[10px] text-gray-500 dark:text-zinc-400">Total Pembayaran</p>
                <p class="text-base font-bold text-soft-green" id="mobileGrandTotal">Rp
                    {{ number_format($subtotal, 0, ',', '.') }}</p>
            </div>
            @if($shoppingEnabled)
                <button type="button" onclick="submitCheckoutForm()" id="submitBtnMobile"
                    class="px-6 py-2 bg-[#16a34a] text-white text-xs font-bold rounded-lg hover:bg-[#15803d] disabled:opacity-45 disabled:cursor-not-allowed"
                    disabled>
                    Buat Pesanan
                </button>
            @else
                <button type="button" disabled
                    class="px-6 py-2 bg-gray-400 text-white text-xs font-bold rounded-lg cursor-not-allowed">
                    Tutup
                </button>
            @endif
        </div>



    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const courierSelect = document.getElementById('courierSelect');
                const submitBtn = document.getElementById('submitBtn');
                const loading = document.getElementById('shippingLoading');
                const errorBox = document.getElementById('shippingError');
                const serviceListDiv = document.getElementById('serviceList');
                const resultDiv = document.getElementById('shippingResult');
                const serviceNameEl = document.getElementById('serviceName');
                const etdEl = document.getElementById('etd');
                const costDisplayEl = document.getElementById('costDisplay');
                const summaryCostEl = document.getElementById('summaryCost');
                const grandTotalEl = document.getElementById('grandTotal');
                const hiddenCourier = document.getElementById('hiddenCourier');
                const hiddenCourierService = document.getElementById('hiddenCourierService');
                const hiddenShippingCost = document.getElementById('hiddenShippingCost');
                const hiddenAddressId = document.getElementById('selectedAddressId');

                const subtotal = {{ $subtotal }};
                const totalWeight = {{ $totalWeight > 0 ? $totalWeight : 1000 }};

                function formatRupiah(n) {
                    return 'Rp ' + parseInt(n).toLocaleString('id-ID');
                }

                function resetShipping() {
                    serviceListDiv.classList.add('hidden');
                    serviceListDiv.innerHTML = '<p class="text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Pilih Layanan Pengiriman:</p>';
                    resultDiv.classList.add('hidden');
                    errorBox.classList.add('hidden');
                    hiddenShippingCost.value = 0;
                    hiddenCourierService.value = '';
                    hiddenCourier.value = '';
                    summaryCostEl.textContent = 'Pilih alamat & kurir';
                    grandTotalEl.textContent = formatRupiah(subtotal);
                    if (submitBtn) submitBtn.disabled = true;

                    // Mobile Sticky Bar sync
                    const mobileGrandTotalEl = document.getElementById('mobileGrandTotal');
                    const submitBtnMobile = document.getElementById('submitBtnMobile');
                    if (mobileGrandTotalEl) mobileGrandTotalEl.textContent = formatRupiah(subtotal);
                    if (submitBtnMobile) submitBtnMobile.disabled = true;
                }

                function selectService(svc) {
                    hiddenCourier.value = svc.courier;
                    hiddenCourierService.value = svc.service;
                    hiddenShippingCost.value = svc.price;

                    const label = `${svc.courier_name} ${svc.service}` + (svc.description ? ` – ${svc.description}` : '');
                    serviceNameEl.textContent = label;
                    etdEl.textContent = svc.etd ? `Estimasi ${svc.etd} hari` : '';
                    costDisplayEl.textContent = formatRupiah(svc.price);
                    summaryCostEl.textContent = formatRupiah(svc.price);
                    grandTotalEl.textContent = formatRupiah(subtotal + svc.price);

                    resultDiv.classList.remove('hidden');
                    if (submitBtn) submitBtn.disabled = false;

                    // Mobile Sticky Bar sync
                    const mobileGrandTotalEl = document.getElementById('mobileGrandTotal');
                    const submitBtnMobile = document.getElementById('submitBtnMobile');
                    if (mobileGrandTotalEl) mobileGrandTotalEl.textContent = formatRupiah(subtotal + svc.price);
                    if (submitBtnMobile) submitBtnMobile.disabled = false;
                }

                function renderServices(services) {
                    serviceListDiv.innerHTML = '<p class="text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Pilih Layanan Pengiriman:</p>';

                    services.forEach(svc => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = [
                            'w-full text-left p-3 border rounded-lg transition flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2',
                            'border-gray-200 dark:border-zinc-600',
                            'hover:border-soft-green hover:bg-green-50 dark:hover:bg-green-900/20',
                            'service-btn'
                        ].join(' ');

                        btn.innerHTML = `
                        <div class="flex-1 min-w-0">
                            <span class="font-semibold text-gray-800 dark:text-white">${svc.courier_name} ${svc.service}</span>
                            ${svc.description ? `<span class="block sm:inline sm:ml-1 text-xs text-gray-500 dark:text-zinc-400">${svc.description}</span>` : ''}
                            ${svc.etd ? `<p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Estimasi ${svc.etd} hari</p>` : ''}
                        </div>
                        <span class="font-bold text-soft-green text-sm sm:text-base whitespace-nowrap mt-1 sm:mt-0">${formatRupiah(svc.price)}</span>
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
                    const addressSelect = document.getElementById('addressSelect');
                    const courier = courierSelect.value;

                    resetShipping();
                    if (!addressSelect || !courier) return;

                    const addressId = addressSelect.value;
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
                const addressSelect = document.getElementById('addressSelect');
                if (addressSelect) {
                    // Function to update details card layout
                    function updateAddressDetailsCard() {
                        const selectedOption = addressSelect.options[addressSelect.selectedIndex];
                        if (selectedOption) {
                            document.getElementById('card-addr-label').textContent = selectedOption.dataset.label;
                            document.getElementById('card-addr-recipient').textContent = selectedOption.dataset.recipient;
                            document.getElementById('card-addr-phone').textContent = '(' + selectedOption.dataset.phone + ')';
                            document.getElementById('card-addr-details').textContent = selectedOption.dataset.details;
                        }
                    }

                    // On Change
                    addressSelect.addEventListener('change', () => {
                        hiddenAddressId.value = addressSelect.value;
                        updateAddressDetailsCard();
                        fetchShippingCost();
                    });

                    // Initialize display card
                    updateAddressDetailsCard();
                }

                courierSelect.addEventListener('change', fetchShippingCost);

                // Init: set hidden address dari select default
                if (addressSelect) {
                    hiddenAddressId.value = addressSelect.value;
                }

                // Submit function for mobile sticky button
                window.submitCheckoutForm = function () {
                    document.getElementById('checkoutForm').submit();
                }
            });
        </script>
    @endpush
@endsection