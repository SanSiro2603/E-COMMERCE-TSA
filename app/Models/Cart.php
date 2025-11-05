<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'product_id', 'quantity', 'subtotal', 'price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    
    public function carts()
{
    return $this->hasMany(Cart::class);
}

// Check if product is in user's cart
public function isInCart($userId)
{
    return $this->carts()->where('user_id', $userId)->exists();
}
}