<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

class OrderService
{
    /**
     * Get Courier Service Default Label
     */
    public function getBiteshipServiceType($courier): string
    {
        $defaultServices = [
            'jne'      => 'reg',
            'jnt'      => 'ez',
            'sicepat'  => 'reg',
            'anteraja' => 'reg',
            'pos'      => 'reg',
            'tiki'     => 'reg'
        ];
        return $defaultServices[$courier] ?? 'reg';
    }

    /**
     * Buat order baru beserta itemnya (mengurangi stok dan menghapus cart)
     * $shippingCost: didapat dari RajaOngkir API (bukan dihitung manual)
     */
    public function createOrder($userId, $carts, $address, $courier, $courierService = 'reg', $shippingCost = 0): Order
    {
        DB::beginTransaction();
        try {
            $totalAmount = $carts->sum('subtotal');
            $grandTotal  = $totalAmount + $shippingCost;
            $lockedProducts = collect();

            // 1. Validasi Ekstra Ketat (Lock baris)
            foreach ($carts as $cart) {
                $product = Product::with('category')
                    ->whereKey($cart->product_id)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw new Exception("Produk tidak ditemukan.");
                }
                if ($product->stock < $cart->quantity) {
                    throw new Exception("Stok produk '{$product->name}' tidak mencukupi (Sisa: {$product->stock}). Silakan update keranjang.");
                }

                $lockedProducts->put($product->id, $product);
            }

            // 2. Buat Order
            $order = Order::create([
                'user_id'         => $userId,
                'order_number'    => Order::generateOrderNumber(),
                'subtotal'        => $totalAmount,
                'shipping_cost'   => $shippingCost,
                'grand_total'     => $grandTotal,
                'status'          => 'pending',
                'address_id'      => $address->id,   // Sumber kebenaran alamat
                'courier'         => $courier,
                'courier_service' => $courierService,
            ]);

            $order->shippingSnapshot()->create($this->shippingSnapshot($address));

            // 3. Kurangi Stok & Buat Order Items
            foreach ($carts as $cart) {
                $product = $lockedProducts->get($cart->product_id);

                OrderItem::create([
                    'order_id'               => $order->id,
                    'product_id'             => $product->id,
                    'product_name'           => $product->name,
                    'product_image'          => $this->copyOrderItemImage($product, $order),
                    'product_category_name'  => $product->category?->name,
                    'quantity'               => $cart->quantity,
                    'price'                  => $product->price,
                    'subtotal'               => $cart->subtotal,
                ]);

                $product->decrement('stock', $cart->quantity);
            }

            // 4. Bersihkan hanya item keranjang yang sudah di-checkout
            $cartIds = $carts->pluck('id')->toArray();
            Cart::where('user_id', $userId)->whereIn('id', $cartIds)->delete();
            DB::commit();

            return $order;
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update shipping saat ganti alamat/kurir
     * $shippingCost: didapat dari RajaOngkir API
     */
    public function updateShippingDetails(Order $order, $address, $courier, $courierService = 'reg', $shippingCost = 0)
    {
        DB::beginTransaction();
        try {
            $grandTotal = $order->subtotal + $shippingCost;

            $order->update([
                'address_id'      => $address->id,   // Sumber kebenaran alamat
                'courier'         => $courier,
                'courier_service' => $courierService,
                'shipping_cost'   => $shippingCost,
                'grand_total'     => $grandTotal,
            ]);

            $order->shippingSnapshot()->updateOrCreate(
                ['order_id' => $order->id],
                $this->shippingSnapshot($address)
            );

            DB::commit();
            return $order;
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Menghapus item dari keranjang dan hitung ulang jika masih ada sisa item.
     * Mengembalikan `cancel` jika item habis, atau `update` jika item sisa.
     */
    public function removeOrderItem(Order $order, OrderItem $item): string
    {
        DB::beginTransaction();
        try {
            // Restore stok
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
            }

            $item->delete();
            $remainingItemsCount = $order->items()->count();

            if ($remainingItemsCount === 0) {
                $order->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                ]);
                DB::commit();
                return 'cancel';
            }

            // Recalculate if items exist – pertahankan shipping_cost lama
            $items = $order->items()->with('product')->get();
            $newSubtotal = $items->sum('subtotal');
            $newGrandTotal = $newSubtotal + $order->shipping_cost;

            $order->update([
                'subtotal'   => $newSubtotal,
                'grand_total'=> $newGrandTotal,
            ]);

            DB::commit();
            return 'update';
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Membatalkan order secara manual (restocking items)
     */
    public function cancelOrder(Order $order)
    {
        DB::beginTransaction();
        try {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            DB::commit();
            return true;
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function shippingSnapshot($address): array
    {
        return [
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
        ];
    }

    private function copyOrderItemImage(Product $product, Order $order): ?string
    {
        if (!$product->image || !Storage::disk('public')->exists($product->image)) {
            return null;
        }

        $extension = pathinfo($product->image, PATHINFO_EXTENSION) ?: 'jpg';
        $destination = sprintf(
            'order-items/%s/%s-%s.%s',
            $order->order_number,
            $product->id,
            uniqid(),
            $extension
        );

        return Storage::disk('public')->copy($product->image, $destination)
            ? $destination
            : null;
    }
}
