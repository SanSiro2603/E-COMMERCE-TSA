{{-- resources/views/pembeli/components/pesanan-card.blade.php --}}
<div class="bg-white dark:bg-zinc-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700">
    <div class="flex justify-between items-start mb-3">
        <div>
            <p class="text-sm font-medium text-charcoal dark:text-white">#{{ $order->order_number }}</p>
            <p class="text-xs text-gray-600 dark:text-zinc-400">{{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <span class="px-2 py-1 text-xs rounded-full 
            @switch($order->status)
                @case('pending') bg-yellow-100 text-yellow-800 @break
                @case('paid') bg-blue-100 text-blue-800 @break
                @case('processing') bg-purple-100 text-purple-800 @break
                @case('shipped') bg-indigo-100 text-indigo-800 @break
                @case('completed') bg-green-100 text-green-800 @break
                @default bg-gray-100 text-gray-800
            @endswitch">
            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
        </span>
    </div>
    <div class="space-y-2 mb-3">
        @foreach($order->items->take(2) as $item)
            <p class="text-sm text-charcoal dark:text-zinc-300">
                {{ $item->quantity }}x {{ $item->product->name }}
            </p>
        @endforeach
        @if($order->items->count() > 2)
            <p class="text-xs text-gray-500">+{{ $order->items->count() - 2 }} item lain</p>
        @endif
    </div>
    <div class="flex justify-between items-center">
        <p class="text-sm font-medium text-soft-green">Rp {{ number_format($order->grand_total) }}</p>
        <a href="{{ route('pembeli.pesanan.show', $order) }}" class="text-xs text-soft-green hover:underline">
            Lihat Detail
        </a>
    </div>
</div>