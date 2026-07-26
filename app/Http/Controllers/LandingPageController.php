<?php

namespace App\Http\Controllers;

use App\Models\LandingPageItem;
use App\Models\LandingPageSetting;

class LandingPageController extends Controller
{
    public function about()
    {
        return $this->show('about');
    }

    public function logistic()
    {
        return $this->show('information_logistic');
    }

    public function procurement()
    {
        return $this->show('information_procurement');
    }

    public function liveExport()
    {
        return $this->show('information_live_export');
    }

    public function futureProjects()
    {
        return $this->show('future_projects');
    }

    public function gallery()
    {
        return $this->show('gallery');
    }

    private function show(string $page)
    {
        $definition = config("landing_cms.$page");
        abort_unless($definition, 404);

        $settingModels = LandingPageSetting::forPage($page)->get()->keyBy('key');
        $settings = $settingModels->map(fn (LandingPageSetting $setting) => $setting->valueForLocale())->all();
        $assets = $settingModels->map(fn (LandingPageSetting $setting) => $setting->asset_url)->all();
        $collections = collect($definition['collections'])->mapWithKeys(
            fn (array $collection, string $section) => [
                $section => LandingPageItem::forSection($page, $section)
                    ->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            ]
        )->all();

        return view($definition['view'], array_merge([
            'pageTitle' => $definition['label'],
            'settings' => $settings,
            'assets' => $assets,
        ], $collections));
    }
}
