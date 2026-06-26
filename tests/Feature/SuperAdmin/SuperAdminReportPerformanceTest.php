<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\Address;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SuperAdminReportPerformanceTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superAdmin = User::factory()->create(['role' => 'super_admin']);
    }

    // =========================================================================
    // RESPONSE TIME
    // =========================================================================

    public function test_superadmin_report_index_responds_within_time_limit(): void
    {
        $this->seedOrders(20);

        $start = microtime(true);
        $response = $this->actingAs($this->superAdmin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.reports.index'));
        $elapsed = (microtime(true) - $start) * 1000;

        $response->assertOk();
        $this->assertLessThan(3000, $elapsed,
            "Laporan SuperAdmin terlalu lambat: {$elapsed}ms (batas 3000ms)");
    }

    public function test_superadmin_report_pdf_export_responds_within_time_limit(): void
    {
        $this->seedOrders(20, ['status' => 'completed']);

        $start = microtime(true);
        $response = $this->actingAs($this->superAdmin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.reports.exportPdf'));
        $elapsed = (microtime(true) - $start) * 1000;

        $response->assertOk();
        $this->assertLessThan(10000, $elapsed,
            "Export PDF SuperAdmin terlalu lambat: {$elapsed}ms (batas 10000ms)");
    }

    public function test_superadmin_report_with_all_filters_responds_within_time_limit(): void
    {
        $orders = $this->seedOrders(20);
        $categoryId = Category::where('slug', 'aves')->value('id');

        $start = microtime(true);
        $response = $this->actingAs($this->superAdmin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.reports.index', [
                'start_date'     => now()->startOfMonth()->format('Y-m-d'),
                'end_date'       => now()->format('Y-m-d'),
                'province'       => 'Jawa Barat',
                'category_id'    => $categoryId,
                'payment_method' => 'bank_transfer',
            ]));
        $elapsed = (microtime(true) - $start) * 1000;

        $response->assertOk();
        $this->assertLessThan(3000, $elapsed,
            "Laporan dengan semua filter terlalu lambat: {$elapsed}ms (batas 3000ms)");
    }

    // =========================================================================
    // QUERY COUNT — deteksi N+1
    // =========================================================================

    public function test_superadmin_report_index_query_count_does_not_grow_with_data(): void
    {
        $this->seedOrders(20);

        DB::enableQueryLog();
        $this->actingAs($this->superAdmin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.reports.index'))
            ->assertOk();
        $queryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        $this->assertLessThan(30, $queryCount,
            "Laporan SuperAdmin N+1: {$queryCount} query untuk 20 pesanan (batas 30)");
    }

    public function test_superadmin_report_with_province_filter_query_count(): void
    {
        $this->seedOrders(20);

        DB::enableQueryLog();
        $this->actingAs($this->superAdmin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.reports.index', ['province' => 'Jawa Barat']))
            ->assertOk();
        $queryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        $this->assertLessThan(30, $queryCount,
            "Filter provinsi N+1: {$queryCount} query (batas 30)");
    }

    public function test_superadmin_report_pdf_export_query_count(): void
    {
        $this->seedOrders(20, ['status' => 'completed']);

        DB::enableQueryLog();
        $this->actingAs($this->superAdmin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.reports.exportPdf'))
            ->assertOk();
        $queryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        $this->assertLessThan(20, $queryCount,
            "PDF export SuperAdmin N+1: {$queryCount} query untuk 20 pesanan (batas 20)");
    }

    // =========================================================================
    // MEMORY USAGE
    // =========================================================================

    public function test_superadmin_report_index_memory_usage_within_limit(): void
    {
        $this->seedOrders(20);

        $memBefore = memory_get_usage(true);
        $this->actingAs($this->superAdmin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.reports.index'))
            ->assertOk();
        $memUsedMB = (memory_get_usage(true) - $memBefore) / 1024 / 1024;

        $this->assertLessThan(50, $memUsedMB,
            "Memory laporan SuperAdmin terlalu besar: {$memUsedMB}MB (batas 50MB)");
    }

    public function test_superadmin_report_pdf_export_memory_usage_within_limit(): void
    {
        $this->seedOrders(20, ['status' => 'completed']);

        $memBefore = memory_get_usage(true);
        $this->actingAs($this->superAdmin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.reports.exportPdf'))
            ->assertOk();
        $memUsedMB = (memory_get_usage(true) - $memBefore) / 1024 / 1024;

        $this->assertLessThan(100, $memUsedMB,
            "Memory PDF export SuperAdmin terlalu besar: {$memUsedMB}MB (batas 100MB)");
    }

    // =========================================================================
    // HELPER
    // =========================================================================

    private function seedOrders(int $count, array $overrides = []): array
    {
        $category = Category::firstOrCreate(
            ['slug' => 'aves'],
            ['name' => 'Aves', 'is_active' => true]
        );

        $orders = [];
        for ($i = 0; $i < $count; $i++) {
            $user = User::factory()->create(['role' => 'pembeli']);
            $product = Product::create([
                'category_id' => $category->id,
                'name'        => 'Produk ' . $i,
                'slug'        => 'produk-' . $i . '-' . uniqid(),
                'description' => 'Deskripsi',
                'price'       => 500000,
                'stock'       => 10,
                'weight'      => 1000,
                'is_active'   => true,
            ]);
            $address = Address::create([
                'user_id'         => $user->id,
                'label'           => 'Rumah',
                'recipient_name'  => 'Pembeli ' . $i,
                'recipient_phone' => '08123456789' . $i,
                'province_id'     => '1',
                'province_name'   => 'Jawa Barat',
                'city_id'         => '1',
                'city_name'       => 'Bandung',
                'city_type'       => 'Kota',
                'postal_code'     => '40123',
                'full_address'    => 'Jl. Test No. ' . $i,
                'is_default'      => true,
            ]);
            $order = Order::create(array_merge([
                'user_id'         => $user->id,
                'address_id'      => $address->id,
                'order_number'    => Order::generateOrderNumber(),
                'subtotal'        => 500000,
                'shipping_cost'   => 20000,
                'grand_total'     => 520000,
                'status'          => 'completed',
                'payment_method'  => 'bank_transfer',
                'courier'         => 'jne',
                'courier_service' => 'reg',
                'paid_at'         => now(),
            ], $overrides));
            $order->shippingSnapshot()->create([
                'label'           => $address->label,
                'recipient_name'  => $address->recipient_name,
                'recipient_phone' => $address->recipient_phone,
                'province_id'     => $address->province_id,
                'province_name'   => $address->province_name,
                'city_id'         => $address->city_id,
                'city_name'       => $address->city_name,
                'city_type'       => $address->city_type,
                'postal_code'     => $address->postal_code,
                'full_address'    => $address->full_address,
            ]);
            OrderItem::create([
                'order_id'              => $order->id,
                'product_id'            => $product->id,
                'product_name'          => $product->name,
                'product_image'         => null,
                'product_category_name' => $category->name,
                'quantity'              => 2,
                'price'                 => 500000,
                'subtotal'              => 1000000,
            ]);
            $orders[] = $order;
        }

        return $orders;
    }
}
