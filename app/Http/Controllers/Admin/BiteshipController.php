<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\LogHelper;
use App\Models\Order;
use App\Services\BiteshipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BiteshipController extends Controller
{
    protected BiteshipService $biteship;

    public function __construct(BiteshipService $biteship)
    {
        $this->biteship = $biteship;
    }

    public function createShipment(Order $order)
    {
        $order->loadMissing(['address', 'shippingSnapshot', 'items.product.category']);

        if (!in_array($order->status, ['paid', 'processing'])) {
            return redirect()->back()
                ->with('error', 'Pengiriman Biteship hanya bisa dibuat untuk pesanan berstatus Dibayar atau Diproses.');
        }

        if ($order->biteship_order_id) {
            return redirect()->back()
                ->with('error', 'Order pengiriman Biteship sudah dibuat sebelumnya. No. Resi: ' . $order->tracking_number);
        }

        if ($order->items->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Pengiriman tidak bisa dibuat karena item pesanan tidak tersedia.');
        }

        if (!$order->hasCompleteShippingAddress()) {
            return redirect()->back()
                ->with('error', 'Pengiriman Biteship belum bisa dibuat. Data alamat historis pesanan belum lengkap.');
        }

        $result = $this->biteship->createOrder($order);

        if ($result['success']) {
            $previousStatus = $order->status;

            $order->update([
                'status'            => 'shipped',
                'biteship_order_id' => $result['biteship_order_id'],
                'tracking_number'   => $result['tracking_number'] ?? null,
                'courier_service'   => $result['courier_service'] ?? null,
                'courier'           => $result['courier'] ?? $order->courier,
                'shipped_at'        => now(),
            ]);

            LogHelper::record(
                'Buat Pengiriman Pesanan',
                "Membuat pengiriman Biteship untuk pesanan #{$order->order_number}. Status berubah dari {$previousStatus} menjadi shipped." .
                ($order->tracking_number ? " No. Resi: {$order->tracking_number}." : '')
            );

            return redirect()->route('admin.orders.show', $order)
                ->with('success',
                    'Order pengiriman Biteship berhasil dibuat! ' .
                    'Status pesanan diubah ke Dikirim. ' .
                    ($result['tracking_number'] ? 'No. Resi: ' . $result['tracking_number'] : 'Nomor resi akan tersedia setelah kurir mengambil paket.')
                );
        }

        Log::error('[BiteshipController] createShipment failed', [
            'order_id' => $order->id,
            'result'   => $result,
        ]);

        return redirect()->back()
            ->with('error', 'Gagal membuat order Biteship: ' . ($result['message'] ?? 'Unknown error'));
    }

    public function trackShipment(Order $order)
    {
        if (!$order->biteship_order_id) {
            return response()->json([
                'success' => false,
                'message' => 'Order ini belum memiliki ID pengiriman Biteship.',
            ]);
        }

        $result = $this->biteship->trackOrder($order->biteship_order_id);

        return response()->json($result);
    }
}
