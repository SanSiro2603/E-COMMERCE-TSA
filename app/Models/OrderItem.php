<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_image',
        'product_category_name',
        'quantity',
        'price',
        'subtotal',
    ];

    public function order() { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }

    public function getDisplayNameAttribute(): string
    {
        return $this->product?->name ?? $this->product_name ?? 'Produk dihapus';
    }

    public function getDisplayImageAttribute(): ?string
    {
        foreach ([$this->product_image, $this->product?->image] as $image) {
            if ($image && Storage::disk('public')->exists($image)) {
                return $image;
            }
        }

        return null;
    }

    public function getDisplayCategoryNameAttribute(): string
    {
        return $this->product?->category?->name ?? $this->product_category_name ?? 'Uncategorized';
    }
}
