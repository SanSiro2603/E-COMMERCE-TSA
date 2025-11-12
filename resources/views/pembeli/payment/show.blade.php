{{-- resources/views/pembeli/payment/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Pembayaran - Lembah Hijau')

@section('content')
<div class="max-w-6xl mx-auto p-4 space-y-6">
    <h1 class="text-2xl font-bold">Pembayaran</h1>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded-lg border border-red-300">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-lg border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid md:grid-cols-3 gap-6">
        <!-- Left: Payment Info + Button -->
        <div class="md:col-span-2 space-y-6">
            <!-- Payment Card -->
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-soft-green to-primary rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-3xl">payment</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Selesaikan Pembayaran</h2>
                        <p class="text-gray-600 text-sm">Pesanan #{{ $order->order_number }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Total Bayar</span>
                            <p class="text-2xl font-bold text-green-600 mt-1">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-600">Batas Waktu</span>
                            <p class="font-medium mt-1">
                                @if($payment->expiry_time)
                                    {{ $payment->expiry_time->format('d M Y, H:i') }}
                                    <span class="block text-xs text-red-600">
                                        {{ $payment->expiry_time->diffForHumans() }}
                                    </span>
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-blue-600 text-xl">info</span>
                            <div class="text-sm">
                                <p class="font-semibold text-blue-900 dark:text-blue-300">Metode Pembayaran</p>
                                <p class="text-blue-800 dark:text-blue-400">
                                    GoPay, ShopeePay, QRIS, Transfer Bank, Kartu Kredit, Alfamart, Indomaret
                                </p>
                            </div>
                        </div>
                    </div>

                    <button id="pay-button" class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded-lg font-bold text-lg transition flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined text-2xl">credit_card</span>
                        Bayar Sekarang
                    </button>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="font-semibold mb-4">Produk yang Dibeli ({{ $order->items->count() }})</h2>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between pb-3 border-b last:border-0">
                            <div class="flex items-center gap-4">
                                <!-- Gambar Produk -->
                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-16 h-16 object-cover rounded-lg border">

                                <div class="text-sm">
                                    <p class="font-medium">{{ $item->product->name }}</p>
                                    <p class="text-gray-500">
                                        {{ $item->quantity }} × Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <p class="font-medium text-sm">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right: Ringkasan -->
        <div>
            <div class="bg-white p-6 rounded-lg shadow sticky top-4">
                <h2 class="font-semibold mb-4">Ringkasan Pembayaran</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Subtotal Produk</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Ongkos Kirim</span>
                        <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t pt-3 mt-3">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total Bayar</span>
                            <span class="text-green-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t">
                    <a href="{{ route('pembeli.pesanan.show', $order) }}" 
                       class="block text-center text-sm text-gray-600 hover:text-gray-800">
                        ← Kembali ke Detail Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
<script>
document.getElementById('pay-button').onclick = function(){
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result){
            console.log('Payment success:', result);
            window.location.href = '{{ route("pembeli.pesanan.index") }}?status=success&order={{ $order->order_number }}';
        },
        onPending: function(result){
            console.log('Payment pending:', result);
            window.location.href = '{{ route("pembeli.pesanan.index") }}?status=pending&order={{ $order->order_number }}';
        },
        onError: function(result){
            console.log('Payment error:', result);
            alert('Pembayaran gagal! Silakan coba lagi.');
            window.location.href = '{{ route("pembeli.pesanan.index") }}?status=error&order={{ $order->order_number }}';
        },
        onClose: function(){
            console.log('Popup ditutup');
            window.location.href = '{{ route("pembeli.pesanan.index") }}?status=cancelled&order={{ $order->order_number }}';
        }
    });
};
</script>
@endpush
@endsection
