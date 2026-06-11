{{-- resources/views/pembeli/pesanan/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-4 sm:space-y-6 pb-24 sm:pb-6 relative">

    <!-- Alerts -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 p-4 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined text-xl">check_circle</span>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 p-4 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined text-xl">error</span>
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400 mb-2">
        <a href="{{ route('pembeli.dashboard') }}" class="hover:text-soft-green transition">Beranda</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <a href="{{ route('pembeli.pesanan.index') }}" class="hover:text-soft-green transition">Pesanan</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-gray-900 dark:text-white font-medium">#{{ substr($order->order_number, -6) }}</span>
    </nav>

    <!-- MAIN STATUS HEADER -->
    @php
        $bgGradient = match($order->status) {
            'pending' => 'from-amber-500 to-orange-600 shadow-orange-500/20',
            'paid' => 'from-blue-500 to-indigo-600 shadow-blue-500/20',
            'processing' => 'from-purple-500 to-fuchsia-600 shadow-purple-500/20',
            'shipped' => 'from-cyan-500 to-blue-600 shadow-cyan-500/20',
            'completed' => 'from-emerald-500 to-teal-600 shadow-emerald-500/20',
            'cancelled', 'failed' => 'from-red-500 to-rose-600 shadow-red-500/20',
            default => 'from-gray-500 to-slate-600 shadow-gray-500/20'
        };
    @endphp
    <div class="bg-gradient-to-r {{ $bgGradient }} rounded-xl p-5 sm:p-6 text-white shadow-md flex items-center justify-between">
        <div>
            <h1 class="text-lg sm:text-2xl font-bold flex items-center gap-2">
                @if($order->status == 'pending') <span class="material-symbols-outlined">pending_actions</span> Menunggu Pembayaran
                @elseif($order->status == 'paid') <span class="material-symbols-outlined">check_circle</span> Pembayaran Berhasil
                @elseif($order->status == 'processing') <span class="material-symbols-outlined">inventory</span> Pesanan Diproses
                @elseif($order->status == 'shipped') <span class="material-symbols-outlined">local_shipping</span> Sedang Dikirim
                @elseif($order->status == 'completed') <span class="material-symbols-outlined">task_alt</span> Pesanan Selesai
                @elseif($order->status == 'cancelled') <span class="material-symbols-outlined">cancel</span> Pesanan Dibatalkan
                @else {{ ucfirst($order->status) }} @endif
            </h1>
            <p class="text-white/80 text-xs sm:text-sm mt-1.5 font-medium tracking-wide">No. Pesanan: {{ $order->order_number }}</p>
        </div>
    </div>

    <!-- TRACKING & SHIPPING INFO (Priority Top) -->
    @if($order->tracking_number || $order->biteship_order_id)
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden" id="biteship-tracking-card">
            <div class="p-4 border-b border-gray-100 dark:border-zinc-800 flex justify-between items-center bg-gray-50/50 dark:bg-zinc-800/30">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-soft-green text-lg">local_shipping</span>
                    <h2 class="font-bold text-gray-900 dark:text-white text-sm sm:text-base">Informasi Pengiriman</h2>
                </div>
                @if($order->biteship_order_id)
                    <button onclick="refreshBiteshipTracking()" id="track-refresh-btn" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded text-[11px] sm:text-xs font-semibold hover:bg-gray-50 dark:hover:bg-zinc-700 transition shadow-sm text-gray-700 dark:text-zinc-300">
                        <span class="material-symbols-outlined text-sm">refresh</span> Refresh
                    </button>
                @endif
            </div>
            <div class="p-4 sm:p-5">
                <div class="flex justify-between items-center mb-4 pb-4 border-b border-dashed border-gray-200 dark:border-zinc-800">
                    <div>
                        <p class="text-[10px] sm:text-xs text-gray-500 dark:text-zinc-400 font-medium">Kurir Pengiriman</p>
                        <p class="font-bold text-gray-900 dark:text-white mt-0.5 text-sm sm:text-base">{{ strtoupper($order->courier) }} {{ $order->courier_service ? '('.strtoupper($order->courier_service).')' : '' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] sm:text-xs text-gray-500 dark:text-zinc-400 font-medium">No. Resi</p>
                        @if($order->tracking_number)
                            <p class="font-bold text-soft-green mt-0.5 tracking-wider text-sm sm:text-base">{{ $order->tracking_number }}</p>
                        @else
                            <p class="text-[11px] sm:text-xs italic text-gray-400 mt-0.5">Menunggu resi...</p>
                        @endif
                    </div>
                </div>

                @if($order->biteship_order_id)
                    <div id="pembeli-tracking-timeline">
                        <p class="text-sm text-gray-500 dark:text-zinc-400 flex items-center gap-2">
                            <span class="material-symbols-outlined animate-spin text-soft-green">refresh</span> Memuat riwayat pengiriman...
                        </p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- ALAMAT PENGIRIMAN -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-4 sm:p-5">
        <h2 class="font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2 text-sm sm:text-base">
            <span class="material-symbols-outlined text-soft-green text-lg">location_on</span> Alamat Pengiriman
        </h2>
        @if($order->display_shipping_full_address)
            <div class="ml-7 text-[13px] sm:text-sm text-gray-700 dark:text-zinc-300">
                <p class="font-bold text-gray-900 dark:text-white">
                    {{ $order->display_shipping_recipient_name ?? '-' }}
                    <span class="font-normal text-gray-500 ml-1">| {{ $order->display_shipping_recipient_phone ?? '-' }}</span>
                </p>
                <p class="mt-1 leading-relaxed text-gray-600 dark:text-zinc-400">
                    {{ $order->display_shipping_full_address }}<br>{{ $order->display_shipping_city_line }}
                </p>
            </div>
        @else
            <div class="ml-7 text-sm italic text-gray-500">Alamat tidak tersedia.</div>
        @endif
    </div>

    <!-- RINCIAN PRODUK -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
        <div class="p-4 border-b border-gray-100 dark:border-zinc-800 bg-gray-50/50 dark:bg-zinc-800/30">
            <h2 class="font-bold text-gray-900 dark:text-white flex items-center gap-2 text-sm sm:text-base">
                <span class="material-symbols-outlined text-soft-green text-lg">storefront</span> Rincian Produk
            </h2>
        </div>
        <div class="p-4 sm:p-5">
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex gap-3 sm:gap-4 border-b border-dashed border-gray-200 dark:border-zinc-700/60 pb-4 last:border-0 last:pb-0 items-start">
                        <div class="relative shrink-0">
                            @if($item->display_image)
                                <img src="{{ asset('storage/' . $item->display_image) }}" class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50 shadow-sm">
                            @else
                                <div class="w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-100 dark:bg-zinc-800/50 shadow-sm">
                                    <span class="material-symbols-outlined text-gray-400 text-2xl">image</span>
                                </div>
                            @endif
                            <span class="absolute -top-2 -right-2 bg-zinc-900 dark:bg-zinc-700 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white dark:border-zinc-800 shadow-sm leading-none flex items-center justify-center min-w-[20px] h-[20px]">
                                x{{ $item->quantity }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0 py-0.5 flex flex-col justify-between h-full">
                            <div>
                                <h4 class="font-bold text-sm text-gray-900 dark:text-white line-clamp-2 leading-snug">
                                    {{ $item->display_name }}
                                </h4>
                                @if($item->product)
                                    <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-gray-100 dark:bg-zinc-800 text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">
                                            {{ $item->product->category->name ?? 'Uncategorized' }}
                                        </span>
                                        @if($item->product->weight)
                                            <span class="text-[10px] text-gray-400 dark:text-zinc-500 font-medium">
                                                • {{ $item->product->weight * $item->quantity }} gr
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="flex justify-between items-center mt-2.5">
                                <p class="text-[10px] sm:text-xs text-gray-500 dark:text-zinc-400 font-medium">
                                    Harga: Rp {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                                <p class="text-sm font-extrabold text-soft-green">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- RINCIAN PEMBAYARAN -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-4 sm:p-5 mb-24 sm:mb-0">
        <h2 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2 text-sm sm:text-base">
            <span class="material-symbols-outlined text-soft-green text-lg">receipt_long</span> Rincian Pembayaran
        </h2>
        <div class="space-y-3 text-[13px] sm:text-sm">
            <div class="flex justify-between text-gray-600 dark:text-zinc-400">
                <span>Subtotal Produk</span>
                <span class="text-gray-900 dark:text-white font-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-gray-600 dark:text-zinc-400">
                <span>Ongkos Kirim</span>
                <span class="text-gray-900 dark:text-white font-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            <div class="pt-3 border-t border-dashed border-gray-200 dark:border-zinc-800 flex justify-between items-center">
                <span class="font-bold text-gray-900 dark:text-white">Total Pesanan</span>
                <span class="text-lg sm:text-xl font-extrabold text-soft-green">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- METODE PEMBAYARAN --}}
        @php
            $rawMethod = $order->payment?->payment_type ?? $order->payment_method ?? null;
            $methodConfig = match($rawMethod) {
                'bank_transfer'         => ['label' => 'Transfer Bank (VA)',              'icon' => 'account_balance',        'color' => 'blue'],
                'echannel'              => ['label' => 'Mandiri E-Channel',               'icon' => 'account_balance',        'color' => 'blue'],
                'gopay'                 => ['label' => 'GoPay',                           'icon' => 'account_balance_wallet', 'color' => 'green'],
                'qris'                  => ['label' => 'QRIS',                            'icon' => 'qr_code_2',              'color' => 'purple'],
                'shopeepay'             => ['label' => 'ShopeePay',                       'icon' => 'account_balance_wallet', 'color' => 'orange'],
                'cstore'                => ['label' => 'Minimarket (Alfamart/Indomaret)', 'icon' => 'store',                  'color' => 'yellow'],
                'credit_card'           => ['label' => 'Kartu Kredit',                    'icon' => 'credit_card',            'color' => 'indigo'],
                default                 => null,
            };
            $colorMap = [
                'blue'   => ['bg' => 'bg-blue-50 dark:bg-blue-500/10',   'border' => 'border-blue-200 dark:border-blue-500/20',   'icon' => 'text-blue-600 dark:text-blue-400',   'text' => 'text-blue-800 dark:text-blue-300'],
                'green'  => ['bg' => 'bg-green-50 dark:bg-green-500/10', 'border' => 'border-green-200 dark:border-green-500/20', 'icon' => 'text-green-600 dark:text-green-400', 'text' => 'text-green-800 dark:text-green-300'],
                'purple' => ['bg' => 'bg-purple-50 dark:bg-purple-500/10','border' => 'border-purple-200 dark:border-purple-500/20','icon' => 'text-purple-600 dark:text-purple-400','text' => 'text-purple-800 dark:text-purple-300'],
                'orange' => ['bg' => 'bg-orange-50 dark:bg-orange-500/10','border' => 'border-orange-200 dark:border-orange-500/20','icon' => 'text-orange-600 dark:text-orange-400','text' => 'text-orange-800 dark:text-orange-300'],
                'yellow' => ['bg' => 'bg-yellow-50 dark:bg-yellow-500/10','border' => 'border-yellow-200 dark:border-yellow-500/20','icon' => 'text-yellow-600 dark:text-yellow-400','text' => 'text-yellow-800 dark:text-yellow-300'],
                'indigo' => ['bg' => 'bg-indigo-50 dark:bg-indigo-500/10','border' => 'border-indigo-200 dark:border-indigo-500/20','icon' => 'text-indigo-600 dark:text-indigo-400','text' => 'text-indigo-800 dark:text-indigo-300'],
            ];
        @endphp

        @if($methodConfig)
        @php $c = $colorMap[$methodConfig['color']]; @endphp
        <div class="mt-5 pt-4 border-t border-gray-100 dark:border-zinc-800">
            <p class="text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider mb-2.5">Metode Pembayaran</p>
            <div class="flex items-center gap-3 p-3 rounded-lg {{ $c['bg'] }} border {{ $c['border'] }}">
                <span class="material-symbols-outlined text-xl {{ $c['icon'] }}">{{ $methodConfig['icon'] }}</span>
                <span class="text-sm font-semibold {{ $c['text'] }}">{{ $methodConfig['label'] }}</span>
            </div>
            @if($order->payment?->bank && $order->payment?->va_number)
                <div class="mt-2 px-3 py-2.5 bg-gray-50 dark:bg-zinc-800/50 rounded-lg text-xs text-gray-600 dark:text-zinc-300 flex items-center justify-between">
                    <span><span class="font-bold uppercase">{{ $order->payment->bank }}</span> VA:</span>
                    <span class="font-mono font-bold tracking-widest text-gray-900 dark:text-white">{{ $order->payment->va_number }}</span>
                </div>
            @elseif($order->payment?->payment_code)
                <div class="mt-2 px-3 py-2.5 bg-gray-50 dark:bg-zinc-800/50 rounded-lg text-xs text-gray-600 dark:text-zinc-300 flex items-center justify-between">
                    <span>Kode Bayar:</span>
                    <span class="font-mono font-bold tracking-widest text-gray-900 dark:text-white">{{ $order->payment->payment_code }}</span>
                </div>
            @endif
        </div>
        @elseif($order->status === 'pending')
        <div class="mt-5 pt-4 border-t border-gray-100 dark:border-zinc-800">
            <p class="text-[11px] font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider mb-2.5">Metode Pembayaran</p>
            <div class="flex items-center gap-2 p-3 rounded-lg bg-gray-50 dark:bg-zinc-800/50 border border-gray-200 dark:border-zinc-700">
                <span class="material-symbols-outlined text-xl text-gray-400">hourglass_empty</span>
                <span class="text-xs text-gray-500 dark:text-zinc-400 font-medium">Belum dipilih</span>
            </div>
        </div>
        @endif
        
        <div class="mt-5 pt-4 border-t border-gray-100 dark:border-zinc-800 text-[11px] sm:text-xs text-gray-500 dark:text-zinc-400 space-y-1.5">
            <div class="flex justify-between"><span>No. Pesanan</span> <span class="font-medium text-gray-800 dark:text-zinc-300">{{ $order->order_number }}</span></div>
            <div class="flex justify-between"><span>Waktu Pemesanan</span> <span class="font-medium text-gray-800 dark:text-zinc-300">{{ $order->created_at->format('d-m-Y H:i') }}</span></div>
            @if($order->paid_at)
            <div class="flex justify-between"><span>Waktu Pembayaran</span> <span class="font-medium text-gray-800 dark:text-zinc-300">{{ \Carbon\Carbon::parse($order->paid_at)->format('d-m-Y H:i') }}</span></div>
            @endif
        </div>
    </div>

</div>

<!-- STICKY BOTTOM BAR FOR ACTIONS (Mobile) & INLINE (Desktop) -->
<div class="fixed sm:relative bottom-0 left-0 right-0 sm:bottom-auto sm:left-auto sm:right-auto bg-white dark:bg-zinc-900 sm:bg-transparent sm:dark:bg-transparent border-t border-gray-200 dark:border-zinc-800 sm:border-0 p-3 sm:p-0 z-40 sm:z-auto mt-0 sm:mt-6 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] sm:shadow-none">
    <div class="max-w-4xl mx-auto flex flex-wrap gap-2 justify-end items-center">
        @if($order->canBeCancelled())
            <form action="{{ route('pembeli.pesanan.cancel', $order->id) }}" method="POST"
                  onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')" class="flex-1 sm:flex-none">
                @csrf @method('PATCH')
                <button type="submit" class="w-full sm:w-auto px-4 py-2 sm:py-2 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 font-bold rounded-lg text-xs sm:text-sm transition-colors text-center">
                    Batalkan Pesanan
                </button>
            </form>
        @endif

        @if($order->status === 'pending')
            <a href="{{ route('pembeli.pesanan.edit', $order->id) }}" class="flex-1 sm:flex-none px-4 py-2 sm:py-2 bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400 font-bold rounded-lg text-xs sm:text-sm transition-colors text-center">
                Edit Pesanan
            </a>
            @if($order->payment?->snap_token)
                <a href="{{ route('pembeli.payment.show', $order->id) }}" class="flex-1 sm:flex-none px-5 py-2 sm:py-2 bg-soft-green text-white font-bold rounded-lg text-xs sm:text-sm shadow-md transition-colors text-center">
                    Bayar Sekarang
                </a>
            @endif
        @endif

        @if($order->canBeCompleted())
            <form action="{{ route('pembeli.pesanan.complete', $order->id) }}" method="POST" class="flex-1 sm:flex-none">
                @csrf @method('PATCH')
                <button type="submit" class="w-full sm:w-auto px-5 py-2 sm:py-2 bg-soft-green text-white font-bold rounded-lg text-xs sm:text-sm shadow-md transition-colors flex justify-center items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">check_circle</span> Pesanan Selesai
                </button>
            </form>
        @endif
    </div>
</div>

@if($order->biteship_order_id)
<script>
document.addEventListener('DOMContentLoaded', () => { loadPembeliTracking(); });

function loadPembeliTracking() {
    const url = '{{ route("pembeli.pesanan.biteship.track", $order) }}';
    fetch(url)
        .then(r => r.json())
        .then(data => renderPembeliTracking(data))
        .catch(() => {
            const el = document.getElementById('pembeli-tracking-timeline');
            if (el) el.innerHTML = '<p class="text-sm text-red-500">Gagal memuat data tracking.</p>';
        });
}

function refreshBiteshipTracking() {
    const btn = document.getElementById('track-refresh-btn');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined text-sm">hourglass_empty</span> Memuat...';
    }
    loadPembeliTracking();
    setTimeout(() => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-sm">refresh</span> Refresh';
        }
    }, 2500);
}

