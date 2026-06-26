<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\Address;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_uses_snapshot_data_for_filters_charts_and_sales_table(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        [$order, $product, , $address, $category] = $this->createOrderWithProduct([
            'status' => 'completed',
            'payment_method' => 'bank_transfer',
        ]);

        $product->delete();
        $address->delete();
        $order->update(['address_id' => null]);

        $response = $this->actingAs($superAdmin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.dashboard', [
                'date_from' => $order->created_at->format('Y-m-d'),
                'date_to' => $order->created_at->format('Y-m-d'),
                'province' => 'Jawa Barat',
                'category_id' => $category->id,
            ]));

        $response->assertOk();
        $response->assertSee('Macaw Biru');
        $response->assertSee('Produk sudah dihapus dari katalog');
        $response->assertSee('Aves');
        $response->assertSee('Jawa Barat');

        $this->assertSame(1, $response->viewData('salesTable')->total());
        $this->assertSame('Macaw Biru', $response->viewData('topProducts')->first()->name);
        $this->assertSame('Aves', $response->viewData('topCategories')->first()->name);
        $this->assertSame('Jawa Barat', $response->viewData('topProvinces')->first()->province);
    }

    public function test_dashboard_payment_method_chart_excludes_pending_orders_without_payment_records(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $this->createOrderWithProduct([
            'status' => 'completed',
            'payment_method' => 'bank_transfer',
        ]);
        $this->createOrderWithProduct([
            'status' => 'pending',
            'payment_method' => 'qris',
        ]);

        $response = $this->actingAs($superAdmin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.dashboard'));

        $response->assertOk();

        $paymentMethods = $response->viewData('paymentMethods');

        $this->assertTrue($paymentMethods->contains('key', 'bank_transfer'));
        $this->assertFalse($paymentMethods->contains('key', 'qris'));
    }

    // =========================================================================
    // ACCESS CONTROL
    // =========================================================================

    public function test_guest_cannot_access_superadmin_dashboard(): void
    {
        $response = $this->get(route('superadmin.dashboard'));
        $response->assertRedirect();
    }

    public function test_admin_cannot_access_superadmin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.dashboard'));
        $response->assertForbidden();
    }

    public function test_pembeli_cannot_access_superadmin_dashboard(): void
    {
        $pembeli = User::factory()->create(['role' => 'pembeli']);
        $response = $this->actingAs($pembeli)->get(route('superadmin.dashboard'));
        $response->assertForbidden();
    }

    // =========================================================================
    // EMPTY STATE
    // =========================================================================

    public function test_dashboard_loads_successfully_with_no_orders(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($superAdmin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.dashboard'));

        $response->assertOk();
        $this->assertSame(0, $response->viewData('salesTable')->total());
    }

    // =========================================================================
    // DATE RANGE FILTER
    // =========================================================================

    public function test_dashboard_date_range_excludes_orders_outside_range(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $this->createOrderWithProduct(['status' => 'completed']);

        $response = $this->actingAs($superAdmin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.dashboard', [
                'date_from' => '2000-01-01',
                'date_to'   => '2000-01-31',
            ]));

        $response->assertOk();
        $this->assertSame(0, $response->viewData('salesTable')->total());
    }

    public function test_dashboard_revenue_excludes_pending_and_cancelled_orders(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $this->createOrderWithProduct(['status' => 'pending']);
        $this->createOrderWithProduct(['status' => 'cancelled']);
        $this->createOrderWithProduct(['status' => 'completed', 'payment_method' => 'bank_transfer']);

        $response = $this->actingAs($superAdmin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.dashboard'));

        $response->assertOk();
        $this->assertSame(1520000.0, (float) $response->viewData('totalRevenue'));
    }

    private function createOrderWithProduct(array $orderOverrides = []): array
    {
        $user = User::factory()->create(['role' => 'pembeli']);
        $category = Category::firstOrCreate(
            ['slug' => 'aves'],
            [
                'name' => 'Aves',
                'is_active' => true,
            ]
        );
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Macaw Biru',
            'slug' => 'macaw-biru-' . uniqid(),
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
            'status' => 'paid',
            'courier' => 'jne',
            'courier_service' => 'reg',
        ], $orderOverrides));

        $order->shippingSnapshot()->create([
            'label' => $address->label,
            'recipient_name' => $address->recipient_name,
            'recipient_phone' => $address->recipient_phone,
            'province_id' => $address->province_id,
            'province_name' => $address->province_name,
            'city_id' => $address->city_id,
            'city_name' => $address->city_name,
            'city_type' => $address->city_type,
            'postal_code' => $address->postal_code,
            'full_address' => $address->full_address,
        ]);

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

        return [$order, $product, $item, $address, $category, $user];
    }
}
