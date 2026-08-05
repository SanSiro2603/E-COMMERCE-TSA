<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function isEnabled(string $key, bool $default = true): bool
    {
        try {
            $value = static::where('key', $key)->value('value');
        } catch (\Throwable) {
            return $default;
        }

        return $value === null ? $default : $value === '1';
    }
}
