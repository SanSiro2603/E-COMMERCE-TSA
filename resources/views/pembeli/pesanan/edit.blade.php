{{-- resources/views/pembeli/pesanan/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Pesanan')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Pesanan</h1>

    {{-- Alert Error --}}
    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 p-4 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined">error</span>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 p-4 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <form action="{{ route('pembeli.pesanan.update', $order->id) }}" method="POST" id="editOrderForm">
        @csrf
        @method('PUT')

        <div class="grid md:grid-cols-3 gap-6">
            <!-- ==================== KIRI ==================== -->
            <div class="md:col-span-2 space-y-6">

                <!-- 1. ALAMAT PENGIRIMAN -->
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
                                           {{ $order->address_id == $addr->id ? 'border-soft-green ring-2 ring-soft-green' : 'border-gray-300 dark:border-zinc-600' }}">
                                    <input type="radio" name="address_id" value="{{ $addr->id }}"
                                           class="mt-1 text-soft-green focus:ring-soft-green"
                                           {{ $order->address_id == $addr->id ? 'checked' : '' }}
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

                <!-- 2. KURIR -->
                <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
                    <h2 class="font-semibold text-lg mb-4 text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">local_shipping</span>
                        Kurir Pengiriman
                    </h2>

                    <select name="courier" id="courier" required
                            class="w-full p-3 border border-gray-300 dark:border-zinc-600 rounded-lg 
                                   focus:ring-2 focus:ring-soft-green focus:border-soft-green dark:bg-zinc-700 dark:text-white">
                        <option value="">Pilih Kurir</option>
                        <option value="jne" {{ $order->courier == 'jne' ? 'selected' : '' }}>JNE</option>
                        <option value="pos" {{ $order->courier == 'pos' ? 'selected' : '' }}>POS Indonesia</option>
                        <option value="tiki" {{ $order->courier == 'tiki' ? 'selected' : '' }}>TIKI</option>
                        <option value="jnt" {{ $order->courier == 'jnt' ? 'selected' : '' }}>J&T Express</option>
                        <option value="sicepat" {{ $order->courier == 'sicepat' ? 'selected' : '' }}>SiCepat</option>
                        <option value="anteraja" {{ $order->courier == 'anteraja' ? 'selected' : '' }}>AnterAja</option>
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

                <!-- 3. PRODUK DENGAN GAMBAR -->
                <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
                    <h2 class="font-semibold text-lg mb-4 text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">inventory_2</span>
                        Produk Dipesan ({{ $order->items->count() }})
                    </h2>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-zinc-700 last:border-0">
                                <div class="flex items-center gap-4 flex-1">
                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 dark:bg-zinc-700 flex-shrink-0">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover" alt="{{ $item->product->name }}">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <span class="material-symbols-outlined text-gray-400 text-2xl">image</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-white">{{ $item->product->name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-zinc-400">
                                            {{ $item->quantity }} × Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                            @if($item->product->weight)
                                                ({{ $item->product->weight * $item->quantity }} gr)
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <p class="font-semibold text-gray-800 dark:text-white">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- ==================== KANAN: RINGKASAN ==================== -->
            <div>
                <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 sticky top-4">
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
</div>

{{-- ==================== SCRIPT ==================== --}}
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
    const subtotal = {{ $order->subtotal }};
    const totalWeight = {{ $order->items->sum(fn($i) => $i->product->weight * $i->quantity) }};
    const addresses = @json($addresses->keyBy('id'));
    const ongkirMap = {
        '31': 15000, '1': 40000, '2': 40000, '3': 40000, '4': 40000, '5': 40000,
        '32': 30000, '33': 35000, '34': 40000, '35': 35000, '36': 40000,
        '61': 70000, '62': 75000, '63': 75000, '64': 80000,
        '71': 70000, '72': 75000, '73': 75000, '74': 80000,
        '51': 60000, '52': 90000, '53': 95000, '81': 120000, '91': 130000
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
            etd.textContent = 'Estimasi 2-3 hari';
            costDisplay.textContent = formatRupiah(totalCost);
            summaryCost.textContent = formatRupiah(totalCost);
            grandTotal.textContent = formatRupiah(subtotal + totalCost);

            loading.classList.add('hidden');
            result.classList.remove('hidden');
            submitBtn.disabled = false;
        }, 600);
    }

    document.querySelectorAll('input[name="address_id"]').forEach(radio => {
        radio.addEventListener('change', calculateShipping);
    });
    courier.addEventListener('change', calculateShipping);

    // Auto trigger jika kurir sudah dipilih
    if (courier.value) calculateShipping();
});

function selectAddress(radio, id) {
    document.getElementById('selectedAddressId').value = id;
}
</script>
@endpush
@endsection
