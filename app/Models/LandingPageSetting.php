<?php

namespace App\Models;

use App\Models\Concerns\HasLandingAsset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LandingPageSetting extends Model
{
    use HasLandingAsset;

    protected $fillable = ['page', 'key', 'value_en', 'value_id', 'asset_path'];

    public function scopeForPage(Builder $query, string $page): Builder
    {
        return $query->where('page', $page);
    }

    public function valueForLocale(?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        return $locale === 'id' && filled($this->value_id)
            ? $this->value_id
            : $this->value_en;
    }

    public function getAssetUrlAttribute(): ?string
    {
        return $this->assetUrl($this->asset_path);
    }
}
