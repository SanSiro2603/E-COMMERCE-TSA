{{-- resources/views/pembeli/pesanan/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

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
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400">
        <a href="{{ route('pembeli.dashboard') }}" class="hover:text-soft-green transition">Beranda</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <a href="{{ route('pembeli.pesanan.index') }}" class="hover:text-soft-green transition">Pesanan</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-gray-900 dark:text-white font-medium">#{{ $order->order_number }}</span>
    </nav>

    <!-- Header -->
    <div class="flex justify-between items-start flex-wrap gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                Pesanan #{{ $order->order_number }}
            </h1>
            <p class="text-sm text-gray-600 dark:text-zinc-400 mt-1">
                Dibuat: {{ $order->created_at->format('d M Y, H:i') }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 rounded-full text-xs font-bold
                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                @elseif($order->status == 'paid') bg-blue-100 text-blue-800
                @elseif($order->status == 'processing') bg-purple-100 text-purple-800
                @elseif($order->status == 'shipped') bg-indigo-100 text-indigo-800
                @elseif($order->status == 'completed') bg-green-100 text-green-800
                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800 @endif">
                {{ $order->status_label ?? ucfirst($order->status) }}
            </span>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6">

        <!-- KIRI: INFORMASI UTAMA -->
        <div class="md:col-span-2 space-y-6">

            <!-- ALAMAT PENGIRIMAN (FALLBACK KE FIELD LAMA) -->
            <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-soft-green">location_on</span>
                    Alamat Pengiriman
                </h2>

                @php
                    $addr = $order->address;

                    $name     = $addr?->recipient_name     ?? $order->recipient_name;
                    $phone    = $addr?->recipient_phone    ?? $order->recipient_phone;
                    $address  = $addr?->full_address       ?? $order->shipping_address;
                    $city     = $addr ? ($addr->city_type . ' ' . $addr->city_name) : $order->city;
                    $province = $addr?->province_name      ?? $order->province;
                    $postal   = $addr?->postal_code        ?? $order->postal_code;
                @endphp

                @if($name && $address)
                    <div class="space-y-2 text-sm text-gray-700 dark:text-zinc-300">
                        <p class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-500 text-lg">person</span>
                            <strong>{{ $name }}</strong>
                            <span class="text-gray-500">({{ $phone }})</span>
                        </p>
                        <p class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-gray-500 text-lg mt-0.5">home</span>
                            <span>
                                {{ $address }}<br>
                                <strong>{{ $city }}</strong>, {{ $province }}
                                @if($postal) <span class="text-gray-500">• {{ $postal }}</span> @endif
                            </span>
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-500 text-lg">local_shipping</span>
                            <span class="font-medium text-soft-green">{{ strtoupper($order->courier) }}</span>
                        </p>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-zinc-500 text-center flex items-center gap-2 justify-center">
                        <span class="material-symbols-outlined text-xl">info</span>
                        Alamat tidak tersedia
                    </p>
                @endif
            </div>

            <!-- PRODUK DIPESAN -->
            <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-soft-green">inventory_2</span>
                    Produk ({{ $order->items->count() }})
                </h2>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex gap-4 p-4 bg-gray-50 dark:bg-zinc-700 rounded-lg">
                            <div class="w-16 h-16 bg-gray-200 dark:bg-zinc-600 rounded-lg overflow-hidden flex-shrink-0">
                                @if($item->product?->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-gray-400">image</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 dark:text-white">
                                    {{ $item->product?->name ?? 'Produk dihapus' }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">
                                    {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}
                                    @if($item->product?->weight)
                                        ({{ $item->product->weight * $item->quantity }} gram)
                                    @endif
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

            <!-- STATUS PENGIRIMAN -->
            @if($order->shipment)
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 p-6 rounded-xl border border-emerald-200 dark:border-emerald-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-600">local_shipping</span>
                        Status Pengiriman
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600 dark:text-zinc-400">Kurir</p>
                            <p class="font-bold text-emerald-700">
                                {{ strtoupper($order->shipment->courier) }}
                                {{ $order->shipment->courier_service ? " ({$order->shipment->courier_service})" : '' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-zinc-400">No. Resi</p>
                            <p class="font-mono text-xs bg-white dark:bg-zinc-800 px-2 py-1 rounded break-all">
                                {{ $order->shipment->tracking_number ?? 'Belum tersedia' }}
                            </p>
                        </div>
                    </div>
                    @if($order->shipment->history && count($order->shipment->history) > 0)
                        <div class="mt-4 pt-4 border-t border-emerald-200 dark:border-emerald-700">
                            <p class="font-medium text-sm mb-2">Riwayat</p>
                            <div class="space-y-2 text-xs">
                                @foreach($order->shipment->history as $log)
                                    <div class="flex justify-between bg-white/70 dark:bg-zinc-800/50 p-2 rounded">
                                        <span>{{ ucfirst($log['status'] ?? 'Update') }}@if(!empty($log['description'])): {{ $log['description'] }}@endif</span>
                                        <span class="text-gray-500">
                                            {{ \Carbon\Carbon::parse($log['timestamp'] ?? now())->format('d/m H:i') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

        </div>

        <!-- KANAN: RINGKASAN & AKSI -->
        <div>
            <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 sticky top-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ringkasan Pembayaran</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-zinc-400">Subtotal Produk</span>
                        <span class="font-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-zinc-400">Ongkos Kirim</span>
                        <span class="font-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-gray-200 dark:border-zinc-700 pt-3 mt-3">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total Bayar</span>
                            <span class="text-soft-green">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- AKSI -->
                <div class="mt-6 space-y-3 border-t border-gray-200 dark:border-zinc-700 pt-6">
                    @if($order->canBeCancelled())
                        <form action="{{ route('pembeli.pesanan.cancel', $order->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-lg">cancel</span>
                                Batalkan Pesanan
                            </button>
                        </form>
                    @endif

                    @if($order->canBeCompleted())
                        <form action="{{ route('pembeli.pesanan.complete', $order->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="w-full py-3 bg-gradient-to-r from-soft-green to-primary text-white font-semibold rounded-lg hover:shadow-lg transition flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-lg">check_circle</span>
                                Tandai Selesai
                            </button>
                        </form>
                    @endif

                    @if($order->status === 'pending')
                        <a href="{{ route('pembeli.pesanan.edit', $order->id) }}"
                           class="block text-center py-3 border border-soft-green text-soft-green font-medium rounded-lg hover:bg-soft-green hover:text-white transition flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-lg">edit</span>
                            Edit Pesanan
                        </a>
                    @endif

                    <a href="{{ route('pembeli.pesanan.index') }}"
                       class="block text-center py-3 text-gray-600 dark:text-zinc-400 hover:text-soft-green transition">
                        Kembali ke Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection