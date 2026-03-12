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
     * Ongkos Kirim Dasar berdasarkan Provinsi
     */
    protected array $ongkirMap = [
        '31' => 15000, // Lampung
        '1' => 40000, '2' => 40000, '3' => 40000, '4' => 40000, '5' => 40000, '6' => 40000, // Jawa
        '32' => 30000, '33' => 35000, '34' => 40000, '35' => 35000, '36' => 40000, '37' => 35000, '38' => 30000, '39' => 30000, // Sumatera
        '61' => 70000, '62' => 75000, '63' => 75000, '64' => 80000, '65' => 85000, // Kalimantan
        '71' => 70000, '72' => 75000, '73' => 75000, '74' => 80000, '75' => 80000, '76' => 75000, // Sulawesi
        '51' => 60000, '52' => 90000, '53' => 95000, // Bali & NTT
        '81' => 120000, '82' => 125000, '91' => 130000, '92' => 130000 // Maluku & Papua
    ];

    /**
     * Hitung ongkos kirim manual by provinsi dan berat.
     */
    public function calculateShippingCost($provinceId, $weightGrams): int
    {
        $weightKg = ceil($weightGrams / 1000);
        $baseCost = $this->ongkirMap[$provinceId] ?? 60000;

        return $baseCost + ($weightKg > 1 ? ($weightKg - 1) * 10000 : 0);
    }

    /**
     * Get Biteship Courier Default Service
     */
    public function getBiteshipServiceType($courier): string
    {
        $defaultServices = [
            'jne' => 'reg',
            'jnt' => 'ez',
            'sicepat' => 'reg',
            'anteraja' => 'reg',
            'pos' => 'reg',
            'tiki' => 'reg'
        ];
        return $defaultServices[$courier] ?? 'reg';
    }

    /**
     * Buat order baru beserta itemnya (mengurangi stok dan menghapus cart)
     */
    public function createOrder($userId, $carts, $address, $courier): Order
    {
        DB::beginTransaction();
        try {
            $totalAmount = $carts->sum('subtotal');
            $weight = $carts->sum(fn($c) => ($c->product->weight ?? 1000) * $c->quantity);

            $shippingCost = $this->calculateShippingCost($address->province_id, $weight);
            $grandTotal = $totalAmount + $shippingCost;

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
                'user_id' => $userId,
                'order_number' => Order::generateOrderNumber(),
                'subtotal' => $totalAmount,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'status' => 'pending',
                'address_id' => $address->id,
                'recipient_name' => $address->recipient_name,
                'recipient_phone' => $address->recipient_phone,
                'province' => $address->province_name,
                'province_id' => $address->province_id,
                'city' => $address->city_type . ' ' . $address->city_name,
                'city_id' => $address->city_id,
                'postal_code' => $address->postal_code,
                'shipping_address' => $address->full_address,
                'courier' => $courier,
                'courier_service' => $this->getBiteshipServiceType($courier),
            ]);

            // 3. Kurangi Stok & Buat Order Items
            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                    'subtotal' => $cart->subtotal,
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
     * Hitung ulang ongkir saat ganti alamat
     */
    public function updateShippingDetails(Order $order, $address, $courier)
    {
        DB::beginTransaction();
        try {
            $weight = $order->items->sum(fn($i) => ($i->product->weight ?? 1000) * $i->quantity);
            $shippingCost = $this->calculateShippingCost($address->province_id, $weight);
            $grandTotal = $order->subtotal + $shippingCost;

            $order->update([
                'address_id' => $address->id,
                'recipient_name' => $address->recipient_name,
                'recipient_phone' => $address->recipient_phone,
                'province' => $address->province_name,
                'province_id' => $address->province_id,
                'city' => $address->city_type . ' ' . $address->city_name,
                'city_id' => $address->city_id,
                'postal_code' => $address->postal_code,
                'shipping_address' => $address->full_address,
                'courier' => $courier,
                'courier_service' => $this->getBiteshipServiceType($courier),
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
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

            // Recalculate if items exist
            $items = $order->items()->with('product')->get();
            $newSubtotal = $items->sum('subtotal');
            $weight = $items->sum(fn($i) => ($i->product->weight ?? 1000) * $i->quantity);

            $newShippingCost = $this->calculateShippingCost($order->province_id, $weight);
            $newGrandTotal = $newSubtotal + $newShippingCost;

            $order->update([
                'subtotal' => $newSubtotal,
                'shipping_cost' => $newShippingCost,
                'grand_total' => $newGrandTotal,
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
