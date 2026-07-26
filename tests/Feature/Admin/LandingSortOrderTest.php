<?php

namespace Tests\Feature\Admin;

use App\Models\HomeSlide;
use App\Models\LandingCatalogAnimal;
use App\Models\LandingCatalogAnimalImage;
use App\Models\LandingPageItem;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LandingSortOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        Storage::fake('public');
    }

    public function test_existing_landing_data_uses_one_based_contiguous_positions(): void
    {
        foreach (['home_slides', 'home_catalog_cards', 'landing_page_items', 'landing_catalog_categories', 'landing_catalog_families', 'landing_catalog_animals', 'landing_catalog_animal_images'] as $table) {
            $this->assertGreaterThanOrEqual(1, DB::table($table)->min('sort_order'), $table.' masih memiliki urutan 0.');
        }
    }

    public function test_generic_landing_item_insert_move_and_delete_shift_sibling_positions(): void
    {
        $session = $this->adminSession();
        $originalIds = LandingPageItem::forSection('future_projects', 'projects')
            ->orderBy('sort_order')->pluck('id')->all();

        $session->post(route('admin.landing.pages.items.store', ['future_projects', 'projects']), [
            'description_en' => 'Inserted project',
            'sort_order' => 1,
        ])->assertSessionHas('success');

        $inserted = LandingPageItem::where('description_en', 'Inserted project')->firstOrFail();
        $this->assertSame(1, $inserted->sort_order);
        $this->assertSame(2, LandingPageItem::findOrFail($originalIds[0])->sort_order);

        $session->put(route('admin.landing.pages.items.update', ['future_projects', 'projects', $inserted]), [
            'description_en' => 'Inserted project',
            'sort_order' => 3,
        ])->assertSessionHas('success');

        $this->assertSame(3, $inserted->fresh()->sort_order);
        $this->assertSame(1, LandingPageItem::findOrFail($originalIds[0])->sort_order);
        $this->assertSame(2, LandingPageItem::findOrFail($originalIds[1])->sort_order);

        $session->delete(route('admin.landing.pages.items.destroy', ['future_projects', 'projects', $inserted]))
            ->assertSessionHas('success');

        $this->assertSame(
            range(1, count($originalIds)),
            LandingPageItem::forSection('future_projects', 'projects')->orderBy('sort_order')->pluck('sort_order')->all()
        );
    }

    public function test_catalog_gallery_insert_at_one_shifts_existing_images(): void
    {
        $animal = LandingCatalogAnimal::whereHas('images')->firstOrFail();
        $firstImage = $animal->images()->orderBy('sort_order')->firstOrFail();

        $this->adminSession()->post(route('admin.landing.catalog.images.store', $animal), [
            'image' => UploadedFile::fake()->image('inserted-gallery.webp'),
            'alt_en' => 'Inserted gallery image',
            'sort_order' => 1,
        ])->assertSessionHas('success');

        $inserted = LandingCatalogAnimalImage::where('alt_en', 'Inserted gallery image')->firstOrFail();
        $this->assertSame(1, $inserted->sort_order);
        $this->assertSame(2, $firstImage->fresh()->sort_order);
    }

    public function test_home_slide_positions_shift_and_zero_is_rejected(): void
    {
        $session = $this->adminSession();
        $firstSlide = HomeSlide::orderBy('sort_order')->firstOrFail();
        $payload = [
            'title_top' => 'Inserted',
            'title_bottom' => 'Slide',
            'copy' => 'Inserted home slide.',
            'bg_position' => 'center center',
            'sort_order' => 1,
            'image' => UploadedFile::fake()->image('home-slide.jpg'),
        ];

        $session->post(route('admin.landing.home.slides.store'), $payload)->assertSessionHas('success');
        $inserted = HomeSlide::where('title_top', 'Inserted')->firstOrFail();
        $this->assertSame(1, $inserted->sort_order);
        $this->assertSame(2, $firstSlide->fresh()->sort_order);

        $payload['sort_order'] = 0;
        $payload['title_top'] = 'Invalid zero';
        $session->post(route('admin.landing.home.slides.store'), $payload)->assertSessionHasErrors('sort_order');
        $this->assertDatabaseMissing('home_slides', ['title_top' => 'Invalid zero']);
    }

    public function test_background_position_controls_are_hidden_and_alt_purpose_is_explained(): void
    {
        $session = $this->adminSession();

        $session->get(route('admin.landing.home.index'))
            ->assertOk()
            ->assertDontSee('Posisi Gambar');
        $session->get(route('admin.landing.about.index'))
            ->assertOk()
            ->assertDontSee('Posisi Background')
            ->assertSee('Tidak tampil sebagai caption');
        $session->get(route('admin.landing.catalog.index'))
            ->assertOk()
            ->assertDontSee('Posisi Background')
            ->assertSee('Tidak tampil sebagai caption');
    }

    private function adminSession(): self
    {
        $admin = User::factory()->create(['role' => 'admin']);

        return $this->actingAs($admin)->withSession(['2fa_passed' => true]);
    }
}
