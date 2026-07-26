<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeCatalogCard;
use App\Models\HomeSlide;
use App\Models\SystemSetting;
use App\Services\LandingSortOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class LandingHomeController extends Controller
{
    public function __construct(private readonly LandingSortOrderService $sortOrder) {}

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
        $slides = HomeSlide::orderBy('sort_order')->orderBy('id')->get();
        $catalogCards = HomeCatalogCard::orderBy('sort_order')->orderBy('id')->get();
        $settings = SystemSetting::whereIn('key', self::SETTINGS_KEYS)->pluck('value', 'key');

        return view('admin.landing.home', compact('slides', 'catalogCards', 'settings'));
    }

    // ── Slides ────────────────────────────────────────────────────────

    public function storeSlide(Request $request)
    {
        $data = $request->validate([
            'title_top' => 'required|string|max:100',
            'title_bottom' => 'required|string|max:100',
            'copy' => 'required|string|max:500',
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:3072',
            'sort_order' => 'nullable|integer|min:1|max:255',
        ]);

        $path = $request->file('image')->store('landing/slides', 'public');

        try {
            DB::transaction(function () use ($data, $path): void {
                $position = $this->sortOrder->insert(
                    HomeSlide::query(),
                    (int) ($data['sort_order'] ?? HomeSlide::count() + 1)
                );
                HomeSlide::create([
                    'title_top' => $data['title_top'],
                    'title_bottom' => $data['title_bottom'],
                    'copy' => $data['copy'],
                    'image_path' => $path,
                    'bg_position' => 'center center',
                    'sort_order' => $position,
                    'is_active' => true,
                ]);
            });
        } catch (Throwable $exception) {
            $this->deleteUploaded($path);
            throw $exception;
        }

        return back()->with('success', 'Slide berhasil ditambahkan.');
    }

    public function updateSlide(Request $request, HomeSlide $slide)
    {
        $data = $request->validate([
            'title_top' => 'required|string|max:100',
            'title_bottom' => 'required|string|max:100',
            'copy' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072',
            'sort_order' => 'nullable|integer|min:1|max:255',
        ]);

        $oldPath = $slide->image_path;
        $imagePath = $oldPath;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('landing/slides', 'public');
        }

        try {
            DB::transaction(function () use ($data, $imagePath, $slide): void {
                $position = $this->sortOrder->move(
                    HomeSlide::query(),
                    $slide,
                    (int) ($data['sort_order'] ?? $slide->sort_order)
                );
                $slide->update([
                    'title_top' => $data['title_top'],
                    'title_bottom' => $data['title_bottom'],
                    'copy' => $data['copy'],
                    'image_path' => $imagePath,
                    'bg_position' => $slide->bg_position,
                    'sort_order' => $position,
                ]);
            });
        } catch (Throwable $exception) {
            if ($imagePath !== $oldPath) {
                $this->deleteUploaded($imagePath);
            }
            throw $exception;
        }

        if ($imagePath !== $oldPath) {
            $this->deleteUploaded($oldPath);
        }

        return back()->with('success', 'Slide berhasil diperbarui.');
    }

    public function destroySlide(HomeSlide $slide)
    {
        $path = $slide->image_path;
        $position = (int) $slide->sort_order;
        DB::transaction(function () use ($slide, $position): void {
            $slide->delete();
            $this->sortOrder->remove(HomeSlide::query(), $position);
        });
        $this->deleteUploaded($path);

        return back()->with('success', 'Slide berhasil dihapus.');
    }

    public function toggleSlide(HomeSlide $slide)
    {
        $slide->update(['is_active' => ! $slide->is_active]);

        return back()->with('success', 'Status slide diperbarui.');
    }

    // ── Catalog Cards ─────────────────────────────────────────────────

    public function storeCatalogCard(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:3072',
            'catalog_key' => 'required|string|max:50',
            'sort_order' => 'nullable|integer|min:1|max:255',
        ]);

        $path = $request->file('image')->store('landing/catalog', 'public');

        try {
            DB::transaction(function () use ($data, $path): void {
                $position = $this->sortOrder->insert(
                    HomeCatalogCard::query(),
                    (int) ($data['sort_order'] ?? HomeCatalogCard::count() + 1)
                );
                HomeCatalogCard::create([
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'image_path' => $path,
                    'catalog_key' => $data['catalog_key'],
                    'sort_order' => $position,
                    'is_active' => true,
                ]);
            });
        } catch (Throwable $exception) {
            $this->deleteUploaded($path);
            throw $exception;
        }

        return back()->with('success', 'Catalog card berhasil ditambahkan.');
    }

    public function updateCatalogCard(Request $request, HomeCatalogCard $card)
    {
        $data = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072',
            'catalog_key' => 'required|string|max:50',
            'sort_order' => 'nullable|integer|min:1|max:255',
        ]);

        $oldPath = $card->image_path;
        $imagePath = $oldPath;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('landing/catalog', 'public');
        }

        try {
            DB::transaction(function () use ($card, $data, $imagePath): void {
                $position = $this->sortOrder->move(
                    HomeCatalogCard::query(),
                    $card,
                    (int) ($data['sort_order'] ?? $card->sort_order)
                );
                $card->update([
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'image_path' => $imagePath,
                    'catalog_key' => $data['catalog_key'],
                    'sort_order' => $position,
                ]);
            });
        } catch (Throwable $exception) {
            if ($imagePath !== $oldPath) {
                $this->deleteUploaded($imagePath);
            }
            throw $exception;
        }

        if ($imagePath !== $oldPath) {
            $this->deleteUploaded($oldPath);
        }

        return back()->with('success', 'Catalog card berhasil diperbarui.');
    }

    public function destroyCatalogCard(HomeCatalogCard $card)
    {
        $path = $card->image_path;
        $position = (int) $card->sort_order;
        DB::transaction(function () use ($card, $position): void {
            $card->delete();
            $this->sortOrder->remove(HomeCatalogCard::query(), $position);
        });
        $this->deleteUploaded($path);

        return back()->with('success', 'Catalog card berhasil dihapus.');
    }

    public function toggleCatalogCard(HomeCatalogCard $card)
    {
        $card->update(['is_active' => ! $card->is_active]);

        return back()->with('success', 'Status card diperbarui.');
    }

    // ── Settings ──────────────────────────────────────────────────────

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'home_catalog_label' => 'nullable|string|max:100',
            'home_catalog_heading' => 'nullable|string|max:200',
            'site_phone_1' => 'nullable|string|max:30',
            'site_phone_2' => 'nullable|string|max:30',
            'site_email' => 'nullable|email|max:100',
            'site_address' => 'nullable|string|max:500',
            'social_facebook' => 'nullable|string|max:200',
            'social_instagram' => 'nullable|string|max:200',
            'social_youtube' => 'nullable|string|max:200',
            'social_whatsapp' => 'nullable|string|max:30',
        ]);

        foreach ($data as $key => $value) {
            SystemSetting::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    private function deleteUploaded(?string $path): void
    {
        if ($path && str_starts_with($path, 'landing/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
