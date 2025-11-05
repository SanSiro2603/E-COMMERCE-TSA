<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create Snap Transaction
     */
    public function createTransaction($order)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->grand_total,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->user->phone ?? '08123456789',
            ],
            'item_details' => $this->getItemDetails($order),
            'enabled_payments' => [
                'gopay', 'shopeepay', 'other_qris',
                'credit_card', 'bca_va', 'bni_va', 'bri_va', 
                'permata_va', 'other_va', 'alfamart', 'indomaret'
            ],
            'callbacks' => [
                'finish' => route('pembeli.payment.finish'),
            ],
            'expiry' => [
                'unit' => 'hours',
                'duration' => 24
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return [
                'success' => true,
                'snap_token' => $snapToken,
                'snap_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get Item Details for Midtrans
     */
    private function getItemDetails($order)
    {
        $items = [];

        foreach ($order->items as $item) {
            $items[] = [
                'id' => $item->product_id,
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
                'name' => substr($item->product->name, 0, 50),
            ];
        }

        // Add shipping cost
        if ($order->shipping_cost > 0) {
            $items[] = [
                'id' => 'SHIPPING',
                'price' => (int) $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Biaya Pengiriman',
            ];
        }

        return $items;
    }

    /**
     * Check Transaction Status
     */
    public function checkStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);
            return [
                'success' => true,
                'data' => $status
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Cancel Transaction
     */
    public function cancelTransaction($orderId)
    {
        try {
            $cancel = Transaction::cancel($orderId);
            return [
                'success' => true,
                'data' => $cancel
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}