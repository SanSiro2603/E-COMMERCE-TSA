{{-- resources/views/pembeli/produk/_card.blade.php --}}
<a href="{{ route('pembeli.produk.show', $product->slug) }}" class="group block">
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-zinc-700 hover:shadow-lg transition">
        <div class="aspect-w-1 aspect-h-1 relative">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                     class="w-full h-48 object-cover group-hover:scale-105 transition">
            @else
                <div class="w-full h-48 bg-gray-200 border-2 border-dashed rounded-t-xl"></div>
            @endif
            @if($product->stock <= 5)
                <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                    Stok Terbatas!
                </span>
            @endif
        </div>
        <div class="p-4">
            <p class="text-xs text-gray-600 dark:text-zinc-400">{{ $product->category->name ?? 'Uncategorized' }}</p>
            <h3 class="font-medium text-charcoal dark:text-white group-hover:text-soft-green transition">
                {{ Str::limit($product->name, 40) }}
            </h3>
            <div class="flex justify-between items-center mt-2">
                <p class="text-lg font-bold text-soft-green">Rp {{ number_format($product->price) }}</p>
                <p class="text-xs text-gray-500">{{ $product->stock }} tersisa</p>
            </div>
            <button class="mt-3 w-full gradient-button text-white py-2 rounded-lg text-sm font-medium">
                Lihat Detail
            </button>
        </div>
    </div>
</a>