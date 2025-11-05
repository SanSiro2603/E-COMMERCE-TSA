{{-- resources/views/pembeli/payment/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Pembayaran - Lembah Hijau')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400">
        <a href="{{ route('pembeli.dashboard') }}" class="hover:text-soft-green transition-colors">Beranda</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <a href="{{ route('pembeli.pesanan.index') }}" class="hover:text-soft-green transition-colors">Pesanan</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-gray-900 dark:text-white font-medium">Pembayaran</span>
    </nav>

    <!-- Payment Info Card -->
    <div class="bg-gradient-to-r from-soft-green/10 to-primary/10 dark:from-soft-green/5 dark:to-primary/5 border border-soft-green/20 dark:border-soft-green/10 rounded-xl p-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-soft-green/20 dark:bg-soft-green/10 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-soft-green text-2xl">payment</span>
            </div>
            <div class="flex-1">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    Pembayaran Pesanan #{{ $order->order_number }}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                    <div>
                        <span class="text-gray-600 dark:text-zinc-400">Total Bayar:</span>
                        <p class="text-lg font-bold text-soft-green mt-1">
                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-zinc-400">Status:</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                            {{ $payment->status_label }}
                        </p>
                    </div>
                    @if($payment->expiry_time)
                    <div>
                        <span class="text-gray-600 dark:text-zinc-400">Batas Waktu:</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                            {{ $payment->expiry_time->format('d M Y, H:i') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Button -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-6">
        <div class="text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-soft-green to-primary rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-white text-4xl">credit_card</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                Selesaikan Pembayaran
            </h3>
            <p class="text-gray-600 dark:text-zinc-400 mb-6">
                Klik tombol di bawah untuk melanjutkan ke halaman pembayaran
            </p>
            
            <button id="pay-button"
                    class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-soft-green to-primary text-white font-semibold rounded-xl hover:shadow-xl transition-all text-lg">
                <span class="material-symbols-outlined text-2xl">payment</span>
                Bayar Sekarang
            </button>

            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-500/10 rounded-lg border border-blue-200 dark:border-blue-500/20">
                <div class="flex items-start gap-2 text-left">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-lg">info</span>
                    <div class="text-sm text-blue-800 dark:text-blue-400">
                        <p class="font-semibold mb-1">Metode Pembayaran Tersedia:</p>
                        <p>E-Wallet (GoPay, ShopeePay, QRIS), Transfer Bank, Kartu Kredit, dan Minimarket</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Pesanan</h3>
        </div>
        <div class="p-6 space-y-4">
            @foreach($order->items as $item)
                <div class="flex gap-4">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-zinc-800 rounded-lg overflow-hidden flex-shrink-0">
                        @if($item->product && $item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                 alt="{{ $item->product->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-gray-400">image</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
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

            <div class="pt-4 border-t border-gray-200 dark:border-zinc-800 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-zinc-400">Subtotal</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-zinc-400">Biaya Pengiriman</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-zinc-800">
                    <span class="font-semibold text-gray-900 dark:text-white">Total Bayar</span>
                    <span class="text-xl font-bold text-soft-green">
                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="text-center">
        <a href="{{ route('pembeli.pesanan.show', $order) }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 font-medium rounded-xl hover:bg-gray-200 dark:hover:bg-zinc-700 transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
            Kembali ke Detail Pesanan
        </a>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ $clientKey }}"></script>
<script>
document.getElementById('pay-button').addEventListener('click', function () {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            console.log('Payment success:', result);
            window.location.href = '{{ route("pembeli.pesanan.index") }}' 
                + '?order_id={{ $order->order_number }}'
                + '&transaction_status=' + result.transaction_status;
        },
        onPending: function(result) {
            console.log('Payment pending:', result);
            window.location.href = '{{ route("pembeli.pesanan.index") }}' 
                + '?order_id={{ $order->order_number }}'
                + '&transaction_status=' + result.transaction_status;
        },
        onError: function(result) {
            console.error('Payment error:', result);
            alert('Pembayaran gagal. Silakan coba lagi.');
        },
        onClose: function() {
            alert('Anda menutup popup pembayaran sebelum selesai.');
        }
    });
});
</script>
@endpush
