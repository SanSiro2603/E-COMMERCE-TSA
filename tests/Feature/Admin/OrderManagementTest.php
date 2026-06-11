<?php

namespace Tests\Feature\Admin;

use App\Models\Address;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\BiteshipService;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_item_history_is_preserved_when_product_is_deleted(): void
    {
        [$order, $product, $item] = $this->createOrderWithProduct();

        $product->delete();

        $this->assertDatabaseHas('order_items', [
            'id' => $item->id,
            'order_id' => $order->id,
            'product_id' => null,
            'product_name' => 'Macaw Biru',
        ]);
    }

    public function test_admin_can_view_order_detail_after_order_product_is_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$order, $product] = $this->createOrderWithProduct();

        $product->delete();

        $response = $this->actingAs($admin)
            ->withSession(['2fa_passed' => true])
            ->get(route('admin.orders.show', $order));

        $response->assertOk();
        $response->assertSee('Macaw Biru');
        $response->assertSee('Produk sudah dihapus dari katalog');
    }

    public function test_admin_cannot_create_biteship_shipment_without_shipping_address(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$order] = $this->createOrderWithProduct(['status' => 'paid', 'address_id' => null]);
        $order->update([
            'shipping_recipient_name' => null,
            'shipping_recipient_phone' => null,
            'shipping_province_name' => null,
            'shipping_city_name' => null,
            'shipping_postal_code' => null,
            'shipping_full_address' => null,
        ]);

        $biteship = Mockery::mock(BiteshipService::class);
        $biteship->shouldNotReceive('createOrder');
        $this->app->instance(BiteshipService::class, $biteship);

        $response = $this->actingAs($admin)
            ->withSession(['2fa_passed' => true])
            ->post(route('admin.orders.biteship.create', $order));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertSame('paid', $order->fresh()->status);
    }

    public function test_order_shipping_address_history_is_visible_after_address_is_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$order, , , $address, $buyer] = $this->createOrderWithProduct(['status' => 'shipped']);

        $address->delete();
        $order->update(['address_id' => null]);
        $this->assertNull($order->fresh()->address_id);

        $buyerResponse = $this->actingAs($buyer)
            ->get(route('pembeli.pesanan.show', $order));

        $buyerResponse->assertOk();
        $buyerResponse->assertSee('Budi');
        $buyerResponse->assertSee('Jl. Mawar No. 1');
        $buyerResponse->assertSee('Kota Bandung, Jawa Barat 40123');

        $adminResponse = $this->actingAs($admin)
            ->withSession(['2fa_passed' => true])
            ->get(route('admin.orders.show', $order));

        $adminResponse->assertOk();
        $adminResponse->assertSee('Budi');
        $adminResponse->assertSee('Jl. Mawar No. 1');
        $adminResponse->assertSee('Kota Bandung, Jawa Barat 40123');
    }

    public function test_order_service_copies_product_image_to_order_item_snapshot(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/macaw.jpg', 'fake-image-content');

        $user = User::factory()->create(['role' => 'pembeli']);
        $category = Category::create([
            'name' => 'Aves',
            'slug' => 'aves',
            'is_active' => true,
        ]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Macaw Biru',
            'slug' => 'macaw-biru',
            'description' => 'Burung macaw',
            'price' => 1500000,
            'stock' => 5,
            'weight' => 1000,
            'image' => 'products/macaw.jpg',
            'is_active' => true,
        ]);
        $address = Address::create([
            'user_id' => $user->id,
            'label' => 'Rumah',
            'recipient_name' => 'Budi',
            'recipient_phone' => '081234567890',
            'province_id' => '1',
            'province_name' => 'Jawa Barat',
            'city_id' => '1',
            'city_name' => 'Bandung',
            'city_type' => 'Kota',
            'postal_code' => '40123',
            'full_address' => 'Jl. Mawar No. 1',
            'is_default' => true,
        ]);
        $carts = collect([
            (object) [
                'id' => null,
                'product_id' => $product->id,
                'quantity' => 1,
                'subtotal' => 1500000,
            ],
        ]);

        $order = app(OrderService::class)->createOrder(
            $user->id,
            $carts,
            $address,
            'jne',
            'reg',
            20000
        );

        $item = $order->items()->first();

        $this->assertNotNull($item->product_image);
        $this->assertStringStartsWith('order-items/' . $order->order_number . '/', $item->product_image);
        Storage::disk('public')->assertExists($item->product_image);

        Storage::disk('public')->delete('products/macaw.jpg');
        $this->assertSame($item->product_image, $item->fresh()->display_image);
    }

    private function createOrderWithProduct(array $orderOverrides = []): array
    {
        $user = User::factory()->create(['role' => 'pembeli']);
        $category = Category::create([
            'name' => 'Aves',
            'slug' => 'aves',
            'is_active' => true,
        ]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Macaw Biru',
            'slug' => 'macaw-biru',
            'description' => 'Burung macaw',
            'price' => 1500000,
            'stock' => 5,
            'weight' => 1000,
            'is_active' => true,
        ]);
        $address = Address::create([
            'user_id' => $user->id,
            'label' => 'Rumah',
            'recipient_name' => 'Budi',
            'recipient_phone' => '081234567890',
            'province_id' => '1',
            'province_name' => 'Jawa Barat',
            'city_id' => '1',
            'city_name' => 'Bandung',
            'city_type' => 'Kota',
            'postal_code' => '40123',
            'full_address' => 'Jl. Mawar No. 1',
            'is_default' => true,
        ]);

        $order = Order::create(array_merge([
            'user_id' => $user->id,
            'address_id' => $address->id,
            'order_number' => Order::generateOrderNumber(),
            'subtotal' => 1500000,
            'shipping_cost' => 20000,
            'grand_total' => 1520000,
            'status' => 'pending',
            'courier' => 'jne',
            'courier_service' => 'reg',
            'shipping_label' => $address->label,
            'shipping_recipient_name' => $address->recipient_name,
            'shipping_recipient_phone' => $address->recipient_phone,
            'shipping_province_id' => $address->province_id,
            'shipping_province_name' => $address->province_name,
            'shipping_city_id' => $address->city_id,
            'shipping_city_name' => $address->city_name,
            'shipping_city_type' => $address->city_type,
            'shipping_postal_code' => $address->postal_code,
            'shipping_full_address' => $address->full_address,
        ], $orderOverrides));

        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_image' => $product->image,
            'product_category_name' => $category->name,
            'quantity' => 1,
            'price' => 1500000,
            'subtotal' => 1500000,
        ]);

        return [$order, $product, $item, $address, $user];
    }
}
