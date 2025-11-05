<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'subtotal',
        'price',
    ];

    protected $with = ['product']; // Otomatis load product

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Cek apakah produk sudah ada di keranjang user
    public static function isInCart($userId, $productId)
    {
        return self::where('user_id', $userId)
                   ->where('product_id', $productId)
                   ->exists();
    }
}