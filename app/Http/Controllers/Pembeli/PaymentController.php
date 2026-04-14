<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\SystemSetting;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    /**
     * Show payment page
     */
    public function show($orderId)
    {
        $isStoreOpen = SystemSetting::where('key', 'shopping_enabled')->value('value');
        if ($isStoreOpen !== '1') {
            return redirect()->route('pembeli.pesanan.index')
                ->with('error', 'Pembayaran ditutup sementara oleh Admin.');
        }

        $order = Order::with(['items.product', 'payment'])
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);

        if (!in_array($order->status, ['pending', 'paid'])) {
            return redirect()->route('pembeli.pesanan.show', $order)
                ->with('error', 'Pesanan tidak dapat dibayar pada status ini');
        }

        if ($order->payment && $order->payment->snap_token) {
            return view('pembeli.payment.show', [
                'order'      => $order,
                'payment'    => $order->payment,
                'snapToken'  => $order->payment->snap_token,
                'clientKey'  => config('midtrans.client_key'),
            ]);
        }

        try {
            DB::beginTransaction();

            $result = $this->midtrans->createTransaction($order);

            if (!$result['success']) {
                throw new \Exception($result['message']);
            }

            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'gross_amount'       => $order->grand_total,
                    'snap_token'         => $result['snap_token'],
                    'snap_url'           => $result['snap_url'],
                    'transaction_status' => 'pending',
                    'expiry_time'        => now()->addHours(24),
                ]
            );

            DB::commit();

            return view('pembeli.payment.show', [
                'order'     => $order,
                'payment'   => $payment,
                'snapToken' => $result['snap_token'],
                'clientKey' => config('midtrans.client_key'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment creation failed: ' . $e->getMessage());

            return redirect()->route('pembeli.pesanan.show', $order)
                ->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Payment finish callback dari Midtrans
     */
    public function finish(Request $request)
    {
        $orderNumber       = $request->order_id;
        $transactionStatus = $request->transaction_status;

        return redirect()->route('pembeli.pesanan.index')
            ->with('check_status', $orderNumber)
            ->with('transaction_status', $transactionStatus);
    }

    /**
     * Webhook notification dari Midtrans
     * Menangani semua metode: bank_transfer, gopay, qris, shopeepay, cstore, dll
     */
    public function notification(Request $request)
    {
        try {
            $payload = $request->all();
            Log::info('Midtrans Notification Received', $payload);

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

            if ($signatureKey !== ($payload['signature_key'] ?? '')) {
                Log::warning('Midtrans invalid signature', $payload);
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            $orderNumber       = $payload['order_id'];
            $transactionStatus = $payload['transaction_status'];
            $fraudStatus       = $payload['fraud_status'] ?? 'accept';

            $order = Order::where('order_number', $orderNumber)->first();

            if (!$order) {
                Log::error('Order not found: ' . $orderNumber);
                return response()->json(['message' => 'Order not found'], 404);
            }

            // =============================================
            // SIMPAN / UPDATE DATA PAYMENT
            // Mendukung semua metode Midtrans:
            // bank_transfer, gopay, qris, shopeepay, cstore, credit_card, echannel
            // =============================================
            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'order_id_midtrans'  => $payload['order_id']               ?? null,
                    'payment_type'       => $payload['payment_type']            ?? null,
                    'transaction_id'     => $payload['transaction_id']          ?? null,
                    'transaction_status' => $payload['transaction_status']      ?? null,
                    'gross_amount'       => $payload['gross_amount']            ?? 0,
                    'transaction_time'   => $payload['transaction_time']        ?? null,
                    'pdf_url'            => $payload['pdf_url']                 ?? null,

                    // Bank Transfer / Virtual Account
                    'bank'               => $payload['bank']                    ?? null,
                    'va_number'          => $payload['va_numbers'][0]['va_number'] ?? null,

                    // Mandiri E-Channel
                    'bill_key'           => $payload['bill_key']                ?? null,
                    'biller_code'        => $payload['biller_code']             ?? null,

                    // Minimarket (Alfamart/Indomaret)
                    'store'              => $payload['store']                   ?? null,
                    'payment_code'       => $payload['payment_code']
                                            ?? $payload['bill_key']
                                            ?? null,

                    // GoPay / ShopeePay — URL ada di actions[0]
                    'gopay_url'          => $payload['actions'][0]['url']       ?? null,

                    // Simpan semua payload mentah sebagai metadata
                    'metadata'           => $payload,
                ]
            );

            // =============================================
            // UPDATE STATUS ORDER
            // =============================================
            DB::beginTransaction();

            if ($transactionStatus === 'capture') {
                if ($fraudStatus === 'accept') {
                    $order->update([
                        'status'  => 'paid',
                        'paid_at' => now(),
                    ]);
                    Log::info("Order {$orderNumber} PAID via capture", [
                        'payment_type' => $payload['payment_type'] ?? '-',
                    ]);
                }
            } elseif ($transactionStatus === 'settlement') {
                $order->update([
                    'status'  => 'paid',
                    'paid_at' => now(),
                ]);
                Log::info("Order {$orderNumber} PAID via settlement", [
                    'payment_type' => $payload['payment_type'] ?? '-',
                ]);
            } elseif ($transactionStatus === 'pending') {
                $order->update(['status' => 'pending']);
                Log::info("Order {$orderNumber} PENDING");
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel', 'failure'])) {
                // Kembalikan stok
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
                $order->update(['status' => 'cancelled']);
                Log::info("Order {$orderNumber} CANCELLED", [
                    'transaction_status' => $transactionStatus,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Notification processed successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Midtrans Notification Error: ' . $e->getMessage(), [
                'payload' => $request->all(),
            ]);
            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }

    /**
     * Cek status pembayaran manual ke Midtrans
     */
    public function checkStatus($orderNumber)
    {
        try {
            $order = Order::with('payment')
                ->where('order_number', $orderNumber)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if ($order->status === 'paid') {
                return response()->json([
                    'success' => true,
                    'status'  => 'settlement',
                    'message' => 'Sudah dibayar',
                ]);
            }

            $result = $this->midtrans->checkStatus($order->order_number);

            if ($result['success']) {
                $status = $result['data'];

                if ($order->payment) {
                    $order->payment->update([
                        'transaction_status' => $status->transaction_status,
                        'payment_type'       => $status->payment_type ?? $order->payment->payment_type,
                        'metadata'           => json_decode(json_encode($status), true),
                    ]);

                    if (in_array($status->transaction_status, ['capture', 'settlement'])) {
                        $order->update([
                            'status'  => 'paid',
                            'paid_at' => now(),
                        ]);
                    }
                }

                return response()->json([
                    'success' => true,
                    'status'  => $status->transaction_status,
                    'message' => 'Status diperbarui',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);

        } catch (\Exception $e) {
            Log::error('Check status error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal cek status',
            ], 500);
        }
    }
}