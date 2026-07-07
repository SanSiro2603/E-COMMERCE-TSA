<?php

namespace App\Http\Controllers;

use App\Models\HomeSlide;
use App\Models\HomeCatalogCard;
use App\Models\SystemSetting;
class DashboardController extends Controller
{
    public function index()
    {
        $slides = HomeSlide::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $catalogCards = HomeCatalogCard::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $settings = SystemSetting::whereIn('key', [
            'home_catalog_label',
            'home_catalog_heading',
            'site_phone_1',
            'site_phone_2',
            'site_email',
            'site_address',
            'social_facebook',
            'social_instagram',
            'social_youtube',
            'social_whatsapp',
        ])->pluck('value', 'key');

        return view('landing.home', compact('slides', 'catalogCards', 'settings'));
    }

    public function hewan()
    {
        return view('gallery_hewan');
    }
}
