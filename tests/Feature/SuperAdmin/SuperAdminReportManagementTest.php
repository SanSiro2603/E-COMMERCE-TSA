<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\Address;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class SuperAdminReportManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_report_filters_snapshot_orders_by_province_and_category_after_product_and_address_are_deleted(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        [$order, $product, , $address, $category] = $this->createOrderWithProduct([
            'status' => 'completed',
        ]);

        $product->delete();
        $address->delete();
        $order->update(['address_id' => null]);

        $response = $this->actingAs($superAdmin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.reports.index', [
                'start_date' => $order->created_at->format('Y-m-d'),
                'end_date' => $order->created_at->format('Y-m-d'),
                'province' => 'Jawa Barat',
                'category_id' => $category->id,
            ]));

        $response->assertOk();
        $response->assertSee('1 data ditemukan');
        $response->assertSee('Jawa Barat');
        $response->assertSee('Aves');
        $response->assertSee('Macaw Biru');
        $response->assertSee('Produk sudah dihapus dari katalog');
    }

    public function test_superadmin_report_pdf_exports_snapshot_orders_after_product_is_deleted(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        [$order, $product] = $this->createOrderWithProduct(['status' => 'completed']);

        $product->delete();

        $response = $this->actingAs($superAdmin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.reports.exportPdf', [
                'start_date' => $order->created_at->format('Y-m-d'),
                'end_date' => $order->created_at->format('Y-m-d'),
            ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_superadmin_report_excel_exports_snapshot_orders_after_product_is_deleted(): void
    {
        Excel::fake();

        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        [$order, $product] = $this->createOrderWithProduct(['status' => 'completed']);

        $product->delete();

        $this->actingAs($superAdmin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.reports.exportExcel', [
                'start_date' => $order->created_at->format('Y-m-d'),
                'end_date' => $order->created_at->format('Y-m-d'),
            ]))
            ->assertOk();

        Excel::assertDownloaded('laporan-penjualan-' . $order->created_at->format('Y-m-d') . '-sd-' . $order->created_at->format('Y-m-d') . '.xlsx');
    }

    public function test_superadmin_report_shows_stats_note_for_non_revenue_status(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $this->createOrderWithProduct(['status' => 'cancelled']);

        $response = $this->actingAs($superAdmin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.reports.index', ['status' => 'cancelled']));

        $response->assertOk();
        $response->assertSee('tetap ditampilkan di tabel untuk analisis');
        $response->assertSee('1 data ditemukan');
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
            'status' => 'paid',
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

        return [$order, $product, $item, $address, $category, $user];
    }
}
