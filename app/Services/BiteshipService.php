<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BiteshipService
{
    protected string $apiKey;
    protected string $baseUrl;
    protected array  $origin;

    public function __construct()
    {
        $this->apiKey  = config('biteship.api_key');
        $this->baseUrl = config('biteship.base_url', 'https://api.biteship.com');
        $this->origin  = config('biteship.origin');
    }

    /**
     * HTTP Client dengan header Biteship
     */
    protected function http()
    {
        return Http::withHeaders([
            'Authorization' => $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->baseUrl($this->baseUrl);
    }

    /**
     * Buat order pengiriman di Biteship
     * Dokumentasi: https://biteship.com/id/docs/api/orders/create
     */
    public function createOrder(Order $order): array
    {
        try {
            $items = $order->items->map(fn($item) => [
                'name'     => $item->product?->name ?? 'Produk',
                'value'    => (int) $item->price,
                'quantity' => $item->quantity,
                'weight'   => $item->product?->weight ?? 1000,
            ])->values()->toArray();

            $totalWeight = $order->items->sum(fn($i) => ($i->product?->weight ?? 1000) * $i->quantity);

            $payload = [
                'shipper_contact_name'      => $this->origin['name'],
                'shipper_contact_phone'     => $this->origin['phone'],
                'shipper_contact_email'     => config('mail.from.address', 'toko@example.com'),
                'shipper_organization'      => $this->origin['name'],
                'origin_contact_name'       => $this->origin['name'],
                'origin_contact_phone'      => $this->origin['phone'],
                'origin_address'            => $this->origin['address'],
                'origin_note'               => '',
                'origin_province_name'      => $this->origin['province'],
                'origin_city_name'          => $this->origin['city'],
                'origin_district_name'      => $this->origin['district'],
                'origin_postal_code'        => $this->origin['postal_code'],

                'destination_contact_name'  => $order->address?->recipient_name ?? '',
                'destination_contact_phone' => $order->address?->recipient_phone ?? '',
                'destination_address'       => $order->address?->full_address ?? '',
                'destination_note'          => '',
                'destination_province_name' => $order->address?->province_name ?? '',
                'destination_city_name'     => $order->address?->city_name ?? '',
                'destination_postal_code'   => $order->address?->postal_code ?? '',

                'courier_company'           => $order->courier ?? 'jne',
                'courier_type'              => strtolower($order->courier_service ?? 'reg'),
                'courier_insurance'         => 0,
                'delivery_type'             => 'now',

                'order_note'                => 'Pesanan #' . $order->order_number,

                'metadata'                  => [],
                'items'                     => $items,
            ];

            $response = $this->http()->post('/v1/orders', $payload);
            $data      = $response->json();

            Log::info('[Biteship] createOrder response', ['order' => $order->order_number, 'response' => $data]);

            if ($response->successful() && isset($data['id'])) {
                return [
                    'success'           => true,
                    'biteship_order_id' => $data['id'],
                    'tracking_number'   => $data['courier']['waybill_id'] ?? null,
                    'courier'           => $data['courier']['company']    ?? $order->courier,
                    'courier_service'   => $data['courier']['type']       ?? null,
                    'origin'            => $data['origin']                ?? null,
                    'destination'       => $data['destination']           ?? null,
                    'raw'               => $data,
                ];
            }

            return [
                'success' => false,
                'message' => $data['error'] ?? $data['message'] ?? 'Gagal membuat order Biteship.',
                'raw'     => $data,
            ];

        } catch (\Exception $e) {
            Log::error('[Biteship] createOrder exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Lacak status pengiriman via Biteship Order ID
     * Dokumentasi: https://biteship.com/id/docs/api/orders/retrieve
     */
    public function trackOrder(string $biteshipOrderId): array
    {
        try {
            $response = $this->http()->get("/v1/orders/{$biteshipOrderId}");
            $data      = $response->json();

            Log::info('[Biteship] trackOrder response', ['id' => $biteshipOrderId, 'response' => $data]);

            if ($response->successful() && isset($data['id'])) {
                $history = collect($data['courier']['history'] ?? [])->map(fn($h) => [
                    'status'      => $h['status']      ?? '',
                    'description' => $h['note']        ?? $h['status'] ?? '',
                    'created_at'  => $h['updated_time'] ?? null,
                ])->toArray();

                return [
                    'success'         => true,
                    'status'          => $data['status'] ?? null,
                    'courier_status'  => $data['courier']['status']      ?? null,
                    'tracking_number' => $data['courier']['waybill_id']  ?? null,
                    'courier'         => $data['courier']['company']     ?? null,
                    'history'         => $history,
                    'raw'             => $data,
                ];
            }

            return [
                'success' => false,
                'message' => $data['error'] ?? $data['message'] ?? 'Gagal melacak pengiriman.',
            ];

        } catch (\Exception $e) {
            Log::error('[Biteship] trackOrder exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Batalkan order di Biteship
     */
    public function cancelOrder(string $biteshipOrderId): array
    {
        try {
            $response = $this->http()->delete("/v1/orders/{$biteshipOrderId}");
            $data      = $response->json();

            return [
                'success' => $response->successful(),
                'message' => $data['message'] ?? 'Order Biteship dibatalkan.',
            ];
        } catch (\Exception $e) {
            Log::error('[Biteship] cancelOrder exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Cek harga ongkir (Rates)
     * Dokumentasi: https://biteship.com/id/docs/api/rates/retrieve
     */
    public function getRates(array $payload): array
    {
        try {
            $formattedPayload = [
                'origin_postal_code'        => $this->origin['postal_code'],
                'destination_postal_code'   => $payload['destination_postal_code'] ?? '',
                'couriers'                  => $payload['couriers'] ?? 'jne,jnt,sicepat,anteraja,pos,tiki',
                'items'                     => [
                    [
                        'name'   => 'Paket E-Commerce',
                        'value'  => $payload['items_value'] ?? 100000,
                        'weight' => $payload['weight'] ?? 1000, // dalam gram
                        'quantity' => 1
                    ]
                ]
            ];

            // Jika ada info tambahan untuk origin dan destination
            if (!empty($payload['origin_area_id'])) {
                $formattedPayload['origin_area_id'] = $payload['origin_area_id'];
            }
            if (!empty($payload['destination_area_id'])) {
                $formattedPayload['destination_area_id'] = $payload['destination_area_id'];
            }
            
            // Tambahkan koordinat origin jika ada di origin setting
            if (isset($this->origin['latitude']) && isset($this->origin['longitude'])) {
                $formattedPayload['origin_latitude'] = $this->origin['latitude'];
                $formattedPayload['origin_longitude'] = $this->origin['longitude'];
            }

            Log::info('[Biteship] HTTP POST /v1/rates/couriers payload:', $formattedPayload);
            $response = $this->http()->post('/v1/rates/couriers', $formattedPayload);
            $data = $response->json();

            Log::info('[Biteship] getRates response status: ' . $response->status());

            if ($response->successful() && isset($data['pricing'])) {
                return [
                    'success' => true,
                    'pricing' => $data['pricing'],
                    'raw'     => $data
                ];
            }

            return [
                'success' => false,
                'message' => $data['error'] ?? $data['message'] ?? 'Gagal cek ongkir Biteship.',
                'raw'     => $data
            ];

        } catch (\Exception $e) {
            Log::error('[Biteship] getRates exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
