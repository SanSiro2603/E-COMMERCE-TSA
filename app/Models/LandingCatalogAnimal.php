<?php

namespace App\Models;

use App\Models\Concerns\HasLandingAsset;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LandingCatalogAnimal extends Model
{
    use HasLandingAsset, LogsActivity;

    protected $fillable = ['category_id', 'family_id', 'slug', 'name_en', 'name_id', 'latin_name', 'main_image_path', 'main_image_alt_en', 'main_image_alt_id', 'description_en', 'description_id', 'details_en', 'details_id', 'shipping_en', 'shipping_id', 'care_en', 'care_id', 'legal_en', 'legal_id', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(LandingCatalogCategory::class, 'category_id');
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(LandingCatalogFamily::class, 'family_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(LandingCatalogAnimalImage::class, 'animal_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getNameAttribute(): ?string
    {
        return $this->localized($this->name_en, $this->name_id);
    }

    public function getMainImageAltAttribute(): ?string
    {
        return $this->localized($this->main_image_alt_en, $this->main_image_alt_id);
    }

    public function getMainImageUrlAttribute(): ?string
    {
        return $this->assetUrl($this->main_image_path);
    }

    public function localizedField(string $field): ?string
    {
        return $this->localized($this->{$field.'_en'}, $this->{$field.'_id'});
    }
}
