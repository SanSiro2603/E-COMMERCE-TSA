<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSlide;
use App\Models\HomeCatalogCard;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingHomeController extends Controller
{
    private const SETTINGS_KEYS = [
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
    ];

    public function index()
    {
        $slides       = HomeSlide::orderBy('sort_order')->orderBy('id')->get();
        $catalogCards = HomeCatalogCard::orderBy('sort_order')->orderBy('id')->get();
        $settings     = SystemSetting::whereIn('key', self::SETTINGS_KEYS)->pluck('value', 'key');

        return view('admin.landing.home', compact('slides', 'catalogCards', 'settings'));
    }

    // ── Slides ────────────────────────────────────────────────────────

    public function storeSlide(Request $request)
    {
        $data = $request->validate([
            'title_top'    => 'required|string|max:100',
            'title_bottom' => 'required|string|max:100',
            'copy'         => 'required|string|max:500',
            'image'        => 'required|image|mimes:jpeg,jpg,png,webp|max:3072',
            'bg_position'  => 'nullable|string|max:50',
            'sort_order'   => 'nullable|integer|min:0|max:255',
        ]);

        $path = $request->file('image')->store('landing/slides', 'public');

        HomeSlide::create([
            'title_top'    => $data['title_top'],
            'title_bottom' => $data['title_bottom'],
            'copy'         => $data['copy'],
            'image_path'   => $path,
            'bg_position'  => $data['bg_position'] ?? 'center center',
            'sort_order'   => $data['sort_order'] ?? 0,
            'is_active'    => true,
        ]);

        return back()->with('success', 'Slide berhasil ditambahkan.');
    }

    public function updateSlide(Request $request, HomeSlide $slide)
    {
        $data = $request->validate([
            'title_top'    => 'required|string|max:100',
            'title_bottom' => 'required|string|max:100',
            'copy'         => 'required|string|max:500',
            'image'        => 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072',
            'bg_position'  => 'nullable|string|max:50',
            'sort_order'   => 'nullable|integer|min:0|max:255',
        ]);

        $imagePath = $slide->image_path;

        if ($request->hasFile('image')) {
            if (str_starts_with($slide->image_path, 'landing/')) {
                Storage::disk('public')->delete($slide->image_path);
            }
            $imagePath = $request->file('image')->store('landing/slides', 'public');
        }

        $slide->update([
            'title_top'    => $data['title_top'],
            'title_bottom' => $data['title_bottom'],
            'copy'         => $data['copy'],
            'image_path'   => $imagePath,
            'bg_position'  => $data['bg_position'] ?? $slide->bg_position,
            'sort_order'   => $data['sort_order'] ?? $slide->sort_order,
        ]);

        return back()->with('success', 'Slide berhasil diperbarui.');
    }

    public function destroySlide(HomeSlide $slide)
    {
        if (str_starts_with($slide->image_path, 'landing/')) {
            Storage::disk('public')->delete($slide->image_path);
        }
        $slide->delete();

        return back()->with('success', 'Slide berhasil dihapus.');
    }

    public function toggleSlide(HomeSlide $slide)
    {
        $slide->update(['is_active' => !$slide->is_active]);

        return back()->with('success', 'Status slide diperbarui.');
    }

    // ── Catalog Cards ─────────────────────────────────────────────────

    public function storeCatalogCard(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'image'       => 'required|image|mimes:jpeg,jpg,png,webp|max:3072',
            'catalog_key' => 'required|string|max:50',
            'sort_order'  => 'nullable|integer|min:0|max:255',
        ]);

        $path = $request->file('image')->store('landing/catalog', 'public');

        HomeCatalogCard::create([
            'title'       => $data['title'],
            'description' => $data['description'],
            'image_path'  => $path,
            'catalog_key' => $data['catalog_key'],
            'sort_order'  => $data['sort_order'] ?? 0,
            'is_active'   => true,
        ]);

        return back()->with('success', 'Catalog card berhasil ditambahkan.');
    }

    public function updateCatalogCard(Request $request, HomeCatalogCard $card)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'image'       => 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072',
            'catalog_key' => 'required|string|max:50',
            'sort_order'  => 'nullable|integer|min:0|max:255',
        ]);

        $imagePath = $card->image_path;

        if ($request->hasFile('image')) {
            if (str_starts_with($card->image_path, 'landing/')) {
                Storage::disk('public')->delete($card->image_path);
            }
            $imagePath = $request->file('image')->store('landing/catalog', 'public');
        }

        $card->update([
            'title'       => $data['title'],
            'description' => $data['description'],
            'image_path'  => $imagePath,
            'catalog_key' => $data['catalog_key'],
            'sort_order'  => $data['sort_order'] ?? $card->sort_order,
        ]);

        return back()->with('success', 'Catalog card berhasil diperbarui.');
    }

    public function destroyCatalogCard(HomeCatalogCard $card)
    {
        if (str_starts_with($card->image_path, 'landing/')) {
            Storage::disk('public')->delete($card->image_path);
        }
        $card->delete();

        return back()->with('success', 'Catalog card berhasil dihapus.');
    }

    public function toggleCatalogCard(HomeCatalogCard $card)
    {
        $card->update(['is_active' => !$card->is_active]);

        return back()->with('success', 'Status card diperbarui.');
    }

    // ── Settings ──────────────────────────────────────────────────────

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'home_catalog_label'   => 'nullable|string|max:100',
            'home_catalog_heading' => 'nullable|string|max:200',
            'site_phone_1'         => 'nullable|string|max:30',
            'site_phone_2'         => 'nullable|string|max:30',
            'site_email'           => 'nullable|email|max:100',
            'site_address'         => 'nullable|string|max:500',
            'social_facebook'      => 'nullable|string|max:200',
            'social_instagram'     => 'nullable|string|max:200',
            'social_youtube'       => 'nullable|string|max:200',
            'social_whatsapp'      => 'nullable|string|max:30',
        ]);

        foreach ($data as $key => $value) {
            SystemSetting::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
