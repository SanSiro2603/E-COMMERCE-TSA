<?php

namespace Database\Seeders;

use App\Models\HomeSlide;
use App\Models\HomeCatalogCard;
use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class LandingHomeSeeder extends Seeder
{
    public function run(): void
    {
        // ── Hero Slides ───────────────────────────────────────────────
        if (HomeSlide::count() === 0) {
            $slides = [
                [
                    'title_top'    => 'PT. Tunas Sejahtera',
                    'title_bottom' => 'Adhiperkasa',
                    'copy'         => 'We are a Breeding Company that focuses on Birds, Mammals, and Reptiles that supplies Domestic and International needs (Export-Import) with official permits and legality from the Indonesian government',
                    'image_path'   => 'images/about-banner-nicobar-pigeon.png',
                    'bg_position'  => '62% center',
                    'sort_order'   => 1,
                    'is_active'    => true,
                ],
                [
                    'title_top'    => 'PT. Tunas Sejahtera',
                    'title_bottom' => 'Adhiperkasa',
                    'copy'         => 'We are a Breeding Company that focuses on Birds, Mammals, and Reptiles that supplies Domestic and International needs (Export-Import) with official permits and legality from the Indonesian government',
                    'image_path'   => 'images/hero-iguana.jpeg',
                    'bg_position'  => '68% center',
                    'sort_order'   => 2,
                    'is_active'    => true,
                ],
                [
                    'title_top'    => 'PT. Tunas Sejahtera',
                    'title_bottom' => 'Adhiperkasa',
                    'copy'         => 'We are a Breeding Company that focuses on Birds, Mammals, and Reptiles that supplies Domestic and International needs (Export-Import) with official permits and legality from the Indonesian government',
                    'image_path'   => 'images/hero-macaw.jpeg',
                    'bg_position'  => '70% center',
                    'sort_order'   => 3,
                    'is_active'    => true,
                ],
            ];

            foreach ($slides as $slide) {
                HomeSlide::create($slide);
            }
        }

        // ── Catalog Cards ─────────────────────────────────────────────
        if (HomeCatalogCard::count() === 0) {
            $cards = [
                [
                    'title'       => 'Aves',
                    'description' => 'Beautiful and healthy birds with excellent care and certification.',
                    'image_path'  => 'images/nicobar-pigeon.png',
                    'catalog_key' => 'aves',
                    'sort_order'  => 1,
                    'is_active'   => true,
                ],
                [
                    'title'       => 'Mamalia',
                    'description' => 'High-quality mammals from trusted breeding and conservation programs.',
                    'image_path'  => 'images/binturong.png',
                    'catalog_key' => 'mammals',
                    'sort_order'  => 2,
                    'is_active'   => true,
                ],
                [
                    'title'       => 'Reptil',
                    'description' => 'Healthy and unique reptiles with excellent care and certification.',
                    'image_path'  => 'images/reptil.jpeg',
                    'catalog_key' => 'reptiles',
                    'sort_order'  => 3,
                    'is_active'   => true,
                ],
                [
                    'title'       => 'Hybrid & Mutation',
                    'description' => 'Special hybrid and mutation animals with rare and unique characteristics.',
                    'image_path'  => 'images/hybrid.jpeg',
                    'catalog_key' => 'hybrid',
                    'sort_order'  => 4,
                    'is_active'   => true,
                ],
            ];

            foreach ($cards as $card) {
                HomeCatalogCard::create($card);
            }
        }

        // ── System Settings ───────────────────────────────────────────
        $defaults = [
            'home_catalog_label'   => 'Our Catalog',
            'home_catalog_heading' => 'Explore Our Main Categories',
            'site_phone_1'         => '+62721 8050354',
            'site_phone_2'         => '+6282183948148',
            'site_email'           => 'pt.tsalampung@gmail.com',
            'site_address'         => 'JL. Raden Imba Kusumaratu, NO: 22, RT: 005, Lk.I, Sukadana Ham, Tanjung Karang Barat, Bandar Lampung, Lampung, Indonesia.',
            'social_facebook'      => '',
            'social_instagram'     => '',
            'social_youtube'       => '',
            'social_whatsapp'      => '6282183948148',
        ];

        foreach ($defaults as $key => $value) {
            SystemSetting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
