{{-- resources/views/pembeli/payment/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Pembayaran - E-Commerce TSA')

@section('content')
    <div class="max-w-6xl mx-auto p-4 space-y-6 pb-28">
        {{-- Header --}}
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Pembayaran
        </h1>

        {{-- Alert Messages --}}
        @if(session('error'))
            <div
                class="bg-red-50/50 dark:bg-red-950/10 border border-red-150 dark:border-red-800 text-red-750 dark:text-red-300 p-4 rounded-xl flex items-center gap-2.5 text-xs sm:text-sm font-medium">
                <span class="material-symbols-outlined text-base">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('success'))
            <div
                class="bg-green-50/40 dark:bg-green-950/10 border border-green-150 dark:border-green-800 text-green-750 dark:text-green-300 p-4 rounded-xl flex items-center gap-2.5 text-xs sm:text-sm font-medium">
                <span class="material-symbols-outlined text-base">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid md:grid-cols-3 gap-6">
            {{-- Left: Payment Info + Button --}}
            <div class="md:col-span-2 space-y-6">
                {{-- Payment Card --}}
                <div
                    class="bg-white dark:bg-zinc-800 p-5 sm:p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700/50">
                    <div class="flex items-center gap-3.5 mb-6 pb-4 border-b border-gray-100 dark:border-zinc-700/50">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-soft-green/10 dark:bg-soft-green/20 rounded-xl flex items-center justify-center shrink-0 text-soft-green">
                            <span class="material-symbols-outlined text-xl sm:text-2xl">receipt_long</span>
                        </div>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">Selesaikan Pembayaran
                            </h2>
                            <p class="text-gray-400 dark:text-zinc-550 text-xs mt-0.5">Pesanan #{{ $order->order_number }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm border-b border-dashed border-gray-150 dark:border-zinc-705 pb-5 mb-2">
                            <div>
                                <span
                                    class="text-gray-400 dark:text-zinc-500 font-bold uppercase tracking-wider text-[10px]">Total
                                    Bayar</span>
                                <p class="text-xl sm:text-2xl font-extrabold text-soft-green mt-1">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <span
                                    class="text-gray-400 dark:text-zinc-500 font-bold uppercase tracking-wider text-[10px]">Batas
                                    Waktu Pembayaran</span>
                                <p class="font-bold mt-1 text-gray-800 dark:text-white flex items-center gap-1.5">
                                    @if($payment->expiry_time)
                                        <span class="material-symbols-outlined text-sm text-amber-500">schedule</span>
                                        <span>{{ $payment->expiry_time->format('d M Y, H:i') }}</span>
                                        <span class="block text-[10px] font-semibold text-red-500 dark:text-red-400">
                                            ({{ $payment->expiry_time->diffForHumans() }})
                                        </span>
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- Info Box --}}
                        <div
                            class="bg-blue-50/50 dark:bg-blue-950/10 border border-blue-150 dark:border-blue-800 rounded-xl p-4">
                            <div class="flex items-start gap-2.5">
                                <span
                                    class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-lg shrink-0 mt-0.5">info</span>
                                <div class="text-xs sm:text-sm">
                                    <p class="font-bold text-blue-900 dark:text-blue-300">Informasi Pembayaran</p>
                                    <p class="text-gray-650 dark:text-gray-450 mt-0.5 leading-relaxed">
                                        Transaksi diproses dengan aman menggunakan payment gateway Midtrans. Anda dapat
                                        memilih metode Transfer Bank, e-Wallet, atau Kartu Kredit pada popup pembayaran.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Order Items --}}
                <div
                    class="bg-white dark:bg-zinc-800 p-5 sm:p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700/50">
                    <h2
                        class="font-bold text-base sm:text-lg text-gray-900 dark:text-white mb-5 pb-3 border-b border-gray-100 dark:border-zinc-700/50">
                        Produk yang Dibeli ({{ $order->items->count() }})
                    </h2>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex gap-3 sm:gap-4 border-b border-dashed border-gray-200 dark:border-zinc-700/60 pb-4 last:border-0 last:pb-0 items-start">
                                <div class="relative shrink-0">
                                    @if($item->display_image)
                                        <img src="{{ asset('storage/' . $item->display_image) }}" alt="{{ $item->display_name }}"
                                            class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50 shadow-sm">
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
                                        <p class="font-bold text-sm text-gray-900 dark:text-white line-clamp-2 leading-snug">
                                            {{ $item->display_name }}
                                        </p>
                                        <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-gray-100 dark:bg-zinc-800 text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">
                                                {{ $item->display_category_name }}
                                            </span>
                                            @if($item->product?->weight)
                                                <span class="text-[10px] text-gray-400 dark:text-zinc-500 font-medium">
                                                    • {{ $item->product->weight * $item->quantity }} gr
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-sm sm:text-base font-extrabold text-soft-green mt-2.5">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right: Ringkasan --}}
            <div>
                <div
                    class="bg-white dark:bg-zinc-800 p-5 sm:p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700/50 sticky top-24">
                    <h2
                        class="font-bold text-lg mb-5 text-gray-900 dark:text-white pb-3 border-b border-gray-100 dark:border-zinc-700/50">
                        Ringkasan Pembayaran</h2>
                    <div class="space-y-4 text-xs sm:text-sm text-gray-800 dark:text-zinc-200">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 dark:text-zinc-450 font-medium">Subtotal Produk</span>
                            <span class="font-bold text-gray-800 dark:text-zinc-200">Rp
                                {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 dark:text-zinc-450 font-medium">Ongkos Kirim</span>
                            <span class="font-bold text-gray-800 dark:text-zinc-200">Rp
                                {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-dashed border-gray-250 dark:border-zinc-700 pt-4 mt-4">
                            <div class="flex justify-between items-baseline">
                                <span class="text-gray-800 dark:text-white font-bold text-sm">Total Bayar</span>
                                <span class="text-xl font-extrabold text-soft-green">Rp
                                    {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Desktop Pay Button (Hidden on mobile, visible on desktop) --}}
                    <button id="pay-button" onclick="startPayment()"
                        class="mt-6 hidden sm:flex w-full bg-soft-green text-white py-3.5 rounded-xl font-bold text-sm items-center justify-center gap-2 active:bg-[#15803d] active:scale-[0.99] transition-transform duration-100 shadow-md">
                        <span class="material-symbols-outlined text-lg">credit_card</span>
                        Bayar Sekarang (Midtrans)
                    </button>

                    <div class="hidden sm:block mt-4 pt-4 border-t border-gray-100 dark:border-zinc-700/55">
                        <a href="{{ route('pembeli.pesanan.show', $order) }}"
                            class="flex items-center justify-center gap-2 w-full py-2.5 border border-gray-200 dark:border-zinc-700 hover:border-gray-300 dark:hover:border-zinc-600 text-xs font-bold text-gray-650 dark:text-zinc-300 rounded-xl transition-all duration-200 bg-gray-50/30 hover:bg-gray-50 dark:bg-transparent dark:hover:bg-zinc-800/50">
                            <span class="material-symbols-outlined text-sm">arrow_back</span>
                            Kembali ke Detail Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky Pay Bar (Shopee / Premium Transaction Style - Mobile Only) -->
    <div class="sm:hidden fixed bottom-0 left-0 right-0 z-40 bg-zinc-900 dark:bg-zinc-950 text-white border-t border-zinc-800/80 shadow-2xl px-4 py-3 pb-safe">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="text-left">
                <p class="text-[10px] text-zinc-400 font-medium">Total Pembayaran</p>
                <p class="text-base sm:text-lg font-bold text-white">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
            </div>
            <button type="button" onclick="startPayment()"
                class="px-6 py-2.5 bg-soft-green text-white hover:bg-green-700 text-xs sm:text-sm font-bold rounded-xl transition active:scale-[0.98] shadow-md">
                Bayar Sekarang
            </button>
        </div>
    </div>

    @push('scripts')
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
        <script>
            const savePaymentMethodUrl = '{{ route("pembeli.payment.save-method", $order) }}';
            const csrfToken = '{{ csrf_token() }}';

            /**
             * Simpan payment_type ke server via AJAX
             * Ini solusi agar payment_method tersimpan di lokal maupun production
             * tanpa harus tunggu webhook dari Midtrans
             */
            function savePaymentMethod(paymentType, transactionStatus, callback) {
                fetch(savePaymentMethodUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        payment_type: paymentType,
                        transaction_status: transactionStatus,
                    }),
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Payment method saved:', data);
                        callback();
                    })
                    .catch(error => {
                        console.warn('Gagal simpan payment method (lanjut redirect):', error);
                        callback(); // Tetap redirect meski gagal simpan
                    });
            }

            // Shared payment trigger function
            window.startPayment = function () {
                snap.pay('{{ $snapToken }}', {
                    onSuccess: function (result) {
                        console.log('Payment success:', result);
                        savePaymentMethod(
                            result.payment_type,
                            result.transaction_status,
                            function () {
                                window.location.href = '{{ route("pembeli.pesanan.index") }}?status=success&order={{ $order->order_number }}';
                            }
                        );
                    },
                    onPending: function (result) {
                        console.log('Payment pending:', result);
                        savePaymentMethod(
                            result.payment_type,
                            result.transaction_status,
                            function () {
                                window.location.href = '{{ route("pembeli.payment.show", $order) }}?status=pending&order={{ $order->order_number }}';
                            }
                        );
                    },
                    onError: function (result) {
                        console.log('Payment error:', result);
                        alert('Pembayaran gagal! Silakan coba lagi.');
                        window.location.href = '{{ route("pembeli.payment.show", $order) }}?status=error&order={{ $order->order_number }}';
                    },
                    onClose: function () {
                        console.log('Popup ditutup');
                    }
                });
            };

            const payBtn = document.getElementById('pay-button');
            if (payBtn) {
                payBtn.onclick = startPayment;
            }
        </script>
    @endpush
@endsection
