{{-- resources/views/pembeli/pesanan/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Pesanan')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Pesanan</h1>

        {{-- Alert Error --}}
        @if(session('error'))
            <div
                class="bg-red-50 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 p-4 rounded-lg flex items-center gap-2">
                <span class="material-symbols-outlined">error</span>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        {{-- Alert Success --}}
        @if(session('success'))
            <div
                class="bg-green-50 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 p-4 rounded-lg flex items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('pembeli.pesanan.update', $order->id) }}" method="POST" id="editOrderForm">
            @csrf
            @method('PUT')

            {{-- HIDDEN INPUTS --}}
            <input type="hidden" name="address_id" id="selectedAddressId"
                value="{{ old('address_id', $order->address_id) }}">
            <input type="hidden" name="courier" id="hiddenCourier" value="{{ old('courier', $order->courier) }}">
            <input type="hidden" name="courier_service" id="hiddenCourierService"
                value="{{ old('courier_service', $order->courier_service) }}">
            <input type="hidden" name="shipping_cost" id="hiddenShippingCost"
                value="{{ old('shipping_cost', $order->shipping_cost) }}">

            <div class="grid md:grid-cols-3 gap-6">
                <!-- ==================== KIRI ==================== -->
                <div class="md:col-span-2 space-y-6">

                    <!-- 1. ALAMAT PENGIRIMAN -->
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
                            <div class="space-y-3">
                                @foreach($addresses as $addr)
                                    <label
                                        class="flex items-start p-4 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-zinc-700 transition
                                                                   {{ $order->address_id == $addr->id ? 'border-soft-green ring-2 ring-soft-green' : 'border-gray-300 dark:border-zinc-600' }}">
                                        <input type="radio" name="address_radio" value="{{ $addr->id }}"
                                            class="mt-1 text-soft-green focus:ring-soft-green" {{ $order->address_id == $addr->id ? 'checked' : '' }}>
                                        <div class="ml-3 flex-1">
                                            <div class="flex justify-between items-center">
                                                <span class="font-medium text-gray-900 dark:text-white">{{ $addr->label }}</span>
                                                @if($addr->is_default)
                                                    <span
                                                        class="bg-gradient-to-r from-soft-green to-primary text-white text-xs px-3 py-1 rounded-full font-bold flex items-center gap-1">
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

                    <!-- 2. KURIR -->
                    <div
                        class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
                        <h2 class="font-semibold text-lg mb-4 text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">local_shipping</span>
                            Kurir Pengiriman
                        </h2>

                        <select id="courierSelect"
                            class="w-full p-3 border border-gray-300 dark:border-zinc-600 rounded-lg 
                                           focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:bg-zinc-700 dark:text-white">
                            <option value="">Pilih Kurir</option>
                            <option value="jne" {{ old('courier', $order->courier) == 'jne' ? 'selected' : '' }}>JNE</option>
                            <option value="pos" {{ old('courier', $order->courier) == 'pos' ? 'selected' : '' }}>POS Indonesia
                            </option>
                            <option value="tiki" {{ old('courier', $order->courier) == 'tiki' ? 'selected' : '' }}>TIKI
                            </option>
                            <option value="jnt" {{ old('courier', $order->courier) == 'jnt' ? 'selected' : '' }}>J&T Express
                            </option>
                            <option value="sicepat" {{ old('courier', $order->courier) == 'sicepat' ? 'selected' : '' }}>
                                SiCepat</option>
                            <option value="anteraja" {{ old('courier', $order->courier) == 'anteraja' ? 'selected' : '' }}>
                                AnterAja</option>
                            <option value="ninja" {{ old('courier', $order->courier) == 'ninja' ? 'selected' : '' }}>Ninja
                                Express</option>
                            <option value="sap" {{ old('courier', $order->courier) == 'sap' ? 'selected' : '' }}>SAP Express
                            </option>
                            <option value="lion" {{ old('courier', $order->courier) == 'lion' ? 'selected' : '' }}>Lion Parcel
                            </option>
                        </select>

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

                        {{-- Daftar Layanan --}}
                        <div id="serviceList" class="hidden mt-4 space-y-2">
                            <p class="text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Pilih Layanan Pengiriman:
                            </p>
                        </div>

                        <div id="shippingResult"
                            class="hidden mt-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-green-800 dark:text-green-300" id="serviceName">JNE REG</p>
                                    <p class="text-xs text-green-600 dark:text-green-400" id="etd">Estimasi 1-2 hari</p>
                                </div>
                                <p class="text-xl font-bold text-green-600 dark:text-green-400" id="costDisplay">Rp 0</p>
                            </div>
                        </div>
                    </div>

                    <!-- 3. PRODUK DENGAN GAMBAR -->
                    <div
                        class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
                        <h2 class="font-semibold text-lg mb-4 text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">inventory_2</span>
                            Produk Dipesan ({{ $order->items->count() }})
                        </h2>
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                                <div
                                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-zinc-700 last:border-0">
                                    <div class="flex items-center gap-4 flex-1">
                                        <div
                                            class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 dark:bg-zinc-700 flex-shrink-0">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                                    class="w-full h-full object-cover" alt="{{ $item->product->name }}">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-gray-400 text-2xl">image</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ $item->product->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-zinc-400">
                                                {{ $item->quantity }} × Rp
                                                {{ number_format($item->product->price, 0, ',', '.') }}
                                                @if($item->product->weight)
                                                    ({{ $item->product->weight * $item->quantity }} gr)
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-end gap-2">
                                        <p class="font-semibold text-gray-800 dark:text-white">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </p>

                                        {{-- Tombol Hapus Produk --}}
                                        @if($order->items->count() > 1)
                                            <button type="button"
                                                onclick="confirmRemoveItem('{{ $item->product->name }}', '{{ route('pembeli.pesanan.removeItem', [$order->id, $item->id]) }}')"
                                                class="text-red-500 hover:text-red-700 flex items-center gap-1 text-xs transition">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                                Hapus
                                            </button>
                                        @else
                                            {{-- Jika sisa 1, beri tooltip/info bahwa hapus ini akan membatalkan pesanan --}}
                                            <button type="button"
                                                onclick="confirmRemoveItem('{{ $item->product->name }}', '{{ route('pembeli.pesanan.removeItem', [$order->id, $item->id]) }}', true)"
                                                class="text-red-500 hover:text-red-700 flex items-center gap-1 text-xs transition">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                                Hapus & Batalkan
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- ==================== KANAN: RINGKASAN ==================== -->
                <div>
                    <div
                        class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 sticky top-4">
                        <h2 class="font-semibold text-lg mb-4 text-gray-900 dark:text-white">Ringkasan Pesanan</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-zinc-400">Subtotal Produk</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-zinc-400">Ongkos Kirim</span>
                                <span id="summaryCost" class="font-medium text-gray-900 dark:text-white">
                                    Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="border-t border-gray-200 dark:border-zinc-700 pt-3 mt-3">
                                <div class="flex justify-between text-lg font-bold">
                                    <span class="text-gray-900 dark:text-white">Total Bayar</span>
                                    <span class="text-soft-green" id="grandTotal">
                                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" id="submitBtn"
                            class="w-full mt-6 bg-gradient-to-r from-soft-green to-primary text-white py-3 rounded-lg font-semibold hover:shadow-lg transition flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-lg">save</span>
                            Perbarui Pesanan
                        </button>

                        <a href="{{ route('pembeli.pesanan.index') }}"
                            class="block text-center mt-3 text-sm text-gray-600 dark:text-zinc-400 hover:text-soft-green">
                            ← Kembali ke Daftar Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </form>

        {{-- Form Hapus Produk (Pindah ke luar form utama agar tidak nested) --}}
        <form id="removeItemForm" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

    {{-- ==================== SCRIPT ==================== --}}
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

                const subtotal = {{ $order->subtotal }};
                const totalWeight = Math.max(1, {{ $order->items->sum(fn($i) => ($i->product?->weight ?: 1000) * $i->quantity) }});

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
                }

                function selectService(svc) {
                    hiddenCourier.value = svc.courier;
                    hiddenCourierService.value = svc.service;
                    hiddenShippingCost.value = svc.price;

                    const label = `${svc.courier_name} ${svc.service}` + (svc.description ? ` – ${svc.description}` : '');
                    serviceNameEl.textContent = label;
                    etdEl.textContent = svc.etd && svc.etd !== '-' ? `Estimasi ${svc.etd} hari` : '';
                    costDisplayEl.textContent = formatRupiah(svc.price);
                    summaryCostEl.textContent = formatRupiah(svc.price);
                    grandTotalEl.textContent = formatRupiah(subtotal + svc.price);

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
                                    ${svc.etd && svc.etd !== '-' ? `<p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">Estimasi ${svc.etd} hari</p>` : ''}
                                </div>
                                <span class="font-bold text-soft-green whitespace-nowrap">${formatRupiah(svc.price)}</span>
                            `;

                        btn.addEventListener('click', () => {
                            document.querySelectorAll('.service-btn').forEach(b => {
                                b.classList.remove('border-soft-green', 'ring-2', 'ring-soft-green', 'bg-green-50');
                            });
                            btn.classList.add('border-soft-green', 'ring-2', 'ring-soft-green', 'bg-green-50');
                            selectService(svc);
                        });

                        serviceListDiv.appendChild(btn);
                    });

                    serviceListDiv.classList.remove('hidden');
                }

                function fetchShippingCost() {
                    // Find which radio is checked
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

                // Bind event listeners
                document.querySelectorAll('input[name="address_radio"]').forEach(r => {
                    r.addEventListener('change', () => {
                        document.querySelectorAll('label').forEach(label => {
                            label.classList.remove('border-soft-green', 'ring-2', 'ring-soft-green');
                        });
                        r.closest('label').classList.add('border-soft-green', 'ring-2', 'ring-soft-green');

                        hiddenAddressId.value = r.value;
                        fetchShippingCost();
                    });
                });

                courierSelect.addEventListener('change', fetchShippingCost);

                // Initial trigger if previously selected
                const prevCourier = hiddenCourier.value;
                if (prevCourier) {
                    courierSelect.value = prevCourier;
                    fetchShippingCost();
                }
            });

            function confirmRemoveItem(productName, url, isLast = false) {
                const message = isLast
                    ? `Hapus "${productName}"? Ini adalah produk terakhir, pesanan akan DIBATALKAN otomatis.`
                    : `Apakah Anda yakin ingin menghapus "${productName}" dari pesanan?`;

                if (confirm(message)) {
                    const form = document.getElementById('removeItemForm');
                    form.action = url;
                    form.submit();
                }
            }
        </script>
    @endpush
@endsection