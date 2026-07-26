<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingCatalogAnimal;
use App\Models\LandingCatalogAnimalImage;
use App\Models\LandingCatalogCategory;
use App\Models\LandingCatalogFamily;
use App\Models\LandingPageSetting;
use App\Services\LandingSortOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class LandingCatalogController extends Controller
{
    public function __construct(private readonly LandingSortOrderService $sortOrder) {}

    public function index(Request $request)
    {
        $tab = in_array($request->query('tab'), ['content', 'categories', 'animals'], true)
            ? $request->query('tab') : 'content';

        return view('admin.landing.catalog', [
            'tab' => $tab,
            'definition' => config('landing_cms.catalog'),
            'settings' => LandingPageSetting::forPage('catalog')->get()->keyBy('key'),
            'categories' => LandingCatalogCategory::with(['families' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')])
                ->withCount('animals')->orderBy('sort_order')->orderBy('id')->get(),
            'families' => LandingCatalogFamily::with('category')->withCount('animals')->orderBy('sort_order')->orderBy('id')->get(),
            'animals' => LandingCatalogAnimal::with(['category', 'family', 'images' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')])
                ->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate($this->categoryRules(true), $this->validationMessages());
        $data['slug'] = $this->uniqueSlug(LandingCatalogCategory::class, $data['name_en']);
        $data['image_path'] = $request->file('image')->store('landing/catalog/categories', 'public');
        $data['is_active'] = $request->boolean('is_active');
        unset($data['image']);

        try {
            DB::transaction(function () use (&$data): void {
                $data['sort_order'] = $this->sortOrder->insert(
                    LandingCatalogCategory::query(),
                    (int) $data['sort_order']
                );
                LandingCatalogCategory::create($data);
            });
        } catch (Throwable $exception) {
            $this->deleteUploaded($data['image_path']);
            throw $exception;
        }

        return $this->back('categories', 'Kategori berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, LandingCatalogCategory $category)
    {
        $data = $request->validate($this->categoryRules(false), $this->validationMessages());
        $oldPath = $category->image_path;
        $newPath = null;
        if ($request->hasFile('image')) {
            $newPath = $request->file('image')->store('landing/catalog/categories', 'public');
            $data['image_path'] = $newPath;
        }
        $data['is_active'] = $request->boolean('is_active');
        unset($data['image']);

        try {
            DB::transaction(function () use (&$data, $category): void {
                $data['sort_order'] = $this->sortOrder->move(
                    LandingCatalogCategory::query(),
                    $category,
                    (int) $data['sort_order']
                );
                $category->update($data);
            });
        } catch (Throwable $exception) {
            $this->deleteUploaded($newPath);
            throw $exception;
        }

        if ($newPath) {
            $this->deleteUploaded($oldPath);
        }

        return $this->back('categories', 'Kategori berhasil diperbarui.');
    }

    public function toggleCategory(LandingCatalogCategory $category)
    {
        $category->update(['is_active' => ! $category->is_active]);

        return $this->back('categories', 'Status kategori diperbarui.');
    }

    public function destroyCategory(LandingCatalogCategory $category)
    {
        if ($category->families()->exists() || $category->animals()->exists()) {
            throw ValidationException::withMessages(['category' => 'Kategori masih digunakan. Nonaktifkan atau pindahkan family dan hewan terlebih dahulu.']);
        }
        $path = $category->image_path;
        $position = (int) $category->sort_order;
        DB::transaction(function () use ($category, $position): void {
            $category->delete();
            $this->sortOrder->remove(LandingCatalogCategory::query(), $position);
        });
        $this->deleteUploaded($path);

        return $this->back('categories', 'Kategori berhasil dihapus.');
    }

    public function storeFamily(Request $request)
    {
        $data = $request->validate($this->familyRules(), $this->validationMessages());
        $data['slug'] = $this->uniqueFamilySlug((int) $data['category_id'], $data['name_en']);
        $data['is_active'] = $request->boolean('is_active');
        DB::transaction(function () use (&$data): void {
            $data['sort_order'] = $this->sortOrder->insert(
                LandingCatalogFamily::where('category_id', $data['category_id']),
                (int) $data['sort_order']
            );
            LandingCatalogFamily::create($data);
        });

        return $this->back('categories', 'Family berhasil ditambahkan.');
    }

    public function updateFamily(Request $request, LandingCatalogFamily $family)
    {
        $data = $request->validate($this->familyRules(), $this->validationMessages());
        if ($family->animals()->exists() && (int) $data['category_id'] !== $family->category_id) {
            throw ValidationException::withMessages(['category_id' => 'Family yang masih digunakan tidak dapat dipindahkan. Pindahkan hewannya terlebih dahulu.']);
        }
        if ((int) $data['category_id'] !== $family->category_id
            && LandingCatalogFamily::where('category_id', $data['category_id'])->where('slug', $family->slug)->exists()) {
            throw ValidationException::withMessages(['category_id' => 'Kategori tujuan sudah memiliki family dengan slug yang sama.']);
        }
        $data['is_active'] = $request->boolean('is_active');
        $oldCategoryId = $family->category_id;
        $newCategoryId = (int) $data['category_id'];
        DB::transaction(function () use (&$data, $family, $oldCategoryId, $newCategoryId): void {
            if ($oldCategoryId === $newCategoryId) {
                $data['sort_order'] = $this->sortOrder->move(
                    LandingCatalogFamily::where('category_id', $oldCategoryId),
                    $family,
                    (int) $data['sort_order']
                );
            } else {
                $this->sortOrder->remove(
                    LandingCatalogFamily::where('category_id', $oldCategoryId),
                    (int) $family->sort_order
                );
                $data['sort_order'] = $this->sortOrder->insert(
                    LandingCatalogFamily::where('category_id', $newCategoryId),
                    (int) $data['sort_order']
                );
            }

            $family->update($data);
        });

        return $this->back('categories', 'Family berhasil diperbarui.');
    }

    public function toggleFamily(LandingCatalogFamily $family)
    {
        $family->update(['is_active' => ! $family->is_active]);

        return $this->back('categories', 'Status family diperbarui.');
    }

    public function destroyFamily(LandingCatalogFamily $family)
    {
        if ($family->animals()->exists()) {
            throw ValidationException::withMessages(['family' => 'Family masih digunakan. Nonaktifkan atau pindahkan hewan terlebih dahulu.']);
        }
        $categoryId = $family->category_id;
        $position = (int) $family->sort_order;
        DB::transaction(function () use ($family, $categoryId, $position): void {
            $family->delete();
            $this->sortOrder->remove(LandingCatalogFamily::where('category_id', $categoryId), $position);
        });

        return $this->back('categories', 'Family berhasil dihapus.');
    }

    public function storeAnimal(Request $request)
    {
        $data = $request->validate($this->animalRules(true), $this->validationMessages());
        $this->ensureFamilyCategory($data);
        $data['slug'] = $this->uniqueSlug(LandingCatalogAnimal::class, $data['name_en']);
        $data['main_image_path'] = $request->file('main_image')->store('landing/catalog/animals', 'public');
        $data['is_active'] = $request->boolean('is_active');
        unset($data['main_image']);

        try {
            DB::transaction(function () use (&$data): void {
                $data['sort_order'] = $this->sortOrder->insert(
                    LandingCatalogAnimal::query(),
                    (int) $data['sort_order']
                );
                LandingCatalogAnimal::create($data);
            });
        } catch (Throwable $exception) {
            $this->deleteUploaded($data['main_image_path']);
            throw $exception;
        }

        return $this->back('animals', 'Hewan berhasil ditambahkan.');
    }

    public function updateAnimal(Request $request, LandingCatalogAnimal $animal)
    {
        $data = $request->validate($this->animalRules(false), $this->validationMessages());
        $this->ensureFamilyCategory($data);
        $oldPath = $animal->main_image_path;
        $newPath = null;
        if ($request->hasFile('main_image')) {
            $newPath = $request->file('main_image')->store('landing/catalog/animals', 'public');
            $data['main_image_path'] = $newPath;
        }
        $data['is_active'] = $request->boolean('is_active');
        unset($data['main_image']);

        try {
            DB::transaction(function () use (&$data, $animal): void {
                $data['sort_order'] = $this->sortOrder->move(
                    LandingCatalogAnimal::query(),
                    $animal,
                    (int) $data['sort_order']
                );
                $animal->update($data);
            });
        } catch (Throwable $exception) {
            $this->deleteUploaded($newPath);
            throw $exception;
        }

        if ($newPath) {
            $this->deleteUploaded($oldPath);
        }

        return $this->back('animals', 'Hewan berhasil diperbarui.');
    }

    public function toggleAnimal(LandingCatalogAnimal $animal)
    {
        $animal->update(['is_active' => ! $animal->is_active]);

        return $this->back('animals', 'Status hewan diperbarui.');
    }

    public function destroyAnimal(LandingCatalogAnimal $animal)
    {
        $paths = $animal->images()->pluck('image_path')->push($animal->main_image_path);
        $position = (int) $animal->sort_order;
        DB::transaction(function () use ($animal, $position): void {
            $animal->delete();
            $this->sortOrder->remove(LandingCatalogAnimal::query(), $position);
        });
        $paths->each(fn ($path) => $this->deleteUploaded($path));

        return $this->back('animals', 'Hewan dan galerinya berhasil dihapus.');
    }

    public function storeImage(Request $request, LandingCatalogAnimal $animal)
    {
        $data = $request->validate($this->imageRules(true), $this->validationMessages());
        $data['image_path'] = $request->file('image')->store('landing/catalog/gallery', 'public');
        unset($data['image']);

        try {
            DB::transaction(function () use (&$data, $animal): void {
                $data['sort_order'] = $this->sortOrder->insert(
                    LandingCatalogAnimalImage::where('animal_id', $animal->id),
                    (int) $data['sort_order']
                );
                $animal->images()->create($data);
            });
        } catch (Throwable $exception) {
            $this->deleteUploaded($data['image_path']);
            throw $exception;
        }

        return $this->back('animals', 'Foto galeri berhasil ditambahkan.');
    }

    public function updateImage(Request $request, LandingCatalogAnimal $animal, LandingCatalogAnimalImage $image)
    {
        abort_unless($image->animal_id === $animal->id, 404);
        $data = $request->validate($this->imageRules(false), $this->validationMessages());
        $oldPath = $image->image_path;
        $newPath = null;
        if ($request->hasFile('image')) {
            $newPath = $request->file('image')->store('landing/catalog/gallery', 'public');
            $data['image_path'] = $newPath;
        }
        unset($data['image']);

        try {
            DB::transaction(function () use (&$data, $animal, $image): void {
                $data['sort_order'] = $this->sortOrder->move(
                    LandingCatalogAnimalImage::where('animal_id', $animal->id),
                    $image,
                    (int) $data['sort_order']
                );
                $image->update($data);
            });
        } catch (Throwable $exception) {
            $this->deleteUploaded($newPath);
            throw $exception;
        }

        if ($newPath) {
            $this->deleteUploaded($oldPath);
        }

        return $this->back('animals', 'Foto galeri berhasil diperbarui.');
    }

    public function destroyImage(LandingCatalogAnimal $animal, LandingCatalogAnimalImage $image)
    {
        abort_unless($image->animal_id === $animal->id, 404);
        $path = $image->image_path;
        $position = (int) $image->sort_order;
        DB::transaction(function () use ($animal, $image, $position): void {
            $image->delete();
            $this->sortOrder->remove(
                LandingCatalogAnimalImage::where('animal_id', $animal->id),
                $position
            );
        });
        $this->deleteUploaded($path);

        return $this->back('animals', 'Foto galeri berhasil dihapus.');
    }

    private function categoryRules(bool $creating): array
    {
        return ['name_en' => 'required|string|max:150', 'description_en' => 'nullable|string|max:2000', 'image_alt_en' => 'required|string|max:200', 'sort_order' => 'required|integer|min:1|max:65535', 'image' => ($creating ? 'required' : 'nullable').'|image|mimes:jpeg,jpg,png,webp|max:3072'];
    }

    private function familyRules(): array
    {
        return ['category_id' => 'required|exists:landing_catalog_categories,id', 'name_en' => 'required|string|max:150', 'sort_order' => 'required|integer|min:1|max:65535'];
    }

    private function animalRules(bool $creating): array
    {
        $rules = ['category_id' => 'required|exists:landing_catalog_categories,id', 'family_id' => 'required|exists:landing_catalog_families,id', 'name_en' => 'required|string|max:180', 'latin_name' => 'nullable|string|max:180', 'main_image_alt_en' => 'required|string|max:200', 'sort_order' => 'required|integer|min:1|max:65535', 'main_image' => ($creating ? 'required' : 'nullable').'|image|mimes:jpeg,jpg,png,webp|max:3072'];
        foreach (['description', 'details', 'shipping', 'care', 'legal'] as $field) {
            $rules[$field.'_en'] = 'nullable|string|max:5000';
        }

        return $rules;
    }

    private function imageRules(bool $creating): array
    {
        return ['alt_en' => 'required|string|max:200', 'sort_order' => 'required|integer|min:1|max:65535', 'image' => ($creating ? 'required' : 'nullable').'|image|mimes:jpeg,jpg,png,webp|max:3072'];
    }

    private function validationMessages(): array
    {
        return [
            'image.image' => 'File harus berupa gambar yang valid.',
            'image.mimes' => 'Gambar harus berformat JPEG, PNG, atau WebP.',
            'image.max' => 'Ukuran gambar maksimal 3 MB.',
            'main_image.image' => 'File gambar utama tidak valid.',
            'main_image.mimes' => 'Gambar utama harus berformat JPEG, PNG, atau WebP.',
            'main_image.max' => 'Ukuran gambar utama maksimal 3 MB.',
            'sort_order.min' => 'Urutan harus dimulai dari 1.',
        ];
    }

    private function ensureFamilyCategory(array $data): void
    {
        if (! LandingCatalogFamily::whereKey($data['family_id'])->where('category_id', $data['category_id'])->exists()) {
            throw ValidationException::withMessages(['family_id' => 'Family harus berasal dari kategori yang dipilih.']);
        }
    }

    private function uniqueSlug(string $model, string $name): string
    {
        $base = Str::slug($name) ?: 'item';
        $slug = $base;
        $suffix = 2;
        while ($model::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    private function uniqueFamilySlug(int $categoryId, string $name): string
    {
        $base = Str::slug($name) ?: 'family';
        $slug = $base;
        $suffix = 2;
        while (LandingCatalogFamily::where('category_id', $categoryId)->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    private function deleteUploaded(?string $path): void
    {
        if ($path && str_starts_with($path, 'landing/')) {
            Storage::disk('public')->delete($path);
        }
    }

    private function back(string $tab, string $message)
    {
        return redirect()->route('admin.landing.catalog.index', ['tab' => $tab])->with('success', $message);
    }
}
