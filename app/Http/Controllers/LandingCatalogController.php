<?php

namespace App\Http\Controllers;

use App\Models\LandingCatalogAnimal;
use App\Models\LandingCatalogCategory;
use App\Models\LandingPageSetting;

class LandingCatalogController extends Controller
{
    public function index()
    {
        $settings = LandingPageSetting::forPage('catalog')->get()->keyBy('key');
        $categories = LandingCatalogCategory::active()
            ->with(['families' => fn ($query) => $query->active()->orderBy('sort_order')->orderBy('id')])
            ->orderBy('sort_order')->orderBy('id')->get();
        $animals = LandingCatalogAnimal::active()
            ->whereHas('category', fn ($query) => $query->active())
            ->whereHas('family', fn ($query) => $query->active())
            ->with(['category', 'family'])
            ->orderBy('sort_order')->orderBy('id')->get();

        return view('landing.catalog', [
            'pageTitle' => 'Catalog',
            'settings' => $settings,
            'mainCategories' => $categories->map(fn ($category) => [
                'key' => $category->slug,
                'name' => $category->name,
                'desc' => $category->description,
                'image' => $category->image_url,
                'alt' => $category->image_alt,
                'families' => $category->families->pluck('name')->values()->all(),
            ])->all(),
            'products' => $animals->map(fn ($animal) => [
                'slug' => $animal->slug,
                'name' => $animal->name,
                'latin' => $animal->latin_name,
                'category' => $animal->category->slug,
                'subcategory' => $animal->family->name,
                'image' => $animal->main_image_url,
                'alt' => $animal->main_image_alt,
            ])->all(),
        ]);
    }

    public function show(string $slug)
    {
        $animal = LandingCatalogAnimal::active()
            ->where('slug', $slug)
            ->whereHas('category', fn ($query) => $query->active())
            ->whereHas('family', fn ($query) => $query->active())
            ->with(['category', 'family', 'images' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')])
            ->firstOrFail();

        $gallery = $animal->images->map(fn ($image) => ['url' => $image->image_url, 'alt' => $image->alt])->values()->all();
        if ($gallery === []) {
            $gallery[] = ['url' => $animal->main_image_url, 'alt' => $animal->main_image_alt];
        }

        return view('landing.catalog-detail', [
            'pageTitle' => $animal->name,
            'product' => [
                'slug' => $animal->slug,
                'name' => $animal->name,
                'latin' => $animal->latin_name,
                'image' => $animal->main_image_url,
                'image_alt' => $animal->main_image_alt,
                'gallery' => $gallery,
                'subcategory' => $animal->family->name,
                'description' => $animal->localizedField('description'),
                'details' => $animal->localizedField('details'),
                'shipping' => $animal->localizedField('shipping'),
                'care' => $animal->localizedField('care'),
                'legal' => $animal->localizedField('legal'),
            ],
            'countries' => config('landing_catalog.countries'),
            'categoryName' => $animal->category->name,
        ]);
    }
}
