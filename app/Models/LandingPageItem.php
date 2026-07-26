<?php

namespace App\Models;

use App\Models\Concerns\HasLandingAsset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LandingPageItem extends Model
{
    use HasLandingAsset;

    protected $fillable = [
        'page', 'section', 'item_key', 'title_en', 'title_id',
        'description_en', 'description_id', 'image_path', 'metadata',
        'sort_order', 'is_active',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeForSection(Builder $query, string $page, string $section): Builder
    {
        return $query->where('page', $page)->where('section', $section);
    }

    public function titleForLocale(?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        return $locale === 'id' && filled($this->title_id) ? $this->title_id : $this->title_en;
    }

    public function descriptionForLocale(?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        return $locale === 'id' && filled($this->description_id)
            ? $this->description_id
            : $this->description_en;
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->assetUrl($this->image_path);
    }
}
