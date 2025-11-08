<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 
        'name', 
        'slug', 
        'description', 
        'price',
        'stock', 
        'unit', // Tambahkan ini jika belum ada
        'image', 
        'images', 
        'weight',
        'is_active', 
        'health_certificate', 
        'available_from'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'weight' => 'decimal:2',
        'stock' => 'integer',
        'available_from' => 'date',
        'images' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}