<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
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

            // 1. Validasi Ekstra Ketat (Lock baris)
            foreach ($carts as $cart) {
                $product = Product::lockForUpdate()->find($cart->product_id);
                if (!$product) {
                    throw new Exception("Produk tidak ditemukan.");
                }
                if ($product->stock < $cart->quantity) {
                    throw new Exception("Stok produk '{$product->name}' tidak mencukupi (Sisa: {$product->stock}). Silakan update keranjang.");
                }
            }

            // 2. Buat Order
            $order = Order::create([
                'user_id'          => $userId,
                'order_number'     => Order::generateOrderNumber(),
                'subtotal'         => $totalAmount,
                'shipping_cost'    => $shippingCost,
                'grand_total'      => $grandTotal,
                'status'           => 'pending',
                'address_id'       => $address->id,
                'recipient_name'   => $address->recipient_name,
                'recipient_phone'  => $address->recipient_phone,
                'province'         => $address->province_name,
                'province_id'      => $address->province_id,
                'city'             => $address->city_type . ' ' . $address->city_name,
                'city_id'          => $address->city_id,
                'postal_code'      => $address->postal_code,
                'shipping_address' => $address->full_address,
                'courier'          => $courier,
                'courier_service'  => $courierService,
            ]);

            // 3. Kurangi Stok & Buat Order Items
            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity'   => $cart->quantity,
                    'price'      => $cart->product->price,
                    'subtotal'   => $cart->subtotal,
                ]);

                $product = Product::find($cart->product_id);
                $product->decrement('stock', $cart->quantity);
            }

            // 4. Bersihkan Keranjang
            Cart::where('user_id', $userId)->delete();
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
                'address_id'       => $address->id,
                'recipient_name'   => $address->recipient_name,
                'recipient_phone'  => $address->recipient_phone,
                'province'         => $address->province_name,
                'province_id'      => $address->province_id,
                'city'             => $address->city_type . ' ' . $address->city_name,
                'city_id'          => $address->city_id,
                'postal_code'      => $address->postal_code,
                'shipping_address' => $address->full_address,
                'courier'          => $courier,
                'courier_service'  => $courierService,
                'shipping_cost'    => $shippingCost,
                'grand_total'      => $grandTotal,
            ]);

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
}
