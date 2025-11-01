{{-- resources/views/admin/orders/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail Pesanan #'. $order->order_number)

@section('content')
<div class="max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-charcoal">Pesanan #{{ $order->order_number }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="text-soft-green hover:underline text-sm">← Kembali</a>
    </div>

    <!-- Info Pesanan -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold text-charcoal mb-2">Informasi Pembeli</h3>
                <p><strong>Nama:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                <p><strong>Telepon:</strong> {{ $order->user->phone ?? '-' }}</p>
                <p><strong>Alamat:</strong> {{ $order->shipping_address }}</p>
            </div>
            <div>
                <h3 class="font-semibold text-charcoal mb-2">Ringkasan</h3>
                <p><strong>Total:</strong> Rp {{ number_format($order->grand_total) }}</p>
                <p><strong>Status:</strong>
                    <span class="px-2 py-1 text-xs rounded-full 
                        @switch($order->status)
                            @case('pending') bg-yellow-100 text-yellow-800 @break
                            @case('paid') bg-blue-100 text-blue-800 @break
                            @default bg-gray-100 text-gray-800
                        @endswitch">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </p>
                <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Produk -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h3 class="font-semibold text-charcoal mb-4">Produk yang Dipesan</h3>
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">Produk</th>
                    <th class="text-center py-2">Jumlah</th>
                    <th class="text-right py-2">Harga</th>
                    <th class="text-right py-2">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr class="border-b">
                        <td class="py-3 flex items-center gap-3">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="w-12 h-12 object-cover rounded">
                            @endif
                            <div>
                                <p class="font-medium">{{ $item->product->name }}</p>
                                <p class="text-xs text-gray-600">{{ $item->product->category->name ?? '' }}</p>
                            </div>
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($item->price) }}</td>
                        <td class="text-right font-medium">Rp {{ number_format($item->subtotal) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Update Status -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-charcoal mb-4">Update Status</h3>
        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
            @csrf @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <select name="status" required class="px-4 py-2 border rounded-lg">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Diproses</option>
                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <input type="text" name="courier" placeholder="Kurir (JNE, J&T, dll)" value="{{ $order->shipment?->courier ?? '' }}"
                       class="px-4 py-2 border rounded-lg">
                <input type="text" name="tracking_number" placeholder="Nomor Resi" value="{{ $order->shipment?->tracking_number ?? '' }}"
                       class="px-4 py-2 border rounded-lg">
            </div>
            <button type="submit" class="mt-4 px-6 py-2 gradient-button text-white rounded-lg font-medium">
                Update Status
            </button>
        </form>
    </div>
</div>
@endsection