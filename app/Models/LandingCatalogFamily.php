<?php

namespace App\Models;

use App\Models\Concerns\HasLandingAsset;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LandingCatalogFamily extends Model
{
    use HasLandingAsset, LogsActivity;

    protected $fillable = ['category_id', 'slug', 'name_en', 'name_id', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(LandingCatalogCategory::class, 'category_id');
    }

    public function animals(): HasMany
    {
        return $this->hasMany(LandingCatalogAnimal::class, 'family_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getNameAttribute(): ?string
    {
        return $this->localized($this->name_en, $this->name_id);
    }
}