function renderPembeliTracking(data) {
    const el = document.getElementById('pembeli-tracking-timeline');
    if (!el) return;

    if (!data.success) {
        el.innerHTML = '<p class="text-sm text-red-500 dark:text-red-400">Gagal memuat tracking: ' + (data.message ?? '-') + '</p>';
        return;
    }

    const statusMap = {
        'confirmed'        : 'Pesanan Dikonfirmasi',
        'allocated'        : 'Kurir Dialokasikan',
        'picking_up'       : 'Kurir Menuju Pengirim',
        'picked'           : 'Paket Diambil Kurir',
        'dropping_off'     : 'Dalam Perjalanan',
        'return_in_transit': 'Paket Dikembalikan',
        'delivered'        : 'Paket Terkirim',
        'rejected'         : 'Ditolak Penerima',
        'cancelled'        : 'Dibatalkan',
        'on_hold'          : 'Ditahan',
    };

    const history = data.history ?? [];
    const courierStatus = data.courier_status ?? data.status ?? '-';

    let html = '<div class="flex items-center gap-2 mb-3"><span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">' +
        (statusMap[courierStatus] ?? courierStatus) + '</span></div>';

    if (history.length > 0) {
        html += '<div class="space-y-3 border-l-2 border-emerald-200 dark:border-emerald-700 pl-4 ml-1">';
        history.slice().reverse().forEach((h, i) => {
            const isFirst = i === 0;
            const dotClass = isFirst ? 'bg-emerald-500' : 'bg-gray-300 dark:bg-zinc-600';
            const timeStr = h.created_at ? '<p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">' + new Date(h.created_at).toLocaleString('id-ID') + '</p>' : '';
            html += '<div class="relative"><div class="absolute -left-[21px] w-3 h-3 rounded-full border-2 border-white dark:border-zinc-900 ' + dotClass + '"></div>' +
                '<p class="text-sm ' + (isFirst ? 'font-semibold text-gray-900 dark:text-white' : 'text-gray-700 dark:text-zinc-300') + '">' + translateBiteshipNote(h.description ?? h.status) + '</p>' +
                timeStr + '</div>';
        });
        html += '</div>';
    } else {
        html += '<p class="text-sm text-gray-500 dark:text-zinc-400 mt-2">Riwayat pengiriman belum tersedia. Coba refresh beberapa saat lagi.</p>';
    }

    el.innerHTML = html;
}

