<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_number',
        'subtotal',
        'shipping_cost',
        'grand_total',
        'status',
        'recipient_name',
        'recipient_phone',
        'shipping_address',
        'province',
        'province_id',
        'city',
        'city_id',
        'postal_code',
        'courier',
        'courier_service',
        'courier_service_description',
        'estimated_delivery',
        'tracking_number',
        'payment_method',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        if ($status && $status !== 'all') {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeRecent($query)
    {
        return $query->latest();
    }

    // Helpers
   public static function generateOrderNumber()
{
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}


    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'yellow',
            'paid' => 'blue',
            'processing' => 'purple',
            'shipped' => 'indigo',
            'completed' => 'green',
            'cancelled' => 'red',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'paid']);
    }

    public function canBeCompleted()
    {
        return $this->status === 'shipped';
    }

    /**
     * Hitung subtotal berdasarkan items
     */
    public function calculateSubtotal()
    {
        return $this->items->sum(fn($item) => $item->price * $item->quantity);
    }

    /**
     * Hitung grand total (subtotal + ongkir)
     */
    public function calculateGrandTotal()
    {
        return $this->calculateSubtotal() + $this->shipping_cost;
    }
}
