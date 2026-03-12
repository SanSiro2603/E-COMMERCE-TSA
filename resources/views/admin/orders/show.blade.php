{{-- resources/views/admin/orders/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">
                Pesanan #{{ $order->order_number }}
            </h1>
            <p class="text-sm text-gray-600 dark:text-zinc-400 mt-1">
                Dibuat pada {{ $order->created_at->format('d M Y, H:i') }}
            </p>
        </div>
        <a href="{{ route('admin.orders.index') }}" 
           class="inline-flex items-center gap-2 text-soft-green hover:text-primary text-sm font-medium transition-colors">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Kembali
        </a>
    </div>

    <!-- Success / Error Alerts -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-lg p-4 animate-fade-in">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">check_circle</span>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-green-900 dark:text-green-300">Berhasil!</h3>
                    <p class="text-sm text-green-800 dark:text-green-400 mt-1">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-lg p-4 animate-fade-in">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-2xl">error</span>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-red-900 dark:text-red-300">Gagal!</h3>
                    <p class="text-sm text-red-800 dark:text-red-400 mt-1">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-lg p-4 animate-fade-in">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-2xl">warning</span>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-red-900 dark:text-red-300">Validasi Gagal</h3>
                    <ul class="text-sm text-red-800 dark:text-red-400 mt-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>
    @endif

    <!-- Info & Summary Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pembeli & Alamat -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-soft-green">person</span>
                Informasi Pembeli
            </h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-zinc-400">Nama</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $order->user->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-zinc-400">Email</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $order->user->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-zinc-400">Penerima</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $order->recipient_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-zinc-400">No. HP</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $order->recipient_phone }}</span>
                </div>
                <div class="col-span-2">
                    <span class="text-gray-600 dark:text-zinc-400 block mb-1">Alamat Pengiriman</span>
                    <p class="font-medium text-gray-900 dark:text-white text-sm">
                        {{ $order->shipping_address }}<br>
                        {{ $order->city }}, {{ $order->province }} {{ $order->postal_code ? ' - ' . $order->postal_code : '' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Ringkasan Pesanan -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-soft-green">receipt_long</span>
                Ringkasan Pesanan
            </h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-zinc-400">Subtotal</span>
                    <span class="font-medium text-gray-900 dark:text-white">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-zinc-400">Ongkir ({{ $order->courier ?? 'JNE' }})</span>
                    <span class="font-medium text-gray-900 dark:text-white">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="border-t border-gray-200 dark:border-zinc-800 pt-3 flex justify-between font-bold text-base">
                    <span class="text-gray-900 dark:text-white">Total Bayar</span>
                    <span class="text-soft-green text-xl">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>

                <!-- Status Badge -->
                <div class="mt-4">
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-full
                        {{ $order->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400' : '' }}
                        {{ $order->status === 'paid' ? 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400' : '' }}
                        {{ $order->status === 'processing' ? 'bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400' : '' }}
                        {{ $order->status === 'shipped' ? 'bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400' : '' }}
                        {{ $order->status === 'completed' ? 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400' : '' }}
                        {{ in_array($order->status, ['cancelled', 'failed']) ? 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400' : '' }}">
                        <span class="w-1.5 h-1.5 rounded-full animate-ping {{ $order->status === 'paid' ? 'bg-blue-500' : 'bg-gray-500' }}"></span>
                        {{ $order->status_label ?? ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>

                   @if($order->paid_at)
    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-2" 
       id="paid-at-text"
       data-time="{{ $order->paid_at->setTimezone(config('app.timezone'))->toIso8601String() }}">
    </p>
@endif

                </div>
            </div>
        </div>
    </div>

    <!-- Produk yang Dipesan -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-soft-green">inventory_2</span>
            Produk yang Dipesan ({{ $order->items->count() }} item)
        </h3>
        <div class="space-y-4">
            @foreach($order->items as $item)
                <div class="flex gap-4 p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-zinc-700 rounded-lg overflow-hidden flex-shrink-0">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-gray-400 text-xl">image</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900 dark:text-white">{{ $item->product->name }}</h4>
                        <p class="text-xs text-gray-600 dark:text-zinc-400 mt-1">
                            {{ $item->product->category->name ?? 'Uncategorized' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600 dark:text-zinc-400">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        <p class="text-base font-bold text-gray-900 dark:text-white mt-1">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>



    {{-- ===== BITESHIP: BUAT PENGIRIMAN ===== --}}
    @if(in_array($order->status, ['paid', 'processing']) && !$order->biteship_order_id)
        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-500/10 dark:to-teal-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-emerald-900 dark:text-emerald-300 mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined">local_shipping</span>
                Kirim via Biteship
            </h3>
            <p class="text-sm text-emerald-700 dark:text-emerald-400 mb-4">
                Buat order pengiriman ke Biteship Sandbox. Sistem akan otomatis mengubah status pesanan menjadi <strong>Dikirim</strong> dan menyimpan nomor resi dari kurir.
            </p>
            <form action="{{ route('admin.orders.biteship.create', $order) }}" method="POST"
                  onsubmit="return confirm('Yakin ingin membuat order pengiriman Biteship untuk pesanan #{{ $order->order_number }}?')">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-medium rounded-lg shadow transition-all">
                    <span class="material-symbols-outlined text-lg">send</span>
                    Buat Pengiriman Biteship
                </button>
            </form>
        </div>
    @endif

    {{-- ===== BITESHIP: TRACKING INFO ===== --}}
    @if($order->biteship_order_id)
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-indigo-200 dark:border-indigo-500/30 shadow-sm p-6" id="biteship-tracking-card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-indigo-500">local_shipping</span>
                    Tracking Pengiriman Biteship
                </h3>
                <button onclick="refreshTracking()" id="refresh-btn"
                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs text-indigo-600 dark:text-indigo-400 border border-indigo-300 dark:border-indigo-500/40 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition">
                    <span class="material-symbols-outlined text-base">refresh</span>
                    Refresh
                </button>
            </div>

            {{-- Info Dasar --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm mb-4">
                <div class="bg-gray-50 dark:bg-zinc-800 rounded-lg p-3">
                    <p class="text-gray-500 dark:text-zinc-400 text-xs mb-1">ID Biteship</p>
                    <p class="font-mono text-xs text-gray-800 dark:text-zinc-200 break-all">{{ $order->biteship_order_id }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-zinc-800 rounded-lg p-3">
                    <p class="text-gray-500 dark:text-zinc-400 text-xs mb-1">No. Resi</p>
                    <p class="font-mono font-bold text-indigo-600 dark:text-indigo-400">{{ $order->tracking_number ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-zinc-800 rounded-lg p-3">
                    <p class="text-gray-500 dark:text-zinc-400 text-xs mb-1">Kurir</p>
                    <p class="font-bold text-gray-800 dark:text-zinc-200">{{ strtoupper($order->courier ?? '-') }} {{ $order->courier_service ? '(' . strtoupper($order->courier_service) . ')' : '' }}</p>
                </div>
            </div>

            {{-- Timeline Tracking --}}
            <div id="tracking-timeline" class="mt-4">
                <p class="text-sm text-gray-500 dark:text-zinc-400 flex items-center gap-2">
                    <span class="material-symbols-outlined text-base animate-spin">progress_activity</span>
                    Memuat data tracking...
                </p>
            </div>
        </div>
    @endif

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const el = document.getElementById('paid-at-text');
    if (el) {
        const iso = el.dataset.time;
        if (iso) {
            const date = new Date(iso);
            const formatted = date.toLocaleString('id-ID', {
                day: '2-digit', month: 'long', year: 'numeric',
                hour: '2-digit', minute: '2-digit', hour12: false
            }).replace(',', '');
            el.textContent = 'Dibayar pada ' + formatted;
        }
    }

    // AUTO REFRESH TIAP 15 DETIK UNTUK ADMIN
    setTimeout(() => { location.reload(); }, 15000);

    @if($order->biteship_order_id)
        loadTracking();
    @endif
});

function loadTracking() {
    const url = '{{ route("admin.orders.biteship.track", $order) }}';
    fetch(url)
        .then(r => r.json())
        .then(data => renderTracking(data))
        .catch(e => {
            document.getElementById('tracking-timeline').innerHTML =
                '<p class="text-sm text-red-500">Gagal memuat data tracking. Coba refresh.</p>';
        });
}

function refreshTracking() {
    const btn = document.getElementById('refresh-btn');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-base animate-spin">progress_activity</span> Memuat...';
    loadTracking();
    setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined text-base">refresh</span> Refresh';
    }, 2000);
}

function renderTracking(data) {
    const el = document.getElementById('tracking-timeline');
    if (!data.success) {
        el.innerHTML = `<p class="text-sm text-red-500 dark:text-red-400">⚠️ ${data.message ?? 'Gagal memuat tracking.'}</p>`;
        return;
    }

    const statusMap = {
        'confirmed': 'Pesanan Dikonfirmasi',
        'allocated': 'Kurir Dialokasikan',
        'picking_up': 'Kurir Menuju Pengirim',
        'picked': 'Paket Diambil Kurir',
        'dropping_off': 'Dalam Perjalanan',
        'return_in_transit': 'Paket Dikembalikan',
        'delivered': 'Paket Terkirim',
        'rejected': 'Ditolak Penerima',
        'cancelled': 'Dibatalkan',
        'on_hold': 'Ditahan',
    };

    const history = data.history ?? [];
    const courierStatus = data.courier_status ?? data.status ?? '-';

    let html = `
        <div class="flex items-center gap-2 mb-3">
            <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300">
                ${statusMap[courierStatus] ?? courierStatus}
            </span>
        </div>
    `;

    if (history.length > 0) {
        html += '<div class="space-y-3 mt-2">';
        history.slice().reverse().forEach((h, i) => {
            const isFirst = i === 0;
            html += `
                <div class="flex gap-3 items-start">
                    <div class="flex flex-col items-center">
                        <div class="w-3 h-3 rounded-full mt-1 ${isFirst ? 'bg-indigo-500' : 'bg-gray-300 dark:bg-zinc-600'}"></div>
                        ${i < history.length - 1 ? '<div class="w-0.5 h-full bg-gray-200 dark:bg-zinc-700 mt-1 min-h-4"></div>' : ''}
                    </div>
                    <div class="pb-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">${h.description ?? h.status}</p>
                        ${h.created_at ? `<p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">${new Date(h.created_at).toLocaleString('id-ID')}</p>` : ''}
                    </div>
                </div>
            `;
        });
        html += '</div>';
    } else {
        html += '<p class="text-sm text-gray-500 dark:text-zinc-400">Riwayat tracking belum tersedia dari kurir.</p>';
    }

    el.innerHTML = html;
}
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.3s ease-out; }
</style>
@endsection