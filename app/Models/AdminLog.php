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
        'description',
        'ip_address',
        'device_type',
        'device_name',
        'operating_system',
        'browser',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
