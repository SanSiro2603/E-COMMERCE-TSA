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

        // =============================================
        // VERIFIKASI SIGNATURE KEY
        // =============================================
        $signatureKey = hash(
            'sha512',
            $payload['order_id'] .
            $payload['status_code'] .
            $payload['gross_amount'] .
            config('midtrans.server_key')
        );

        if ($signatureKey !== $payload['signature_key']) {
            Log::warning('Midtrans fake notification', $payload);
            return response('Invalid signature', 403);
        }

        $orderNumber       = $payload['order_id'];
        $transactionStatus = $payload['transaction_status'];
        $fraudStatus       = $payload['fraud_status'] ?? 'accept';

        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            Log::error("Order not found: {$orderNumber}");
            return response('Order not found', 404);
        }

        // =============================================
        // SIMPAN / UPDATE DATA PAYMENT
        // Ini yang sebelumnya hilang — payment_type tidak pernah tersimpan
        // =============================================
        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'order_id_midtrans'  => $payload['order_id']            ?? null,
                'payment_type'       => $payload['payment_type']         ?? null,
                'transaction_id'     => $payload['transaction_id']       ?? null,
                'transaction_status' => $payload['transaction_status']   ?? null,
                'gross_amount'       => $payload['gross_amount']         ?? 0,
                'payment_code'       => $payload['payment_code']         ?? null,
                'pdf_url'            => $payload['pdf_url']              ?? null,
                'transaction_time'   => $payload['transaction_time']     ?? null,
                'bank'               => $payload['bank']                 ?? null,
                'va_number'          => $payload['va_numbers'][0]['va_number'] ?? null,
                'bill_key'           => $payload['bill_key']             ?? null,
                'biller_code'        => $payload['biller_code']          ?? null,
                'store'              => $payload['store']                ?? null,
                'metadata'           => $payload,
            ]
        );

        // =============================================
        // UPDATE STATUS ORDER BERDASARKAN NOTIFIKASI MIDTRANS
        // =============================================
        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            if ($fraudStatus === 'accept') {
                $order->status  = 'paid';
                $order->paid_at = now();
                $order->save();

                Log::info("Order {$orderNumber} PAID automatically via Midtrans", [
                    'payment_type' => $payload['payment_type'] ?? '-',
                    'transaction_id' => $payload['transaction_id'] ?? '-',
                ]);
            }
        } elseif ($transactionStatus === 'pending') {
            $order->status = 'pending';
            $order->save();

            Log::info("Order {$orderNumber} PENDING via Midtrans");
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
            $order->status = 'cancelled';
            $order->save();

            Log::info("Order {$orderNumber} CANCELLED via Midtrans", [
                'transaction_status' => $transactionStatus,
            ]);
        }

        return response('OK', 200);
    }
}