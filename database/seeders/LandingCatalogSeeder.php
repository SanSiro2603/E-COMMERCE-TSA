<?php

namespace Database\Seeders;

use App\Models\LandingCatalogAnimal;
use App\Models\LandingCatalogAnimalImage;
use App\Models\LandingCatalogCategory;
use App\Models\LandingCatalogFamily;
use App\Models\LandingPageSetting;
use App\Support\LandingCatalogData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LandingCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'hero_eyebrow' => ['value_en' => 'Catalog'],
            'hero_title' => ['value_en' => 'Our Catalog'],
            'hero_description' => ['value_en' => 'Explore our wide range of animals from trusted breeding and conservation programs'],
            'hero_image' => ['asset_path' => 'images/catalog-banner.png'],
            'hero_image_alt' => ['value_en' => 'TSA animal catalog'],
            'hero_position' => ['value_en' => 'center -26px'],
            'category_heading' => ['value_en' => 'Explore by Main Category'],
            'browse_heading' => ['value_en' => 'Browse Our Animals'],
            'all_label' => ['value_en' => 'All'],
            'all_image' => ['asset_path' => 'images/semua.jpeg'],
            'all_image_alt' => ['value_en' => 'All animals'],
        ];

        foreach ($settings as $key => $values) {
            LandingPageSetting::firstOrCreate(['page' => 'catalog', 'key' => $key], $values);
        }

        $categories = [];
        $families = [];
        foreach (LandingCatalogData::categories() as $categoryOrder => $source) {
            $category = LandingCatalogCategory::firstOrCreate(
                ['slug' => $source['key']],
                [
                    'name_en' => $source['name'],
                    'description_en' => $source['desc'],
                    'image_path' => $this->storedPath($source['image']),
                    'image_alt_en' => $source['name'],
                    'sort_order' => $categoryOrder + 1,
                    'is_active' => true,
                ]
            );
            $categories[$source['key']] = $category;

            foreach ($source['families'] as $familyOrder => $name) {
                $family = LandingCatalogFamily::firstOrCreate(
                    ['category_id' => $category->id, 'slug' => Str::slug($name)],
                    ['name_en' => $name, 'sort_order' => $familyOrder + 1, 'is_active' => true]
                );
                $families[$source['key'].'|'.$name] = $family;
            }
        }

        foreach (LandingCatalogData::products() as $animalOrder => $source) {
            $category = $categories[$source['category']];
            $family = $families[$source['category'].'|'.$source['subcategory']];
            $other = $source['other'] ?? null;
            $animal = LandingCatalogAnimal::firstOrCreate(
                ['slug' => $source['slug']],
                [
                    'category_id' => $category->id,
                    'family_id' => $family->id,
                    'name_en' => $source['name'],
                    'latin_name' => $source['latin'],
                    'main_image_path' => $this->storedPath($source['image']),
                    'main_image_alt_en' => $source['name'],
                    'description_en' => $source['description'] ?? null,
                    'details_en' => $source['details'] ?? null,
                    'shipping_en' => $source['shipping'] ?? null,
                    'care_en' => $source['care'] ?? $other,
                    'legal_en' => $source['legal'] ?? $other,
                    'sort_order' => $animalOrder + 1,
                    'is_active' => true,
                ]
            );

            foreach (($source['gallery'] ?? []) as $imageOrder => $image) {
                LandingCatalogAnimalImage::firstOrCreate(
                    ['animal_id' => $animal->id, 'image_path' => $this->storedPath($image)],
                    ['alt_en' => $source['name'].' image '.($imageOrder + 1), 'sort_order' => $imageOrder + 1]
                );
            }
        }
    }

    private function storedPath(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);

        return str_starts_with($url, asset('/')) && $path ? ltrim($path, '/') : $url;
    }
}
