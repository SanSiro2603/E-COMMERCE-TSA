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
               class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 {{ !request('status') || request('status') == 'all' ? 'bg-[#16a34a] text-white shadow-md' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-700 hover:text-[#16a34a] dark:hover:text-green-400' }}">
                Semua
            </a>
            @foreach($statuses as $key => $label)
                @if($key !== 'all')
                    <a href="{{ route('pembeli.pesanan.index', ['status' => $key]) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 {{ request('status') == $key ? 'bg-[#16a34a] text-white shadow-md' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-700 hover:text-[#16a34a] dark:hover:text-green-400' }}">
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
                    <div class="px-4 py-3 border-b border-gray-100 dark:border-zinc-800/80 flex justify-between items-center bg-gray-50/50 dark:bg-zinc-800/30">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-gray-500 dark:text-zinc-400">storefront</span>
                            <span class="text-xs font-bold text-gray-900 dark:text-white">Pesanan #{{ substr($order->order_number, -6) }}</span>
                        </div>
                        <span class="text-[10px] sm:text-xs font-extrabold uppercase tracking-wider
                            {{ $order->status === 'pending' ? 'text-yellow-600 dark:text-yellow-500' : '' }}
                            {{ $order->status === 'paid' ? 'text-blue-600 dark:text-blue-500' : '' }}
                            {{ $order->status === 'processing' ? 'text-purple-600 dark:text-purple-500' : '' }}
                            {{ $order->status === 'shipped' ? 'text-indigo-600 dark:text-indigo-500' : '' }}
                            {{ $order->status === 'completed' ? 'text-green-600 dark:text-green-500' : '' }}
                            {{ in_array($order->status, ['cancelled', 'failed']) ? 'text-red-600 dark:text-red-500' : '' }}">
                            {{ $order->status_label }}
                        </span>
                    </div>

                    <!-- Items Preview -->
                    <div class="px-4 py-3">
                        <div class="space-y-4">
                            @foreach($order->items->take(3) as $item)
                                <div class="flex gap-3 sm:gap-4 items-start">
                                    <div class="relative shrink-0">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50 shadow-sm">
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
                                                {{ $item->product->name ?? 'Produk tidak tersedia' }}
                                            </h4>
                                            @if($item->product)
                                                <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-gray-100 dark:bg-zinc-800 text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">
                                                        {{ $item->product->category->name ?? 'Uncategorized' }}
                                                    </span>
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
                            @if($order->items->count() > 3)
                                <div class="pt-3 mt-1 border-t border-dashed border-gray-200 dark:border-zinc-700/60">
                                    <a href="{{ route('pembeli.pesanan.show', $order) }}" class="block text-xs text-gray-500 dark:text-zinc-400 text-center font-medium hover:text-soft-green transition-colors">
                                        Lihat {{ $order->items->count() - 3 }} produk lainnya...
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Footer & Actions -->
                    <div class="px-4 py-3 border-t border-gray-100 dark:border-zinc-800/80 bg-white dark:bg-zinc-900">
                        <div class="flex justify-between items-center mb-3">
                            <p class="text-[10px] sm:text-xs text-gray-500 dark:text-zinc-400 font-medium">{{ $order->items->count() }} produk</p>
                            <div class="text-right">
                                <p class="text-[10px] sm:text-xs text-gray-500 dark:text-zinc-400 font-medium inline">Total Pesanan:</p>
                                <p class="text-sm sm:text-base font-extrabold text-soft-green inline ml-1">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 justify-end">
                            <!-- Detail -->
                            <a href="{{ route('pembeli.pesanan.show', $order) }}"
                               class="px-3 py-1.5 bg-gray-100 dark:bg-zinc-700 text-gray-700 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-600 rounded text-[11px] sm:text-xs font-bold transition-colors">
                                Detail
                            </a>

                            <!-- Edit Alamat -->
                            @if($order->status === 'pending')
                                <a href="{{ route('pembeli.pesanan.edit', $order) }}"
                                   class="px-3 py-1.5 bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400 hover:bg-yellow-200 dark:hover:bg-yellow-500/30 rounded text-[11px] sm:text-xs font-bold transition-colors">
                                    Edit
                                </a>
                            @endif

                            <!-- Batalkan Pesanan -->
                            @if($order->canBeCancelled())
                                <form id="cancel-form-{{ $order->id }}" action="{{ route('pembeli.pesanan.cancel', $order->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="button"
                                            onclick="confirmCancel('{{ $order->id }}', '#{{ $order->order_number }}')"
                                            class="px-3 py-1.5 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 rounded text-[11px] sm:text-xs font-bold transition-colors">
                                        Batalkan
                                    </button>
                                </form>
                            @endif

                            <!-- Bayar Sekarang -->
                            @if($order->status === 'pending' && $order->payment?->snap_token)
                                <a href="{{ route('pembeli.payment.show', $order->id) }}"
                                   class="px-4 py-1.5 bg-soft-green text-white hover:bg-[#15803d] rounded text-[11px] sm:text-xs font-bold shadow-sm transition-colors">
                                    Bayar Sekarang
                                </a>
                            @endif

                            <!-- Tandai Selesai -->
                            @if($order->canBeCompleted())
                                <form id="complete-form-{{ $order->id }}" action="{{ route('pembeli.pesanan.complete', $order->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="button"
                                            onclick="confirmComplete('{{ $order->id }}', '#{{ $order->order_number }}')"
                                            class="px-4 py-1.5 bg-soft-green text-white hover:bg-[#15803d] rounded text-[11px] sm:text-xs font-bold shadow-sm transition-colors">
                                        Pesanan Selesai
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
            <div class="mt-8 mb-4">
                {{ $orders->appends(request()->query())->links('vendor.pagination.minimal') }}
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