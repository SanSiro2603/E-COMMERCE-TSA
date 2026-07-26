<?php

namespace App\Models;

use App\Models\Concerns\HasLandingAsset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingCatalogAnimalImage extends Model
{
    use HasLandingAsset;

    protected $fillable = ['animal_id', 'image_path', 'alt_en', 'alt_id', 'sort_order'];

    public function animal(): BelongsTo
    {
        return $this->belongsTo(LandingCatalogAnimal::class, 'animal_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->assetUrl($this->image_path);
    }

    public function getAltAttribute(): ?string
    {
        return $this->localized($this->alt_en, $this->alt_id);
    }
}
