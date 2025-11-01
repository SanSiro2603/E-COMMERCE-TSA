{{-- resources/views/admin/orders/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Pesanan')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-charcoal">Kelola Pesanan</h1>
</div>

<!-- Filter & Search -->
<div class="bg-white p-4 rounded-xl shadow-sm mb-6 flex flex-col md:flex-row gap-4">
    <form method="GET" class="flex-1 flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor pesanan / nama pembeli..."
               class="flex-1 px-4 py-2 border rounded-lg text-sm">
        <select name="status" class="px-4 py-2 border rounded-lg text-sm">
            @foreach($statuses as $value => $label)
                <option value="{{ $value }}" {{ request('status', 'all') == $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-soft-green text-white rounded-lg text-sm font-medium">
            Filter
        </button>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py--"
                    "text-left text-xs font-medium text-gray-500 uppercase">No. Pesanan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pembeli</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-charcoal">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-soft-green hover:underline">
                            {{ $order->order_number }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $order->user->name }}</td>
                    <td class="px-6 py-4 text-sm font-medium">Rp {{ number_format($order->grand_total) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @switch($order->status)
                                @case('pending') bg-yellow-100 text-yellow-800 @break
                                @case('paid') bg-blue-100 text-blue-800 @break
                                @case('processing') bg-purple-100 text-purple-800 @break
                                @case('shipped') bg-indigo-100 text-indigo-800 @break
                                @case('completed') bg-green-100 text-green-800 @break
                                @case('cancelled') bg-red-100 text-red-800 @break
                            @endswitch">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $order->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-soft-green hover:underline">Detail</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-8 text-gray-500">Belum ada pesanan</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $orders->appends(request()->query())->links() }}
</div>
@endsection