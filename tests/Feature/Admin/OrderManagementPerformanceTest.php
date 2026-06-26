<?php

namespace Tests\Feature\Admin;

use App\Models\Address;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class OrderManagementPerformanceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    // =========================================================================
    // RESPONSE TIME
    // =========================================================================

    public function test_orders_index_responds_within_time_limit(): void
    {
        $this->seedOrders(20);

        $start = microtime(true);
        $response = $this->actingAs($this->admin)
            ->withSession(['2fa_passed' => true])
            ->get(route('admin.orders.index'));
        $elapsed = (microtime(true) - $start) * 1000;

        $response->assertOk();
        $this->assertLessThan(3000, $elapsed,
            "Orders index terlalu lambat: {$elapsed}ms (batas 3000ms)");
    }

    public function test_orders_show_responds_within_time_limit(): void
    {
        [$order] = $this->seedOrders(1);

        $start = microtime(true);
        $response = $this->actingAs($this->admin)
            ->withSession(['2fa_passed' => true])
            ->get(route('admin.orders.show', $order));
        $elapsed = (microtime(true) - $start) * 1000;

        $response->assertOk();
        $this->assertLessThan(3000, $elapsed,
            "Orders show terlalu lambat: {$elapsed}ms (batas 3000ms)");
    }

    // =========================================================================
    // QUERY COUNT — deteksi N+1
    // =========================================================================

    public function test_orders_index_query_count_does_not_grow_with_data(): void
    {
        $this->seedOrders(20);

        DB::enableQueryLog();
        $this->actingAs($this->admin)
            ->withSession(['2fa_passed' => true])
            ->get(route('admin.orders.index'))
            ->assertOk();
        $queryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        $this->assertLessThan(25, $queryCount,
            "Kemungkinan N+1 query: {$queryCount} query untuk 20 pesanan (batas 25)");
    }

    public function test_orders_index_filter_status_query_count(): void
    {
        $this->seedOrders(20, ['status' => 'paid']);

        DB::enableQueryLog();
        $this->actingAs($this->admin)
            ->withSession(['2fa_passed' => true])
            ->get(route('admin.orders.index', ['status' => 'paid']))
            ->assertOk();
        $queryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        $this->assertLessThan(25, $queryCount,
            "Filter status N+1: {$queryCount} query (batas 25)");
    }

    public function test_orders_show_query_count(): void
    {
        [$order] = $this->seedOrders(1);

        DB::enableQueryLog();
        $this->actingAs($this->admin)
            ->withSession(['2fa_passed' => true])
            ->get(route('admin.orders.show', $order))
            ->assertOk();
        $queryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        $this->assertLessThan(15, $queryCount,
            "Order show terlalu banyak query: {$queryCount} (batas 15)");
    }

    // =========================================================================
    // MEMORY USAGE
    // =========================================================================

    public function test_orders_index_memory_usage_within_limit(): void
    {
        $this->seedOrders(20);

        $memBefore = memory_get_usage(true);
        $this->actingAs($this->admin)
            ->withSession(['2fa_passed' => true])
            ->get(route('admin.orders.index'))
            ->assertOk();
        $memUsedMB = (memory_get_usage(true) - $memBefore) / 1024 / 1024;

        $this->assertLessThan(50, $memUsedMB,
            "Memory terlalu besar: {$memUsedMB}MB (batas 50MB)");
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
                'user_id'          => $user->id,
                'label'            => 'Rumah',
                'recipient_name'   => 'Pembeli ' . $i,
                'recipient_phone'  => '08123456789' . $i,
                'province_id'      => '1',
                'province_name'    => 'Jawa Barat',
                'city_id'          => '1',
                'city_name'        => 'Bandung',
                'city_type'        => 'Kota',
                'postal_code'      => '40123',
                'full_address'     => 'Jl. Test No. ' . $i,
                'is_default'       => true,
            ]);
            $order = Order::create(array_merge([
                'user_id'         => $user->id,
                'address_id'      => $address->id,
                'order_number'    => Order::generateOrderNumber(),
                'subtotal'        => 500000,
                'shipping_cost'   => 20000,
                'grand_total'     => 520000,
                'status'          => 'pending',
                'courier'         => 'jne',
                'courier_service' => 'reg',
            ], $overrides));
            $order->shippingSnapshot()->create([
                'label'            => $address->label,
                'recipient_name'   => $address->recipient_name,
                'recipient_phone'  => $address->recipient_phone,
                'province_id'      => $address->province_id,
                'province_name'    => $address->province_name,
                'city_id'          => $address->city_id,
                'city_name'        => $address->city_name,
                'city_type'        => $address->city_type,
                'postal_code'      => $address->postal_code,
                'full_address'     => $address->full_address,
            ]);
            OrderItem::create([
                'order_id'             => $order->id,
                'product_id'           => $product->id,
                'product_name'         => $product->name,
                'product_image'        => null,
                'product_category_name'=> $category->name,
                'quantity'             => 1,
                'price'                => 500000,
                'subtotal'             => 500000,
            ]);
            $orders[] = $order;
        }

        return $orders;
    }
}
