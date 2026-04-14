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
        'unit',
        'image', 
        'images', 
        'weight',
        'is_active', 
        'is_featured',
        'health_certificate', 
        'available_from'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'float',
        'weight' => 'float',
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