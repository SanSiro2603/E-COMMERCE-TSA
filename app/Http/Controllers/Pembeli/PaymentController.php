<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
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
        $order = Order::with(['items.product', 'payment'])
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);

        // Check if order can be paid
        if (!in_array($order->status, ['pending', 'paid'])) {
            return redirect()->route('pembeli.pesanan.show', $order)
                ->with('error', 'Pesanan tidak dapat dibayar pada status ini');
        }

        // Check if payment already exists
        if ($order->payment && $order->payment->snap_token) {
            return view('pembeli.payment.show', [
                'order' => $order,
                'payment' => $order->payment,
                'snapToken' => $order->payment->snap_token,
                'clientKey' => config('midtrans.client_key')
            ]);
        }

        // Create new payment
        try {
            DB::beginTransaction();

            $result = $this->midtrans->createTransaction($order);

            if (!$result['success']) {
                throw new \Exception($result['message']);
            }

            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'gross_amount' => $order->grand_total,
                    'snap_token' => $result['snap_token'],
                    'snap_url' => $result['snap_url'],
                    'transaction_status' => 'pending',
                    'expiry_time' => now()->addHours(24),
                ]
            );

            DB::commit();

            return view('pembeli.payment.show', [
                'order' => $order,
                'payment' => $payment,
                'snapToken' => $result['snap_token'],
                'clientKey' => config('midtrans.client_key')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment creation failed: ' . $e->getMessage());
            
            return redirect()->route('pembeli.pesanan.show', $order)
                ->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Payment finish callback
     */
    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $transactionStatus = $request->transaction_status;

        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            return redirect()->route('pembeli.pesanan.index')
                ->with('error', 'Pesanan tidak ditemukan');
        }

        $message = '';
        
        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                $message = 'Pembayaran berhasil! Pesanan Anda sedang diproses.';
                break;
            case 'pending':
                $message = 'Menunggu pembayaran. Silakan selesaikan pembayaran Anda.';
                break;
            case 'deny':
            case 'expire':
            case 'cancel':
                $message = 'Pembayaran gagal atau dibatalkan.';
                break;
            default:
                $message = 'Status pembayaran: ' . $transactionStatus;
        }

        return redirect()->route('pembeli.pesanan.show', $order)
            ->with('success', $message);
    }

    /**
     * Webhook/Notification handler from Midtrans
     */
    public function notification(Request $request)
    {
        try {
            $payload = $request->all();
            Log::info('Midtrans Notification', $payload);

            $orderId = $payload['order_id'];
            $transactionStatus = $payload['transaction_status'];
            $fraudStatus = $payload['fraud_status'] ?? null;

            $order = Order::where('order_number', $orderId)->first();
            
            if (!$order) {
                Log::error('Order not found: ' . $orderId);
                return response()->json(['message' => 'Order not found'], 404);
            }

            $payment = Payment::where('order_id', $order->id)->first();

            if (!$payment) {
                Log::error('Payment not found for order: ' . $orderId);
                return response()->json(['message' => 'Payment not found'], 404);
            }

            // Update payment record
            $payment->update([
                'transaction_id' => $payload['transaction_id'] ?? null,
                'transaction_status' => $transactionStatus,
                'payment_type' => $payload['payment_type'] ?? null,
                'transaction_time' => $payload['transaction_time'] ?? null,
                'payment_code' => $payload['payment_code'] ?? $payload['bill_key'] ?? null,
                'pdf_url' => $payload['pdf_url'] ?? null,
                'metadata' => $payload,
            ]);

            // Handle order status based on transaction status
            DB::beginTransaction();

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $order->update([
                        'status' => 'paid',
                        'paid_at' => now()
                    ]);
                }
            } elseif ($transactionStatus == 'settlement') {
                $order->update([
                    'status' => 'paid',
                    'paid_at' => now()
                ]);
            } elseif ($transactionStatus == 'pending') {
                // Keep as pending
                $order->update(['status' => 'pending']);
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                // Restore stock
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
                
                $order->update(['status' => 'cancelled']);
            }

            DB::commit();

            return response()->json(['message' => 'Notification processed successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }

    /**
     * Check payment status manually
     */
    public function checkStatus($orderId)
    {
        try {
            $order = Order::with('payment')
                ->where('user_id', Auth::id())
                ->findOrFail($orderId);

            $result = $this->midtrans->checkStatus($order->order_number);

            if ($result['success']) {
                $status = $result['data'];
                
                // Update payment
                if ($order->payment) {
                    $order->payment->update([
                        'transaction_status' => $status->transaction_status,
                        'metadata' => json_decode(json_encode($status), true),
                    ]);

                    // Update order status if paid
                    if (in_array($status->transaction_status, ['capture', 'settlement'])) {
                        $order->update([
                            'status' => 'paid',
                            'paid_at' => now()
                        ]);
                    }
                }

                return response()->json([
                    'success' => true,
                    'status' => $status->transaction_status,
                    'message' => 'Status pembayaran berhasil diperbarui'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);

        } catch (\Exception $e) {
            Log::error('Check Status Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengecek status pembayaran'
            ], 500);
        }
    }
}