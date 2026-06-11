<?php

use App\Models\OrderItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Storage;

return new class extends Migration {
    public function up(): void
    {
        OrderItem::with(['order', 'product'])
            ->whereHas('order')
            ->chunkById(100, function ($items) {
                foreach ($items as $item) {
                    if ($item->product_image && str_starts_with($item->product_image, 'order-items/')) {
                        continue;
                    }

                    $source = $item->product_image ?: $item->product?->image;
                    if (!$source || !Storage::disk('public')->exists($source)) {
                        continue;
                    }

                    $extension = pathinfo($source, PATHINFO_EXTENSION) ?: 'jpg';
                    $destination = sprintf(
                        'order-items/%s/%s-%s.%s',
                        $item->order->order_number,
                        $item->id,
                        uniqid(),
                        $extension
                    );

                    if (Storage::disk('public')->copy($source, $destination)) {
                        $item->update(['product_image' => $destination]);
                    }
                }
            });
    }

    public function down(): void
    {
        // Snapshot files are intentionally retained to avoid breaking order history.
    }
};
