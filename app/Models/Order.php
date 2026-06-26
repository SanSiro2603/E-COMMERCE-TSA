<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Model utama pesanan — tabel: orders
// Relasi: users, order_items, payments, shipments, addresses, order_shipping_snapshots
class Order extends Model
{
    use HasFactory, SoftDeletes;

    // [+] Tambah nama kolom baru di sini jika ada kolom baru di migration
    protected $fillable = [
        'user_id',
        'address_id',
        'order_number',
        'subtotal',
        'shipping_cost',
        'grand_total',
        'status',           // enum: pending | paid | processing | shipped | completed | cancelled
        'courier',
        'courier_service',
        'tracking_number',
        'biteship_order_id',
        'payment_method',
        'paid_at',
        'shipped_at',
        'cancelled_at',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'subtotal'     => 'decimal:2',
        'shipping_cost'=> 'decimal:2',
        'grand_total'  => 'decimal:2',
        'paid_at'      => 'datetime',
        'shipped_at'   => 'datetime',
        'cancelled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // === RELATIONSHIPS ===

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id');
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class, 'order_id');
    }

    public function shippingSnapshot()
    {
        return $this->hasOne(OrderShippingSnapshot::class);
    }

    public function address()
    {
        return $this->belongsTo(\App\Models\Address::class);
    }

    // === SCOPES ===

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

    // === HELPERS ===

    public static function generateOrderNumber()
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    // Accessor: $order->status_label — label status dalam Bahasa Indonesia
    // [+] Tambah entri di $labels jika ada status baru
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending'    => 'Menunggu Pembayaran',
            'paid'       => 'Sudah Dibayar',
            'processing' => 'Diproses',
            'shipped'    => 'Dikirim',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    // Accessor: $order->status_color — nama warna Tailwind sesuai status
    // [+] Tambah entri di $colors jika ada status baru
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending'    => 'yellow',
            'paid'       => 'blue',
            'processing' => 'purple',
            'shipped'    => 'indigo',
            'completed'  => 'green',
            'cancelled'  => 'red',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    public function calculateSubtotal()
    {
        return $this->items->sum(fn($item) => $item->price * $item->quantity);
    }

    public function calculateGrandTotal()
    {
        return $this->calculateSubtotal() + $this->shipping_cost;
    }

    public function getDisplayShippingRecipientNameAttribute(): ?string
    {
        return $this->shippingSnapshot?->recipient_name ?? $this->address?->recipient_name;
    }

    public function getDisplayShippingRecipientPhoneAttribute(): ?string
    {
        return $this->shippingSnapshot?->recipient_phone ?? $this->address?->recipient_phone;
    }

    public function getDisplayShippingFullAddressAttribute(): ?string
    {
        return $this->shippingSnapshot?->full_address ?? $this->address?->full_address;
    }

    public function getDisplayShippingProvinceNameAttribute(): ?string
    {
        return $this->shippingSnapshot?->province_name ?? $this->address?->province_name;
    }

    public function getDisplayShippingCityNameAttribute(): ?string
    {
        return $this->shippingSnapshot?->city_name ?? $this->address?->city_name;
    }

    public function getDisplayShippingCityTypeAttribute(): ?string
    {
        return $this->shippingSnapshot?->city_type ?? $this->address?->city_type;
    }

    public function getDisplayShippingPostalCodeAttribute(): ?string
    {
        return $this->shippingSnapshot?->postal_code ?? $this->address?->postal_code;
    }

    public function getDisplayShippingCityLineAttribute(): string
    {
        $city = trim(implode(' ', array_filter([
            $this->display_shipping_city_type,
            $this->display_shipping_city_name,
        ])));

        $location = trim(implode(', ', array_filter([
            $city,
            $this->display_shipping_province_name,
        ])));

        return trim($location . ($this->display_shipping_postal_code ? ' ' . $this->display_shipping_postal_code : ''));
    }

    public function hasCompleteShippingAddress(): bool
    {
        return filled($this->display_shipping_recipient_name)
            && filled($this->display_shipping_recipient_phone)
            && filled($this->display_shipping_full_address)
            && filled($this->display_shipping_province_name)
            && filled($this->display_shipping_city_name)
            && filled($this->display_shipping_postal_code);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'paid'])
            && is_null($this->cancelled_at)
            && is_null($this->shipped_at);
    }

    public function canBeCompleted(): bool
    {
        return in_array($this->status, ['processing', 'shipped']) && !$this->completed_at;
    }
}
