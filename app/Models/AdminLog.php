<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_name',
        'admin_email',
        'action',
        'module',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'severity',
        'latitude',
        'longitude',
        'ip_address',
        'device_type',
        'device_name',
        'operating_system',
        'browser',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
