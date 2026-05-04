@extends('layouts.app')

@section('title', 'Pesanan Saya - E-Commerce TSA')

@section('content')
<div class="space-y-6">

    <!-- SUCCESS / ERROR ALERT -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-xl p-4 animate-slide-in" data-auto-dismiss>
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-9 h-9 bg-green-100 dark:bg-green-500/20 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-xl">check_circle</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-bold text-green-900 dark:text-green-300">Berhasil!</h3>
                    <p class="text-sm text-green-700 dark:text-green-400 mt-0.5">{{ session('success') }}</p>
                </div>
                <button onclick="this.closest('[data-auto-dismiss]').remove()"
                        class="flex-shrink-0 p-1 text-green-500 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-500/20 rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-xl p-4 animate-slide-in" data-auto-dismiss>
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-9 h-9 bg-red-100 dark:bg-red-500/20 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-xl">error</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-bold text-red-900 dark:text-red-300">Gagal!</h3>
                    <p class="text-sm text-red-700 dark:text-red-400 mt-0.5">{{ session('error') }}</p>
                </div>
                <button onclick="this.closest('[data-auto-dismiss]').remove()"
                        class="flex-shrink-0 p-1 text-red-500 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>
        </div>
    @endif

    <!-- Breadcrumb & Header -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400">
        <a href="{{ route('pembeli.dashboard') }}" class="hover:text-soft-green transition-colors">Beranda</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-gray-900 dark:text-white font-medium">Pesanan Saya</span>
    </nav>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">
                Riwayat Pesanan
            </h1>
            <p class="text-sm text-gray-600 dark:text-zinc-400 mt-1">
                Kelola dan pantau pesanan Anda
            </p>
        </div>
        <a href="{{ route('pembeli.produk.index') }}"
           class="flex items-center gap-2 px-4 py-2 bg-[#16a34a] hover:bg-[#15803d] text-white rounded-lg text-sm font-semibold hover:shadow-lg hover:shadow-green-500/30 transition-all duration-200">
            <span class="material-symbols-outlined text-lg">storefront</span>
            Belanja Lagi
        </a>
    </div>

    <!-- FILTER TABS -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-4">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('pembeli.pesanan.index') }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ !request('status') || request('status') == 'all' ? 'bg-gradient-to-r from-soft-green to-primary text-white shadow-md' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-700' }}">
                Semua
            </a>
            @foreach($statuses as $key => $label)
                @if($key !== 'all')
                    <a href="{{ route('pembeli.pesanan.index', ['status' => $key]) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request('status') == $key ? 'bg-gradient-to-r from-soft-green to-primary text-white shadow-md' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-700' }}">
                        {{ $label }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>

    <!-- ORDERS LIST -->
    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white dark:bg-zinc-900 rounded-xl border overflow-hidden hover:shadow-lg transition-all
                    {{ $highlightedOrder && $highlightedOrder->id === $order->id ? 'ring-4 ring-green-400 ring-opacity-70 shadow-2xl animate-pulse' : 'border-gray-200 dark:border-zinc-800' }}">
                    
                    <!-- SUCCESS PAYMENT BANNER -->
                    @if($highlightedOrder && $highlightedOrder->id === $order->id)
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-2 text-center font-bold text-sm animate-bounce">
                            PEMBAYARAN BERHASIL! Pesanan ini baru saja dibayar.
                        </div>
                    @endif

                    <!-- Header -->
                    <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-800/50">
                        <div class="flex flex-col sm:flex-row justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="material-symbols-outlined text-soft-green">receipt_long</span>
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                        #{{ $order->order_number }}
                                    </h3>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full
                                        {{ $order->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400' : '' }}
                                        {{ $order->status === 'paid' ? 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400' : '' }}
                                        {{ $order->status === 'processing' ? 'bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400' : '' }}
                                        {{ $order->status === 'shipped' ? 'bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400' : '' }}
                                        {{ $order->status === 'completed' ? 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400' : '' }}
                                        {{ in_array($order->status, ['cancelled', 'failed']) ? 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400' : '' }}">
                                        @if($order->status === 'paid' && $order->paid_at && $order->paid_at->diffInMinutes(now()) < 5)
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-ping"></span>
                                        @endif
                                        {{ $order->status_label }}
                                    </span>
                                    @if($order->tracking_number)
                                        <span class="inline-flex items-center gap-1 text-xs text-blue-600 dark:text-blue-400">
                                            <span class="material-symbols-outlined text-sm">local_shipping</span>
                                            {{ $order->tracking_number }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 dark:text-zinc-400">
                                    <span class="material-symbols-outlined text-sm align-middle">calendar_today</span>
                                    {{ $order->created_at->format('d M Y, H:i') }} • {{ $order->items->count() }} produk
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 dark:text-zinc-400 mb-1">Total Bayar</p>
                                <p class="text-xl font-bold text-soft-green">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Items Preview -->
                    <div class="p-4 sm:p-6">
                        <div class="space-y-3">
                            @foreach($order->items->take(3) as $item)
                                <div class="flex gap-3">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-zinc-800 rounded-lg overflow-hidden flex-shrink-0">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <span class="material-symbols-outlined text-gray-400 text-xl">image</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-1">
                                            {{ $item->product->name ?? 'Produk tidak tersedia' }}
                                        </h4>
                                        <p class="text-xs text-gray-600 dark:text-zinc-400 mt-1">
                                            {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                            @if($order->items->count() > 3)
                                <p class="text-xs text-gray-500 dark:text-zinc-400 text-center py-2">
                                    +{{ $order->items->count() - 3 }} produk lainnya
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="p-4 sm:p-6 border-t border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-800/50">
                        <div class="flex flex-wrap gap-2 justify-end">

                            <!-- Detail -->
                            <a href="{{ route('pembeli.pesanan.show', $order) }}"
                               class="inline-flex items-center gap-1 px-4 py-2 bg-gray-100 dark:bg-zinc-700 text-gray-700 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-600 rounded-lg text-sm font-medium transition-colors">
                                <span class="material-symbols-outlined text-base">visibility</span>
                                Detail
                            </a>

                            <!-- Edit Alamat (hanya jika pending) -->
                            @if($order->status === 'pending')
                                <a href="{{ route('pembeli.pesanan.edit', $order) }}"
                                   class="inline-flex items-center gap-1 px-4 py-2 bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400 hover:bg-yellow-200 dark:hover:bg-yellow-500/30 rounded-lg text-sm font-medium transition-colors">
                                    <span class="material-symbols-outlined text-base">edit</span>
                                    Edit Pesanan
                                </a>
                            @endif

                            <!-- Bayar Sekarang -->
                            @if($order->status === 'pending' && $order->payment?->snap_token)
                                <a href="{{ route('pembeli.payment.show', $order->id) }}"
                                   class="inline-flex items-center gap-1 px-4 py-2 bg-[#16a34a] hover:bg-[#15803d] text-white rounded-lg text-sm font-semibold shadow-md hover:shadow-lg hover:shadow-green-500/30 transition-all duration-200">
                                    <span class="material-symbols-outlined text-base">payment</span>
                                    Bayar Sekarang
                                </a>
                            @endif

                            <!-- Batalkan Pesanan -->
                            @if($order->canBeCancelled())
                                <form id="cancel-form-{{ $order->id }}" action="{{ route('pembeli.pesanan.cancel', $order->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="button"
                                            onclick="confirmCancel('{{ $order->id }}', '#{{ $order->order_number }}')"
                                            class="inline-flex items-center gap-1 px-4 py-2 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/30 hover:bg-red-100 dark:hover:bg-red-500/20 rounded-lg text-sm font-medium transition-all duration-200">
                                        <span class="material-symbols-outlined text-base">cancel</span>
                                        Batalkan
                                    </button>
                                </form>
                            @endif

                            <!-- Tandai Selesai -->
                            @if($order->canBeCompleted())
                                <form id="complete-form-{{ $order->id }}" action="{{ route('pembeli.pesanan.complete', $order->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="button"
                                            onclick="confirmComplete('{{ $order->id }}', '#{{ $order->order_number }}')"
                                            class="inline-flex items-center gap-1 px-4 py-2 bg-[#16a34a] hover:bg-[#15803d] text-white rounded-lg text-sm font-semibold shadow-sm hover:shadow-md hover:shadow-green-500/30 transition-all duration-200">
                                        <span class="material-symbols-outlined text-base">check_circle</span>
                                        Selesai
                                    </button>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-4">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-12 bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800">
            <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-zinc-700">shopping_bag</span>
            <p class="text-lg font-medium text-gray-900 dark:text-white mt-4">Belum ada pesanan</p>
            <a href="{{ route('pembeli.produk.index') }}" class="mt-4 inline-block text-soft-green hover:underline">Mulai Belanja</a>
        </div>
    @endif
</div>





<script>
// Copy to Clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Disalin!',
            text: text,
            toast: true,
            position: 'top-end',
            timer: 1500,
            showConfirmButton: false,
            timerProgressBar: true,
        });
    }).catch(() => {
        prompt('Salin manual:', text);
    });
}

// Konfirmasi Batalkan Pesanan
function confirmCancel(orderId, orderNumber) {
    Swal.fire({
        title: 'Batalkan Pesanan?',
        html: `Apakah Anda yakin ingin membatalkan pesanan <strong>${orderNumber}</strong>?<br><small class="text-gray-500">Tindakan ini tidak dapat dibatalkan.</small>`,
        icon: 'warning',
        iconColor: '#ef4444',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<span style="display:flex;align-items:center;gap:6px"><span class="material-symbols-outlined" style="font-size:18px">cancel</span> Ya, Batalkan</span>',
        cancelButtonText: 'Tidak, Kembali',
        reverseButtons: true,
        focusCancel: true,
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-lg font-semibold',
            cancelButton: 'rounded-lg font-medium',
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Membatalkan...',
                text: 'Mohon tunggu sebentar',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });
            document.getElementById('cancel-form-' + orderId).submit();
        }
    });
}

// Konfirmasi Tandai Selesai
function confirmComplete(orderId, orderNumber) {
    Swal.fire({
        title: 'Tandai Selesai?',
        html: `Konfirmasi bahwa pesanan <strong>${orderNumber}</strong> telah Anda terima dengan baik.`,
        icon: 'question',
        iconColor: '#16a34a',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<span style="display:flex;align-items:center;gap:6px"><span class="material-symbols-outlined" style="font-size:18px">check_circle</span> Ya, Selesai</span>',
        cancelButtonText: 'Kembali',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-lg font-semibold',
            cancelButton: 'rounded-lg font-medium',
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });
            document.getElementById('complete-form-' + orderId).submit();
        }
    });
}
</script>

<!-- ANIMASI CSS -->
<style>
    @keyframes slide-in { from { opacity: 0; transform: translateY(-12px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes shake { 0%,100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .animate-slide-in { animation: slide-in 0.35s cubic-bezier(0.4,0,0.2,1); }
    .animate-shake { animation: shake 0.5s ease-in-out; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-auto-dismiss]').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });
});
</script>
@endsection