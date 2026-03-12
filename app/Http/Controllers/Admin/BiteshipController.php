<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    /**
     * Buat order pengiriman di Biteship dari halaman detail pesanan admin.
     * Otomatis update status order ke 'shipped' dan simpan tracking info.
     */
    public function createShipment(Order $order)
    {
        // Validasi: hanya bisa buat shipment jika status paid/processing
        if (!in_array($order->status, ['paid', 'processing'])) {
            return redirect()->back()
                ->with('error', 'Pengiriman Biteship hanya bisa dibuat untuk pesanan berstatus Dibayar atau Diproses.');
        }

        // Jika sudah ada biteship_order_id, jangan buat duplikat
        if ($order->biteship_order_id) {
            return redirect()->back()
                ->with('error', 'Order pengiriman Biteship sudah dibuat sebelumnya. No. Resi: ' . $order->tracking_number);
        }

        $result = $this->biteship->createOrder($order);

        if ($result['success']) {
            $order->update([
                'status'            => 'shipped',
                'biteship_order_id' => $result['biteship_order_id'],
                'tracking_number'   => $result['tracking_number'] ?? null,
                'courier_service'   => $result['courier_service'] ?? null,
                'courier'           => $result['courier'] ?? $order->courier,
                'shipped_at'        => now(),
            ]);

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

    /**
     * Lacak status pengiriman (AJAX endpoint) berdasarkan biteship_order_id.
     */
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
