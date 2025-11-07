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
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-2">
                            Dibayar pada {{ $order->paid_at->format('d M Y, H:i') }}
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

    <!-- Update Status -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-soft-green">sync</span>
            Update Status Pesanan
        </h3>
        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="space-y-4">
            @csrf @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Status</label>
                    <select name="status" required 
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                        <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="paid" {{ old('status', $order->status) == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                        <option value="processing" {{ old('status', $order->status) == 'processing' ? 'selected' : '' }}>Diproses</option>
                        <option value="shipped" {{ old('status', $order->status) == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                        <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Kurir</label>
                    <input type="text" name="courier" placeholder="JNE, J&T, SiCepat..." 
                           value="{{ old('courier', $order->shipment?->courier) }}"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Nomor Resi</label>
                    <input type="text" name="tracking_number" placeholder="Contoh: CGK123456789" 
                           value="{{ old('tracking_number', $order->shipment?->tracking_number) }}"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-soft-green to-primary text-white font-medium rounded-lg hover:shadow-lg transition-all">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Update Status
                </button>
            </div>
        </form>
    </div>

    <!-- Shipment Info (if shipped) -->
    @if($order->shipment && in_array($order->status, ['shipped', 'completed']))
        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-500/10 dark:to-blue-500/10 border border-indigo-200 dark:border-indigo-500/20 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-indigo-900 dark:text-indigo-300 mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined">local_shipping</span>
                Informasi Pengiriman
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-indigo-700 dark:text-indigo-400 font-medium">Kurir:</span>
                    <span class="ml-2 text-indigo-900 dark:text-indigo-300">{{ $order->shipment->courier }}</span>
                </div>
                <div>
                    <span class="text-indigo-700 dark:text-indigo-400 font-medium">No. Resi:</span>
                    <span class="ml-2 font-mono text-indigo-900 dark:text-indigo-300">{{ $order->shipment->tracking_number }}</span>
                </div>
                <div>
                    <span class="text-indigo-700 dark:text-indigo-400 font-medium">Dikirim:</span>
                    <span class="ml-2 text-indigo-900 dark:text-indigo-300">{{ $order->shipment->shipped_at?->format('d M Y, H:i') ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-indigo-700 dark:text-indigo-400 font-medium">Status:</span>
                    <span class="ml-2 inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300">
                        {{ ucfirst($order->shipment->status) }}
                    </span>
                </div>
            </div>
        </div>
    @endif

</div>

<!-- ANIMASI & CONFETTI -->
@if($order->status === 'paid' && $order->paid_at && $order->paid_at->diffInMinutes(now()) < 2)
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        setTimeout(() => {
            confetti({
                particleCount: 120,
                spread: 70,
                origin: { y: 0.6 }
            });
        }, 500);
    </script>
@endif

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.3s ease-out; }
</style>
@endsection