{{-- resources/views/pembeli/pesanan/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number . ' - Lembah Hijau')

@section('content')
<div class="space-y-6">

    <!-- Alerts -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">check_circle</span>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-green-900 dark:text-green-300">Sukses!</h3>
                    <p class="text-sm text-green-800 dark:text-green-400 mt-1">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-2xl">error</span>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-red-900 dark:text-red-300">Gagal!</h3>
                    <p class="text-sm text-red-800 dark:text-red-400 mt-1">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400">
        <a href="{{ route('pembeli.dashboard') }}" class="hover:text-soft-green transition-colors">Beranda</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <a href="{{ route('pembeli.pesanan.index') }}" class="hover:text-soft-green transition-colors">Pesanan</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-gray-900 dark:text-white font-medium">Detail #{{ $order->order_number }}</span>
    </nav>

    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">
                Pesanan #{{ $order->order_number }}
            </h1>
            <p class="text-sm text-gray-600 dark:text-zinc-400 mt-1">
                Dibuat pada: {{ $order->created_at->format('d M Y H:i') }}
            </p>
        </div>
        <div class="text-right">
            <span class="px-4 py-2 rounded-full text-sm font-bold
                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                @elseif($order->status == 'paid') bg-blue-100 text-blue-800
                @elseif($order->status == 'processing') bg-purple-100 text-purple-800
                @elseif($order->status == 'shipped') bg-indigo-100 text-indigo-800
                @elseif($order->status == 'completed') bg-green-100 text-green-800
                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800
                @endif">
                {{ $order->status_label ?? ucfirst($order->status) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Payment Info -->
            @if($order->payment)
            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-500/10 dark:to-cyan-500/10 rounded-xl border border-blue-200 dark:border-blue-500/20 p-6">
                <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600">payment</span>
                    Informasi Pembayaran
                </h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600 dark:text-zinc-400">Metode</p>
                        <p class="font-bold capitalize">{{ $order->payment->payment_type ?? 'Midtrans' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 dark:text-zinc-400">Status</p>
                        <p class="font-bold">
                            @if($order->payment->isSuccess())
                                <span class="text-green-600">Berhasil</span>
                            @elseif($order->payment->isPending())
                                <span class="text-yellow-600">Menunggu</span>
                            @else
                                <span class="text-red-600">Gagal</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600 dark:text-zinc-400">Dibayar pada</p>
                        <p class="font-medium">{{ $order->paid_at?->format('d M Y H:i') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 dark:text-zinc-400">Transaction ID</p>
                        <p class="font-mono text-xs bg-white dark:bg-zinc-800 px-2 py-1 rounded">
                            {{ $order->payment->transaction_id ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Shipment Info -->
            @if($order->shipment)
            <div class="bg-gradient-to-r from-emerald-50 to-green-50 dark:from-emerald-500/10 dark:to-green-500/10 rounded-xl border border-emerald-200 dark:border-emerald-500/20 p-6">
                <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600">local_shipping</span>
                    Status Pengiriman
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600 dark:text-zinc-400">Kurir & Layanan</p>
                        <p class="font-bold text-emerald-700">
                            {{ strtoupper($order->shipment->courier) }}
                            {{ $order->shipment->courier_service ? " ({$order->shipment->courier_service})" : '' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600 dark:text-zinc-400">No. Resi</p>
                        <p class="font-bold text-blue-600 break-all">
                            {{ $order->shipment->tracking_number ?? 'Belum tersedia' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600 dark:text-zinc-400">Status Pengiriman</p>
                        <p>
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                bg-{{ $order->shipment->status_color }}-100 text-{{ $order->shipment->status_color }}-800">
                                {{ $order->shipment->status_label }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600 dark:text-zinc-400">Dikirim pada</p>
                        <p class="font-medium">
                            {{ $order->shipment->shipped_at?->format('d M Y H:i') ?? '-' }}
                        </p>
                    </div>
                </div>

                @if($order->shipment->history && count($order->shipment->history) > 0)
                <div class="mt-4 pt-4 border-t border-emerald-200 dark:border-emerald-500/30">
                    <p class="font-medium text-sm mb-2">Riwayat Tracking</p>
                    <div class="space-y-2 text-xs">
                        @foreach($order->shipment->history as $log)
                        <div class="flex justify-between items-start bg-white/70 dark:bg-zinc-800/50 p-2 rounded">
                            <div>
                                <span class="font-medium">{{ ucfirst($log['status'] ?? 'Update') }}</span>
                                @if(!empty($log['description']))
                                    : <span class="text-gray-600 dark:text-zinc-400">{{ $log['description'] }}</span>
                                @endif
                            </div>
                            <span class="text-gray-500">
                                {{ \Carbon\Carbon::parse($log['timestamp'] ?? now())->format('d/m H:i') }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @else
            <div class="bg-yellow-50 dark:bg-yellow-500/10 border border-yellow-200 dark:border-yellow-500/20 rounded-xl p-6 text-center">
                <span class="material-symbols-outlined text-yellow-600 text-4xl">inventory_2</span>
                <p class="mt-2 font-medium text-yellow-800 dark:text-yellow-300">
                    Pesanan sedang diproses oleh penjual
                </p>
                <p class="text-sm text-yellow-700 dark:text-yellow-400">
                    Resi akan muncul setelah barang dikirim
                </p>
            </div>
            @endif

            <!-- Shipping Address -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                    <h2 class="text-lg font-semibold flex items-center gap-2">
                        <span class="material-symbols-outlined text-soft-green">location_on</span>
                        Alamat Pengiriman
                    </h2>
                </div>
                <div class="p-6 space-y-2 text-sm">
                    <p><span class="font-semibold">Penerima:</span> {{ $order->recipient_name ?? '-' }}</p>
                    <p><span class="font-semibold">Telepon:</span> {{ $order->recipient_phone ?? '-' }}</p>
                    <p><span class="font-semibold">Alamat:</span> {{ $order->shipping_address }}</p>
                    <p><span class="font-semibold">Kota:</span> {{ $order->city ?? '-' }}, {{ $order->province ?? '-' }} {{ $order->postal_code ? ' - ' . $order->postal_code : '' }}</p>
                    <p><span class="font-semibold">Kurir:</span> {{ $order->courier ?? '-' }}</p>
                </div>
            </div>

            <!-- Products -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                    <h2 class="text-lg font-semibold flex items-center gap-2">
                        <span class="material-symbols-outlined text-soft-green">shopping_bag</span>
                        Produk ({{ $order->items->count() }} item)
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex gap-4 p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-lg">
                        <div class="w-16 h-16 bg-gray-200 dark:bg-zinc-700 rounded-lg overflow-hidden flex-shrink-0">
                            @if($item->product?->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-400">image</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                {{ $item->product?->name ?? 'Produk dihapus' }}
                            </h3>
                            <p class="text-xs text-gray-600 dark:text-zinc-400 mt-1">
                                {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-900 dark:text-white">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Right Column - Summary & Actions -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm sticky top-20">
                <div class="p-6 border-b border-gray-200 dark:border-zinc-800">
                    <h2 class="text-lg font-semibold">Ringkasan Pembayaran</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-zinc-400">Subtotal Produk</span>
                        <span class="font-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-zinc-400">Biaya Pengiriman</span>
                        <span class="font-semibold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-4 border-t border-gray-200 dark:border-zinc-800">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total Bayar</span>
                            <span class="text-soft-green">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="p-6 space-y-3 border-t border-gray-200 dark:border-zinc-800">
                    @if($order->canBeCancelled())
                        <form action="{{ route('pembeli.pesanan.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan?')">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition">
                                Batalkan Pesanan
                            </button>
                        </form>
                    @endif

                    @if($order->canBeCompleted())
                        <form action="{{ route('pembeli.pesanan.complete', $order->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-full py-3 bg-gradient-to-r from-soft-green to-emerald-600 text-white font-semibold rounded-xl hover:shadow-lg transition">
                                Tandai sebagai Selesai
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('pembeli.pesanan.index') }}" class="block text-center py-3 text-gray-600 dark:text-zinc-400 hover:text-soft-green transition">
                        ← Kembali ke Daftar Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection