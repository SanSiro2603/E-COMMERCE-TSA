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

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-lg p-4 animate-fade-in" data-auto-dismiss>
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
        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-lg p-4 animate-fade-in" data-auto-dismiss>
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
        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-lg p-4 animate-fade-in" data-auto-dismiss>
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

    {{-- INFO CARDS: Pembeli & Ringkasan Pesanan --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Card: Info Pembeli & Alamat
             [+] Tambah baris <div class="flex justify-between"> baru di sini
                 jika perlu tampilkan field tambahan dari tabel orders/users/addresses --}}
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
                        {{ $order->city }}, {{ $order->province }}{{ $order->postal_code ? ' - ' . $order->postal_code : '' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Card: Ringkasan Harga & Status
             [+] Tambah baris harga baru (mis: diskon) sebelum baris "Total Bayar" --}}
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

                {{-- Badge Status
                     [+] Tambah kondisi {{ }} baru jika ada status baru --}}
                <div class="mt-4">
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-full
                        {{ $order->status === 'pending'    ? 'bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400' : '' }}
                        {{ $order->status === 'paid'       ? 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400' : '' }}
                        {{ $order->status === 'processing' ? 'bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400' : '' }}
                        {{ $order->status === 'shipped'    ? 'bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400' : '' }}
                        {{ $order->status === 'completed'  ? 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400' : '' }}
                        {{ in_array($order->status, ['cancelled', 'failed']) ? 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400' : '' }}">
                        <span class="w-1.5 h-1.5 rounded-full animate-ping
                            @switch($order->status)
                                @case('pending')    bg-yellow-500 @break
                                @case('paid')       bg-blue-500   @break
                                @case('processing') bg-purple-500 @break
                                @case('shipped')    bg-indigo-500 @break
                                @case('completed')  bg-green-500  @break
                                @default            bg-red-500
                            @endswitch"></span>
                        {{ $order->status_label }}
                    </span>

                    {{-- Tampilkan waktu pembayaran jika sudah dibayar
                         Format waktu dikonversi via JavaScript di bawah --}}
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

    {{-- TOMBOL PROSES PESANAN — hanya muncul jika status 'paid'
         Form dikirim lewat modal konfirmasi (#modal-processing)
         Handler: OrderController::updateStatus() --}}
    @if($order->status === 'paid')
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-purple-200 dark:border-purple-500/30 shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-purple-500">inventory</span>
                Proses Pesanan
            </h3>
            <p class="text-sm text-gray-600 dark:text-zinc-400 mb-4">
                Tandai pesanan ini sedang diproses / disiapkan sebelum dikirim. Status akan berubah menjadi <strong class="text-purple-600 dark:text-purple-400">Diproses</strong>.
            </p>
            <form id="form-processing" action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="processing">
            </form>
            <button type="button"
                    onclick="openModal('modal-processing')"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg shadow transition-all">
                <span class="material-symbols-outlined text-lg">inventory</span>
                Tandai Sedang Diproses
            </button>
        </div>
    @endif

    {{-- DAFTAR PRODUK YANG DIPESAN
         [+] Tambah field produk baru di dalam div.flex jika perlu tampilkan atribut lain
             (mis: berat, SKU) — data diambil dari $item->product --}}
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

    {{-- TOMBOL BUAT PENGIRIMAN BITESHIP
         Muncul jika status paid/processing dan belum ada biteship_order_id
         Handler: BiteshipController::createShipment() --}}
    @if(in_array($order->status, ['paid', 'processing']) && !$order->biteship_order_id)
        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-500/10 dark:to-teal-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-emerald-900 dark:text-emerald-300 mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined">local_shipping</span>
                Kirim via Biteship
            </h3>
            <p class="text-sm text-emerald-700 dark:text-emerald-400 mb-4">
                Buat order pengiriman ke Biteship Sandbox. Sistem akan otomatis mengubah status pesanan menjadi <strong>Dikirim</strong> dan menyimpan nomor resi dari kurir.
            </p>
            <form id="form-biteship" action="{{ route('admin.orders.biteship.create', $order) }}" method="POST">
                @csrf
            </form>
            <button type="button"
                    onclick="openModal('modal-biteship')"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-medium rounded-lg shadow transition-all">
                <span class="material-symbols-outlined text-lg">send</span>
                Buat Pengiriman Biteship
            </button>
        </div>
    @endif

    {{-- INFO TRACKING BITESHIP — muncul setelah pengiriman dibuat
         Data tracking dimuat via AJAX: loadTracking() → BiteshipController::trackShipment()
         [+] Untuk tambah kolom info (mis: estimasi tiba), tambahkan di dalam grid di bawah --}}
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

            {{-- Timeline tracking diisi oleh renderTracking() via JavaScript --}}
            <div id="tracking-timeline" class="mt-4">
                <p class="text-sm text-gray-500 dark:text-zinc-400 flex items-center gap-2">
                    <span class="material-symbols-outlined text-base animate-spin">progress_activity</span>
                    Memuat data tracking...
                </p>
            </div>
        </div>
    @endif

    {{-- MODAL: Konfirmasi ubah status → Diproses --}}
    @if($order->status === 'paid')
        <div id="modal-processing" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('modal-processing')"></div>
            <div class="relative bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-md p-6 animate-fade-in">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-2xl">inventory</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Konfirmasi Proses Pesanan</h3>
                        <p class="text-sm text-gray-500 dark:text-zinc-400">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
                <p class="text-sm text-gray-700 dark:text-zinc-300 mb-6">
                    Yakin ingin mengubah status pesanan <strong class="text-gray-900 dark:text-white">#{{ $order->order_number }}</strong> menjadi <strong class="text-purple-600 dark:text-purple-400">Diproses</strong>?
                </p>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeModal('modal-processing')"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-zinc-300 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="button" onclick="document.getElementById('form-processing').submit()"
                            class="px-4 py-2 text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 rounded-lg transition-colors inline-flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">check</span>
                        Ya, Tandai Diproses
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL: Konfirmasi buat pengiriman Biteship --}}
    @if(in_array($order->status, ['paid', 'processing']) && !$order->biteship_order_id)
        <div id="modal-biteship" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('modal-biteship')"></div>
            <div class="relative bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-md p-6 animate-fade-in">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-2xl">local_shipping</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Konfirmasi Pengiriman Biteship</h3>
                        <p class="text-sm text-gray-500 dark:text-zinc-400">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
                <p class="text-sm text-gray-700 dark:text-zinc-300 mb-6">
                    Yakin ingin membuat order pengiriman Biteship untuk pesanan <strong class="text-gray-900 dark:text-white">#{{ $order->order_number }}</strong>? Status akan otomatis berubah menjadi <strong class="text-indigo-600 dark:text-indigo-400">Dikirim</strong>.
                </p>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeModal('modal-biteship')"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-zinc-300 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="button" onclick="document.getElementById('form-biteship').submit()"
                            class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 rounded-lg transition-colors inline-flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">send</span>
                        Ya, Buat Pengiriman
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

<script>
// ===== MODAL HELPERS =====
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
}

// Tutup modal dengan tombol Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id^="modal-"]').forEach(m => {
            m.classList.add('hidden');
            m.classList.remove('flex');
        });
        document.body.style.overflow = '';
    }
});

