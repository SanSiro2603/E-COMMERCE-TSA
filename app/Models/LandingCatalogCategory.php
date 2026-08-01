<?php

namespace App\Models;

use App\Models\Concerns\HasLandingAsset;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LandingCatalogCategory extends Model
{
    use HasLandingAsset, LogsActivity;

    protected $fillable = ['slug', 'name_en', 'name_id', 'description_en', 'description_id', 'image_path', 'image_alt_en', 'image_alt_id', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function families(): HasMany
    {
        return $this->hasMany(LandingCatalogFamily::class, 'category_id');
    }

    public function animals(): HasMany
    {
        return $this->hasMany(LandingCatalogAnimal::class, 'category_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getNameAttribute(): ?string
    {
        return $this->localized($this->name_en, $this->name_id);
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->localized($this->description_en, $this->description_id);
    }

    public function getImageAltAttribute(): ?string
    {
        return $this->localized($this->image_alt_en, $this->image_alt_id);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->assetUrl($this->image_path);
    }
}
