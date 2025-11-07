<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function notification(Request $request)
    {
        $payload = $request->all();
        $signatureKey = hash('sha512', $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . config('midtrans.server_key'));

        // Verifikasi signature (KEAMANAN 100%)
        if ($signatureKey !== $payload['signature_key']) {
            Log::warning('Midtrans fake notification', $payload);
            return response('Invalid signature', 403);
        }

        $orderNumber = $payload['order_id'];
        $transactionStatus = $payload['transaction_status'];
        $fraudStatus = $payload['fraud_status'] ?? 'accept';

        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            Log::error("Order not found: {$orderNumber}");
            return response('Order not found', 404);
        }

        // UPDATE STATUS OTOMATIS BERDASARKAN MIDTRANS
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            if ($fraudStatus == 'accept') {
                $order->status = 'paid';
                $order->paid_at = now();
                $order->save();

                // Update payment status juga
                if ($order->payment) {
                    $order->payment->status = 'paid';
                    $order->payment->paid_at = now();
                    $order->payment->save();
                }

                Log::info("Order {$orderNumber} PAID automatically via Midtrans");
            }
        } 
        elseif ($transactionStatus == 'pending') {
            $order->status = 'pending';
            $order->save();
        }
        elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
            $order->status = 'cancelled';
            $order->save();
        }

        return response('OK', 200);
    }
}