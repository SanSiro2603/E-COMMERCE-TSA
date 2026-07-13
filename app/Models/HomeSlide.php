<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HomeSlide extends Model
{
    protected $fillable = [
        'title_top', 'title_bottom', 'copy',
        'image_path', 'bg_position', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'landing/')) {
            return Storage::url($this->image_path);
        }
        return asset($this->image_path);
    }
}
