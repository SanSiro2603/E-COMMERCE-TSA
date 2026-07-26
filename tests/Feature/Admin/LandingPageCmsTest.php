<?php

namespace Tests\Feature\Admin;

use App\Models\LandingPageItem;
use App\Models\LandingPageSetting;
use App\Models\User;
use Database\Seeders\LandingPagesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LandingPageCmsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(LandingPagesSeeder::class);
    }

    public function test_public_landing_pages_render_seeded_database_content(): void
    {
        $pages = [
            'landing.about' => 'Our Leadership',
            'landing.information.logistic-delivery' => 'Air Freight Services',
            'landing.information.procurement-preparation' => 'Sources of Livestock',
            'landing.information.live-export-process' => 'Initial Consultation',
            'landing.future-projects' => 'Development Roadmap',
            'landing.gallery' => 'Photo Collection',
        ];

        foreach ($pages as $route => $content) {
            $this->get(route($route))->assertOk()->assertSee($content);
        }
    }

    public function test_indonesian_locale_falls_back_to_english_content(): void
    {
        LandingPageSetting::where('page', 'future_projects')
            ->where('key', 'section_heading')
            ->update(['value_en' => 'English Roadmap', 'value_id' => null]);

        $this->withSession(['locale' => 'id'])
            ->get(route('landing.future-projects'))
            ->assertOk()
            ->assertSee('English Roadmap');
    }

    public function test_public_collections_only_show_active_items_in_sort_order(): void
    {
        $projects = LandingPageItem::forSection('future_projects', 'projects')->orderBy('sort_order')->get();
        $projects[0]->update(['is_active' => false]);
        $projects[1]->update(['description_en' => 'Visible project', 'sort_order' => 1]);
        LandingPageItem::create([
            'page' => 'future_projects', 'section' => 'projects', 'item_key' => 'second-visible',
            'description_en' => 'Second visible project', 'sort_order' => 2, 'is_active' => true,
        ]);

        $response = $this->get(route('landing.future-projects'));
        $response->assertDontSee($projects[0]->description_en);
        $response->assertSeeInOrder(['Visible project', 'Second visible project']);
    }

    public function test_admin_and_super_admin_can_open_all_cms_pages(): void
    {
        $routes = [
            'admin.landing.about.index',
            'admin.landing.information.logistic.index',
            'admin.landing.information.procurement.index',
            'admin.landing.information.live-export.index',
            'admin.landing.future-projects.index',
            'admin.landing.gallery.index',
        ];

        foreach (['admin', 'super_admin'] as $role) {
            $user = User::factory()->create(['role' => $role]);
            foreach ($routes as $route) {
                $this->actingAs($user)->withSession(['2fa_passed' => true])->get(route($route))->assertOk();
            }
        }
    }

    public function test_guest_and_buyer_cannot_access_cms(): void
    {
        $this->get(route('admin.landing.about.index'))->assertRedirect(route('login'));

        $buyer = User::factory()->create(['role' => 'pembeli']);
        $this->actingAs($buyer)->get(route('admin.landing.about.index'))->assertForbidden();
    }

    public function test_admin_can_update_settings_and_replace_an_uploaded_asset(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $payload = $this->futureSettings();
        $payload['settings']['section_heading'] = 'Updated Roadmap';
        $payload['assets']['hero_image'] = UploadedFile::fake()->image('hero.webp', 1200, 600)->size(500);

        $this->actingAs($admin)->withSession(['2fa_passed' => true])
            ->post(route('admin.landing.pages.settings.update', 'future_projects'), $payload)
            ->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('landing_page_settings', [
            'page' => 'future_projects', 'key' => 'section_heading', 'value_en' => 'Updated Roadmap',
        ]);
        $path = LandingPageSetting::where('page', 'future_projects')->where('key', 'hero_image')->value('asset_path');
        Storage::disk('public')->assertExists($path);
    }

    public function test_setting_image_validation_rejects_oversized_files(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $payload = $this->futureSettings();
        $payload['assets']['hero_image'] = UploadedFile::fake()->image('large.jpg')->size(3073);

        $this->actingAs($admin)->withSession(['2fa_passed' => true])
            ->post(route('admin.landing.pages.settings.update', 'future_projects'), $payload)
            ->assertSessionHasErrors('assets.hero_image');
    }

    public function test_admin_can_create_update_toggle_and_delete_collection_items(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $session = $this->actingAs($admin)->withSession(['2fa_passed' => true]);

        $session->post(route('admin.landing.pages.items.store', ['gallery', 'items']), [
            'title_en' => 'New Gallery Photo', 'category' => 'Wildlife', 'alt' => 'New photo',
            'sort_order' => 10, 'image' => UploadedFile::fake()->image('gallery.jpg')->size(300),
        ])->assertSessionHas('success');

        $item = LandingPageItem::where('title_en', 'New Gallery Photo')->firstOrFail();
        Storage::disk('public')->assertExists($item->image_path);
        $originalPath = $item->image_path;

        $session->put(route('admin.landing.pages.items.update', ['gallery', 'items', $item]), [
            'title_en' => 'Updated Gallery Photo', 'category' => 'Facility', 'alt' => 'Updated photo',
            'sort_order' => 2, 'image' => UploadedFile::fake()->image('gallery-new.webp')->size(300),
        ])->assertSessionHas('success');
        Storage::disk('public')->assertMissing($originalPath);
        Storage::disk('public')->assertExists($item->fresh()->image_path);

        $session->patch(route('admin.landing.pages.items.toggle', ['gallery', 'items', $item]))
            ->assertSessionHas('success');
        $this->assertFalse($item->fresh()->is_active);

        $uploadedPath = $item->fresh()->image_path;
        $session->delete(route('admin.landing.pages.items.destroy', ['gallery', 'items', $item]))
            ->assertSessionHas('success');
        $this->assertDatabaseMissing('landing_page_items', ['id' => $item->id]);
        Storage::disk('public')->assertMissing($uploadedPath);
    }

    public function test_deleting_seeded_item_never_deletes_public_asset(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $item = LandingPageItem::forSection('gallery', 'items')->firstOrFail();
        $asset = public_path($item->image_path);
        $this->assertFileExists($asset);

        $this->actingAs($admin)->withSession(['2fa_passed' => true])
            ->delete(route('admin.landing.pages.items.destroy', ['gallery', 'items', $item]))
            ->assertSessionHas('success');

        $this->assertFileExists($asset);
    }

    public function test_item_cannot_be_mutated_through_another_page_or_section(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = LandingPageItem::forSection('future_projects', 'projects')->firstOrFail();

        $this->actingAs($admin)->withSession(['2fa_passed' => true])
            ->patch(route('admin.landing.pages.items.toggle', ['gallery', 'items', $project]))
            ->assertNotFound();
    }

    public function test_landing_pages_seeder_is_idempotent(): void
    {
        $settingsCount = LandingPageSetting::count();
        $itemsCount = LandingPageItem::count();
        $setting = LandingPageSetting::where('page', 'gallery')->where('key', 'section_heading')->firstOrFail();
        $item = LandingPageItem::forSection('future_projects', 'projects')->firstOrFail();
        $setting->update(['value_en' => 'Admin custom heading']);
        $item->update(['description_en' => 'Admin custom project']);

        $this->seed(LandingPagesSeeder::class);

        $this->assertSame($settingsCount, LandingPageSetting::count());
        $this->assertSame($itemsCount, LandingPageItem::count());
        $this->assertSame('Admin custom heading', $setting->fresh()->value_en);
        $this->assertSame('Admin custom project', $item->fresh()->description_en);
    }

    private function futureSettings(): array
    {
        return ['settings' => [
            'hero_eyebrow' => 'Future Projects',
            'hero_title' => 'Future Projects',
            'hero_description' => 'Future development plans.',
            'hero_image_alt' => 'Future project hero',
            'hero_position' => 'center center',
            'section_label' => 'Future Projects',
            'section_heading' => 'Development Roadmap',
        ], 'assets' => []];
    }
}