function translateBiteshipNote(text) {
    if (!text) return '-';
    let t = text;
    t = t.replace(/Courier order is confirmed/gi, 'Pesanan kurir telah dikonfirmasi');
    t = t.replace(/has been notified to pick up/gi, 'telah diinformasikan untuk penjemputan');
    t = t.replace(/Pickup Number/gi, 'Nomor Penjemputan');
    t = t.replace(/Shipment has been allocated to courier/gi, 'Pengiriman telah dialokasikan ke kurir');
    t = t.replace(/Courier is on the way to pick up the shipment/gi, 'Kurir dalam perjalanan untuk mengambil paket');
    t = t.replace(/Courier is on the way to pick up location/gi, 'Kurir dalam perjalanan menuju lokasi penjemputan');
    t = t.replace(/Item has been picked by courier/gi, 'Paket telah diambil oleh kurir');
    t = t.replace(/Courier is allocated and ready to pick up/gi, 'Kurir telah disiapkan dan siap menjemput paket');
    t = t.replace(/Courier is dropping off item to destination/gi, 'Kurir sedang dalam perjalanan mengirimkan paket ke tujuan');
    t = t.replace(/Shipment has been picked up/gi, 'Paket telah diambil oleh kurir');
    t = t.replace(/Shipment is being delivered/gi, 'Paket sedang dalam proses pengangkutan');
    t = t.replace(/Shipment has been dropped off by courier/gi, 'Paket telah diserahkan ke agen\/hub');
    t = t.replace(/Shipment has been delivered/gi, 'Paket telah berhasil terkirim');
    t = t.replace(/Shipment has been cancelled/gi, 'Pengiriman dibatalkan');
    t = t.replace(/Shipment is returned/gi, 'Paket dikembalikan ke pengirim');
    t = t.replace(/return_in_transit/gi, 'Dikembalikan di perjalanan');
    t = t.replace(/on_hold/gi, 'Ditahan (On Hold)');
    return t;
}
</script>
@endif

@endsection
