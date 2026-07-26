<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPageItem;
use App\Models\LandingPageSetting;
use App\Services\LandingSortOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class LandingPageController extends Controller
{
    public function __construct(private readonly LandingSortOrderService $sortOrder) {}

    public function index(string $page)
    {
        $definition = $this->definition($page);
        $settings = LandingPageSetting::forPage($page)->get()->keyBy('key');
        $collections = collect($definition['collections'])->mapWithKeys(
            fn (array $collection, string $section) => [
                $section => LandingPageItem::forSection($page, $section)
                    ->orderBy('sort_order')->orderBy('id')->get(),
            ]
        );

        return view('admin.landing.page', compact('page', 'definition', 'settings', 'collections'));
    }

    public function updateSettings(Request $request, string $page)
    {
        $definition = $this->definition($page);
        $fields = $this->settingFields($definition);
        $rules = [];

        foreach ($fields as $key => $field) {
            $rules[($field['type'] === 'image' ? 'assets.' : 'settings.').$key] =
                $field['type'] === 'image'
                    ? 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072'
                    : $field['rules'];
        }

        $data = $request->validate($rules);

        $newPaths = [];
        $oldPaths = [];

        try {
            DB::transaction(function () use ($data, $fields, $page, $request, &$newPaths, &$oldPaths): void {
                foreach ($fields as $key => $field) {
                    $setting = LandingPageSetting::firstOrNew(['page' => $page, 'key' => $key]);

                    if ($field['type'] === 'image') {
                        if ($request->hasFile("assets.$key")) {
                            $oldPaths[] = $setting->asset_path;
                            $setting->asset_path = $request->file("assets.$key")
                                ->store("landing/$page/settings", 'public');
                            $newPaths[] = $setting->asset_path;
                        }
                    } else {
                        $setting->value_en = data_get($data, "settings.$key");
                    }

                    $setting->save();
                }
            });
        } catch (Throwable $exception) {
            collect($newPaths)->each(fn ($path) => $this->deleteUploadedFile($path));
            throw $exception;
        }

        collect($oldPaths)->each(fn ($path) => $this->deleteUploadedFile($path));

        return back()->with('success', 'Konten halaman berhasil disimpan.');
    }

    public function storeItem(Request $request, string $page, string $section)
    {
        $collection = $this->collection($page, $section);
        $data = $request->validate($this->itemRules($collection, true), $this->orderValidationMessages());
        $payload = $this->itemPayload($data, $page, $section, Str::uuid()->toString());

        try {
            DB::transaction(function () use (&$payload, $page, $section): void {
                $payload['sort_order'] = $this->sortOrder->insert(
                    LandingPageItem::forSection($page, $section),
                    (int) $payload['sort_order']
                );
                LandingPageItem::create($payload);
            });
        } catch (Throwable $exception) {
            $this->deleteUploadedFile($payload['image_path']);
            throw $exception;
        }

        return back()->with('success', 'Item berhasil ditambahkan.');
    }

    public function updateItem(Request $request, string $page, string $section, LandingPageItem $item)
    {
        $this->ensureItemScope($item, $page, $section);
        $collection = $this->collection($page, $section);
        $data = $request->validate($this->itemRules($collection, false), $this->orderValidationMessages());
        $oldPath = $item->image_path;
        $payload = $this->itemPayload($data, $page, $section, $item->item_key, $item);
        $newPath = $payload['image_path'] !== $oldPath ? $payload['image_path'] : null;

        try {
            DB::transaction(function () use (&$payload, $item, $page, $section): void {
                $payload['sort_order'] = $this->sortOrder->move(
                    LandingPageItem::forSection($page, $section),
                    $item,
                    (int) $payload['sort_order']
                );
                $item->update($payload);
            });
        } catch (Throwable $exception) {
            $this->deleteUploadedFile($newPath);
            throw $exception;
        }

        if ($newPath) {
            $this->deleteUploadedFile($oldPath);
        }

        return back()->with('success', 'Item berhasil diperbarui.');
    }

    public function destroyItem(string $page, string $section, LandingPageItem $item)
    {
        $this->collection($page, $section);
        $this->ensureItemScope($item, $page, $section);
        $path = $item->image_path;
        $position = (int) $item->sort_order;
        DB::transaction(function () use ($item, $page, $section, $position): void {
            $item->delete();
            $this->sortOrder->remove(LandingPageItem::forSection($page, $section), $position);
        });
        $this->deleteUploadedFile($path);

        return back()->with('success', 'Item berhasil dihapus.');
    }

    public function toggleItem(string $page, string $section, LandingPageItem $item)
    {
        $this->collection($page, $section);
        $this->ensureItemScope($item, $page, $section);
        $item->update(['is_active' => ! $item->is_active]);

        return back()->with('success', 'Status item berhasil diperbarui.');
    }

    private function definition(string $page): array
    {
        return config("landing_cms.$page") ?? abort(404);
    }

    private function collection(string $page, string $section): array
    {
        $definition = $this->definition($page);

        return $definition['collections'][$section] ?? abort(404);
    }

    private function settingFields(array $definition): array
    {
        return collect($definition['settings'])
            ->flatMap(fn (array $group) => $group['fields'])
            ->all();
    }

    private function itemRules(array $collection, bool $creating): array
    {
        $fields = $collection['fields'];
        $rules = ['sort_order' => 'required|integer|min:1|max:65535'];

        if (in_array('title', $fields, true)) {
            $rules['title_en'] = 'required|string|max:255';
        }
        if (in_array('description', $fields, true)) {
            $rules['description_en'] = 'required|string|max:5000';
        }
        if (in_array('image', $fields, true)) {
            $rules['image'] = ($creating ? 'required' : 'nullable').'|image|mimes:jpeg,jpg,png,webp|max:3072';
        }
        if (in_array('category', $fields, true)) {
            $rules['category'] = 'required|string|max:100';
        }
        if (in_array('alt', $fields, true)) {
            $rules['alt'] = 'required|string|max:200';
        }

        return $rules;
    }

    private function orderValidationMessages(): array
    {
        return ['sort_order.min' => 'Urutan harus dimulai dari 1.'];
    }

    private function itemPayload(
        array $data,
        string $page,
        string $section,
        string $itemKey,
        ?LandingPageItem $existing = null
    ): array {
        $imagePath = $existing?->image_path;
        if (isset($data['image'])) {
            $imagePath = $data['image']->store("landing/$page/$section", 'public');
        }

        $metadata = $existing?->metadata ?? [];
        if (array_key_exists('category', $data)) {
            $metadata['category'] = $data['category'];
        }
        if (array_key_exists('alt', $data)) {
            $metadata['alt'] = $data['alt'];
        }

        return [
            'page' => $page,
            'section' => $section,
            'item_key' => $itemKey,
            'title_en' => $data['title_en'] ?? null,
            'description_en' => $data['description_en'] ?? null,
            'image_path' => $imagePath,
            'metadata' => $metadata ?: null,
            'sort_order' => $data['sort_order'],
        ];
    }

    private function ensureItemScope(LandingPageItem $item, string $page, string $section): void
    {
        abort_unless($item->page === $page && $item->section === $section, 404);
    }

    private function deleteUploadedFile(?string $path): void
    {
        if ($path && str_starts_with($path, 'landing/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