document.addEventListener('DOMContentLoaded', () => {

    // Format waktu pembayaran dari ISO ke format lokal Indonesia
    const el = document.getElementById('paid-at-text');
    if (el) {
        const date = new Date(el.dataset.time);
        const formatted = date.toLocaleString('id-ID', {
            day: '2-digit', month: 'long', year: 'numeric',
            hour: '2-digit', minute: '2-digit', hour12: false
        }).replace(',', '');
        el.textContent = 'Dibayar pada ' + formatted;
    }

    // Auto-dismiss notifikasi setelah 4 detik
    document.querySelectorAll('[data-auto-dismiss]').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });

    @if($order->biteship_order_id)
        loadTracking();
    @endif
});

// ===== TRACKING BITESHIP =====

// Muat data tracking dari server (AJAX ke BiteshipController::trackShipment)
function loadTracking() {
    const url = '{{ route("admin.orders.biteship.track", $order) }}';
    fetch(url)
        .then(r => r.json())
        .then(data => renderTracking(data))
        .catch(() => {
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

// Render timeline tracking ke dalam #tracking-timeline
// [+] Ubah di sini jika perlu tampilkan field tambahan dari response Biteship
function renderTracking(data) {
    const el = document.getElementById('tracking-timeline');
    if (!data.success) {
        el.innerHTML = `<p class="text-sm text-red-500 dark:text-red-400">⚠️ ${data.message ?? 'Gagal memuat tracking.'}</p>`;
        return;
    }

    const statusMap = {
        'confirmed':         'Pesanan Dikonfirmasi',
        'allocated':         'Kurir Dialokasikan',
        'picking_up':        'Kurir Menuju Pengirim',
        'picked':            'Paket Diambil Kurir',
        'dropping_off':      'Dalam Perjalanan',
        'return_in_transit': 'Paket Dikembalikan',
        'delivered':         'Paket Terkirim',
        'rejected':          'Ditolak Penerima',
        'cancelled':         'Dibatalkan',
        'on_hold':           'Ditahan',
    };

    const history       = data.history ?? [];
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
                        <p class="text-sm font-medium text-gray-900 dark:text-white">${translateBiteshipNote(h.description ?? h.status)}</p>
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

// Terjemahkan keterangan status dari Biteship ke Bahasa Indonesia
// [+] Tambah pasangan terjemahan baru jika ada keterangan baru dari Biteship API
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

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.3s ease-out; }
</style>

@endsection