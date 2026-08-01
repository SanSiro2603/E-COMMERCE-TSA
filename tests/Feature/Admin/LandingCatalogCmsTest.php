<?php

namespace Tests\Feature\Admin;

use App\Models\LandingCatalogAnimal;
use App\Models\LandingCatalogAnimalImage;
use App\Models\LandingCatalogCategory;
use App\Models\LandingCatalogFamily;
use App\Models\LandingPageSetting;
use App\Models\User;
use Database\Seeders\LandingCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LandingCatalogCmsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(LandingCatalogSeeder::class);
    }

    public function test_public_catalog_uses_database_and_contains_no_commercial_fields(): void
    {
        $response = $this->get(route('landing.catalog'));
        $response->assertOk()->assertSee('Blue and Gold Macaw');
        $response->assertDontSee('Price')->assertDontSee('Availability')->assertDontSee('USD 2,850');

        $detail = $this->get(route('landing.catalog.show', 'blue-and-gold-macaw'));
        $detail->assertOk()->assertSee('Ara ararauna');
        $detail->assertDontSee('Price')->assertDontSee('Availability')->assertDontSee('USD 2,850');
    }

    public function test_catalog_keeps_category_query_and_stable_public_routes(): void
    {
        $this->get('/home/catalog?category=aves')->assertOk()->assertSee('categoryFromUrl');
        $this->get('/home/catalog/blue-and-gold-macaw')->assertOk();
    }

    public function test_public_detail_gallery_starts_with_main_image_and_deduplicates_sorted_images(): void
    {
        $animal = LandingCatalogAnimal::where('slug', 'blue-and-gold-macaw')->firstOrFail();
        $animal->images()->delete();

        $animal->images()->create([
            'image_path' => 'landing/catalog/gallery/last.jpg',
            'alt_en' => 'Last gallery image',
            'sort_order' => 20,
        ]);
        $animal->images()->create([
            'image_path' => $animal->main_image_path,
            'alt_en' => 'Duplicate main image',
            'sort_order' => 1,
        ]);
        $animal->images()->create([
            'image_path' => 'landing/catalog/gallery/first.jpg',
            'alt_en' => 'First gallery image',
            'sort_order' => 5,
        ]);

        $response = $this->get(route('landing.catalog.show', $animal->slug))
            ->assertOk()
            ->assertSee('aria-label="Show previous image"', false)
            ->assertSee('aria-label="Show next image"', false)
            ->assertSee('data-gallery-index="2"', false);
        $gallery = $response->viewData('product')['gallery'];

        $this->assertSame([
            $animal->main_image_url,
            '/storage/landing/catalog/gallery/first.jpg',
            '/storage/landing/catalog/gallery/last.jpg',
        ], array_column($gallery, 'url'));
        $this->assertSame([
            $animal->main_image_alt,
            'First gallery image',
            'Last gallery image',
        ], array_column($gallery, 'alt'));
    }

    public function test_public_detail_gallery_uses_only_main_image_when_no_extra_images_exist(): void
    {
        $animal = LandingCatalogAnimal::where('slug', 'blue-and-gold-macaw')->firstOrFail();
        $animal->images()->delete();

        $response = $this->get(route('landing.catalog.show', $animal->slug))
            ->assertOk()
            ->assertDontSee('aria-label="Show previous image"', false)
            ->assertDontSee('aria-label="Show next image"', false)
            ->assertDontSee('data-gallery-index="0"', false);
        $gallery = $response->viewData('product')['gallery'];

        $this->assertSame([[
            'url' => $animal->main_image_url,
            'alt' => $animal->main_image_alt,
        ]], $gallery);
    }

    public function test_public_catalog_respects_numeric_animal_order(): void
    {
        LandingCatalogAnimal::where('slug', 'blue-and-gold-macaw')->update(['sort_order' => 20]);
        LandingCatalogAnimal::where('slug', 'yellow-crested-cockatoo')->update(['sort_order' => 1]);

        $this->get(route('landing.catalog'))
            ->assertOk()
            ->assertSeeInOrder(['Yellow Crested Cockatoo', 'Blue and Gold Macaw']);
    }

    public function test_inactive_animal_category_or_family_is_hidden_and_detail_returns_404(): void
    {
        $animal = LandingCatalogAnimal::where('slug', 'blue-and-gold-macaw')->firstOrFail();
        $animal->update(['is_active' => false]);
        $this->get(route('landing.catalog'))->assertDontSee($animal->name_en);
        $this->get(route('landing.catalog.show', $animal->slug))->assertNotFound();

        $animal->update(['is_active' => true]);
        $animal->family->update(['is_active' => false]);
        $this->get(route('landing.catalog.show', $animal->slug))->assertNotFound();

        $animal->family->update(['is_active' => true]);
        $animal->category->update(['is_active' => false]);
        $this->get(route('landing.catalog.show', $animal->slug))->assertNotFound();
    }

    public function test_admin_and_super_admin_can_open_all_tabs_but_guest_and_buyer_cannot(): void
    {
        foreach (['admin', 'super_admin'] as $role) {
            $user = User::factory()->create(['role' => $role]);
            foreach (['content', 'categories', 'animals'] as $tab) {
                $this->actingAs($user)->withSession(['2fa_passed' => true])
                    ->get(route('admin.landing.catalog.index', ['tab' => $tab]))->assertOk();
            }
        }

        auth()->logout();
        $this->get(route('admin.landing.catalog.index'))->assertRedirect(route('login'));
        $buyer = User::factory()->create(['role' => 'pembeli']);
        $this->actingAs($buyer)->get(route('admin.landing.catalog.index'))->assertForbidden();
    }

    public function test_category_and_animal_slugs_do_not_change_when_names_are_edited(): void
    {
        Storage::fake('public');
        $admin = $this->admin();
        $this->actingAs($admin)->withSession(['2fa_passed' => true])->post(route('admin.landing.catalog.categories.store'), [
            'name_en' => 'Aquatic Animals', 'description_en' => 'Water species', 'image_alt_en' => 'Aquatic',
            'sort_order' => 9, 'is_active' => 1, 'image' => UploadedFile::fake()->image('aquatic.jpg'),
        ])->assertSessionHas('success');
        $category = LandingCatalogCategory::where('slug', 'aquatic-animals')->firstOrFail();
        $this->actingAs($admin)->withSession(['2fa_passed' => true])->put(route('admin.landing.catalog.categories.update', $category), [
            'name_en' => 'Water Animals', 'description_en' => 'Updated', 'image_alt_en' => 'Water', 'sort_order' => 9, 'is_active' => 1,
        ])->assertSessionHas('success');
        $this->assertSame('aquatic-animals', $category->fresh()->slug);

        $family = LandingCatalogFamily::create(['category_id' => $category->id, 'slug' => 'fish', 'name_en' => 'Fish', 'is_active' => true]);
        $this->actingAs($admin)->withSession(['2fa_passed' => true])->post(route('admin.landing.catalog.animals.store'), [
            'category_id' => $category->id, 'family_id' => $family->id, 'name_en' => 'Blue Fish', 'latin_name' => 'Piscis',
            'main_image_alt_en' => 'Blue fish', 'sort_order' => 1, 'is_active' => 1, 'main_image' => UploadedFile::fake()->image('fish.webp'),
        ])->assertSessionHas('success');
        $animal = LandingCatalogAnimal::where('slug', 'blue-fish')->firstOrFail();
        $payload = $this->animalPayload($animal, ['name_en' => 'Renamed Fish']);
        $this->actingAs($admin)->withSession(['2fa_passed' => true])->put(route('admin.landing.catalog.animals.update', $animal), $payload)->assertSessionHas('success');
        $this->assertSame('blue-fish', $animal->fresh()->slug);
    }

    public function test_admin_can_update_catalog_page_content(): void
    {
        Storage::fake('public');
        $payload = [
            'settings' => [
                'hero_eyebrow' => 'Catalog',
                'hero_title' => 'Updated Catalog',
                'hero_description' => 'Updated catalog description.',
                'hero_image_alt' => 'Catalog hero',
                'hero_position' => 'center center',
                'category_heading' => 'Choose a Category',
                'browse_heading' => 'Browse Animals',
                'all_label' => 'All Animals',
                'all_image_alt' => 'All catalog animals',
            ],
            'assets' => [
                'hero_image' => UploadedFile::fake()->image('catalog-hero.webp')->size(400),
            ],
        ];

        $this->actingAs($this->admin())->withSession(['2fa_passed' => true])
            ->post(route('admin.landing.pages.settings.update', 'catalog'), $payload)
            ->assertSessionHas('success');

        $this->assertDatabaseHas('landing_page_settings', [
            'page' => 'catalog',
            'key' => 'hero_title',
            'value_en' => 'Updated Catalog',
        ]);
        $imagePath = LandingPageSetting::where('page', 'catalog')->where('key', 'hero_image')->value('asset_path');
        Storage::disk('public')->assertExists($imagePath);
    }

    public function test_catalog_rejects_invalid_or_oversized_images_with_clear_errors(): void
    {
        Storage::fake('public');
        $session = $this->actingAs($this->admin())->withSession(['2fa_passed' => true]);

        $session->post(route('admin.landing.catalog.categories.store'), [
            'name_en' => 'Invalid Image Category',
            'image_alt_en' => 'Invalid image',
            'sort_order' => 1,
            'image' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('image');

        $animal = LandingCatalogAnimal::firstOrFail();
        $session->put(route('admin.landing.catalog.animals.update', $animal), $this->animalPayload($animal, [
            'main_image' => UploadedFile::fake()->image('too-large.jpg')->size(3073),
        ]))->assertSessionHasErrors('main_image');

        $this->assertDatabaseMissing('landing_catalog_categories', ['name_en' => 'Invalid Image Category']);
    }

    public function test_family_must_belong_to_the_selected_category(): void
    {
        Storage::fake('public');
        $animal = LandingCatalogAnimal::firstOrFail();
        $wrongFamily = LandingCatalogFamily::where('category_id', '!=', $animal->category_id)->firstOrFail();
        $payload = $this->animalPayload($animal, ['family_id' => $wrongFamily->id]);

        $this->actingAs($this->admin())->withSession(['2fa_passed' => true])
            ->put(route('admin.landing.catalog.animals.update', $animal), $payload)
            ->assertSessionHasErrors('family_id');
    }

    public function test_used_category_and_family_cannot_be_deleted(): void
    {
        $animal = LandingCatalogAnimal::firstOrFail();
        $session = $this->actingAs($this->admin())->withSession(['2fa_passed' => true]);
        $session->delete(route('admin.landing.catalog.categories.destroy', $animal->category))->assertSessionHasErrors('category');
        $session->delete(route('admin.landing.catalog.families.destroy', $animal->family))->assertSessionHasErrors('family');
        $this->assertDatabaseHas('landing_catalog_categories', ['id' => $animal->category_id]);
        $this->assertDatabaseHas('landing_catalog_families', ['id' => $animal->family_id]);
    }

    public function test_uploaded_files_are_replaced_and_animal_delete_cascades_gallery(): void
    {
        Storage::fake('public');
        $animal = LandingCatalogAnimal::firstOrFail();
        $session = $this->actingAs($this->admin())->withSession(['2fa_passed' => true]);
        $payload = $this->animalPayload($animal, ['main_image' => UploadedFile::fake()->image('main.jpg')]);
        $session->put(route('admin.landing.catalog.animals.update', $animal), $payload)->assertSessionHas('success');
        $mainPath = $animal->fresh()->main_image_path;
        Storage::disk('public')->assertExists($mainPath);
        $this->assertSame('/storage/'.$mainPath, $animal->fresh()->main_image_url);

        $session->post(route('admin.landing.catalog.images.store', $animal), [
            'image' => UploadedFile::fake()->image('gallery.webp'), 'alt_en' => 'Gallery photo', 'sort_order' => 2,
        ])->assertSessionHas('success');
        $image = $animal->images()->where('image_path', 'like', 'landing/%')->firstOrFail();
        Storage::disk('public')->assertExists($image->image_path);

        $session->delete(route('admin.landing.catalog.animals.destroy', $animal))->assertSessionHas('success');
        $this->assertDatabaseMissing('landing_catalog_animal_images', ['id' => $image->id]);
        Storage::disk('public')->assertMissing($mainPath);
        Storage::disk('public')->assertMissing($image->image_path);
    }

    public function test_gallery_image_is_scoped_to_its_animal_and_replacement_cleans_old_upload(): void
    {
        Storage::fake('public');
        $animal = LandingCatalogAnimal::firstOrFail();
        $otherAnimal = LandingCatalogAnimal::where('id', '!=', $animal->id)->firstOrFail();
        $session = $this->actingAs($this->admin())->withSession(['2fa_passed' => true]);

        $session->post(route('admin.landing.catalog.images.store', $animal), [
            'image' => UploadedFile::fake()->image('gallery-old.jpg'),
            'alt_en' => 'Old gallery image',
            'sort_order' => 1,
        ])->assertSessionHas('success');

        $image = $animal->images()->where('image_path', 'like', 'landing/%')->firstOrFail();
        $oldPath = $image->image_path;

        $session->put(route('admin.landing.catalog.images.update', [$otherAnimal, $image]), [
            'alt_en' => 'Wrong animal',
            'sort_order' => 1,
        ])->assertNotFound();

        $session->put(route('admin.landing.catalog.images.update', [$animal, $image]), [
            'image' => UploadedFile::fake()->image('gallery-new.webp'),
            'alt_en' => 'New gallery image',
            'sort_order' => 2,
        ])->assertSessionHas('success');

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($image->fresh()->image_path);
        $this->assertSame('New gallery image', $image->fresh()->alt_en);
    }

    public function test_seeded_public_and_external_assets_are_not_deleted(): void
    {
        $animal = LandingCatalogAnimal::where('slug', 'blue-and-gold-macaw')->firstOrFail();
        $publicAsset = public_path($animal->main_image_path);
        $this->assertFileExists($publicAsset);
        $this->actingAs($this->admin())->withSession(['2fa_passed' => true])->delete(route('admin.landing.catalog.animals.destroy', $animal));
        $this->assertFileExists($publicAsset);
    }

    public function test_catalog_seeder_is_idempotent_and_preserves_admin_changes(): void
    {
        $counts = [LandingCatalogCategory::count(), LandingCatalogFamily::count(), LandingCatalogAnimal::count(), LandingCatalogAnimalImage::count()];
        $animal = LandingCatalogAnimal::firstOrFail();
        $setting = LandingPageSetting::where('page', 'catalog')->where('key', 'hero_title')->firstOrFail();
        $animal->update(['name_en' => 'Admin Name']);
        $setting->update(['value_en' => 'Admin Catalog']);

        $this->seed(LandingCatalogSeeder::class);

        $this->assertSame($counts, [LandingCatalogCategory::count(), LandingCatalogFamily::count(), LandingCatalogAnimal::count(), LandingCatalogAnimalImage::count()]);
        $this->assertSame('Admin Name', $animal->fresh()->name_en);
        $this->assertSame('Admin Catalog', $setting->fresh()->value_en);
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function animalPayload(LandingCatalogAnimal $animal, array $overrides = []): array
    {
        return array_merge([
            'category_id' => $animal->category_id, 'family_id' => $animal->family_id, 'name_en' => $animal->name_en,
            'latin_name' => $animal->latin_name, 'main_image_alt_en' => $animal->main_image_alt_en,
            'description_en' => $animal->description_en, 'details_en' => $animal->details_en,
            'shipping_en' => $animal->shipping_en, 'care_en' => $animal->care_en, 'legal_en' => $animal->legal_en,
            'sort_order' => $animal->sort_order, 'is_active' => 1,
        ], $overrides);
    }
}
