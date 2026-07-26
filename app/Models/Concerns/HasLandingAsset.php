<?php

namespace App\Models\Concerns;

trait HasLandingAsset
{
    protected function assetUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return str_starts_with($path, 'landing/')
            ? '/storage/'.ltrim($path, '/')
            : asset($path);
    }

    protected function localized(?string $english, ?string $indonesian): ?string
    {
        return app()->getLocale() === 'id' && filled($indonesian) ? $indonesian : $english;
    }
}
