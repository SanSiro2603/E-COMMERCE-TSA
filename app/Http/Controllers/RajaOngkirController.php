<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirController extends Controller
{
    /**
     * Ambil daftar provinsi
     */
    public function provinces()
    {
        try {
            $apiKey = config('rajaongkir.api_key');
            if (empty($apiKey)) {
                Log::warning('RajaOngkir: API Key not configured');
                return response()->json(['error' => 'API Key not configured'], 500);
            }

            $response = Http::withHeaders(['key' => $apiKey])
                ->timeout(30)
                ->get('https://rajaongkir.komerce.id/api/v1/destination/province');

            if (!$response->successful()) {
                Log::error('RajaOngkir provinces failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json(['error' => 'Failed to fetch provinces'], $response->status());
            }

            $body = $response->json();

            // Support berbagai struktur response
            $rawProvinces = $body['rajaongkir']['results'] ?? $body['data'] ?? $body ?? [];

            if (empty($rawProvinces)) {
                Log::warning('RajaOngkir provinces empty response', ['body' => $body]);
                return response()->json([], 200);
            }

            // Normalisasi data
            $provinces = array_map(function ($p) {
                return [
                    'province_id' => $p['province_id'] ?? $p['id'] ?? null,
                    'name'        => $p['province'] ?? $p['name'] ?? 'Unknown Province',
                ];
            }, $rawProvinces);

            // Filter yang valid
            $provinces = array_filter($provinces, fn($p) => !empty($p['province_id']));

            return response()->json(array_values($provinces));

        } catch (\Exception $e) {
            Log::error('RajaOngkir provinces exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Ambil daftar kota berdasarkan province_id
     */
    public function cities(Request $request)
    {
        $provinceId = $request->query('province_id');

        if (!$provinceId || !is_numeric($provinceId)) {
            return response()->json(['error' => 'province_id required and must be numeric'], 400);
        }

        try {
            $apiKey = config('rajaongkir.api_key');
            if (empty($apiKey)) {
                return response()->json(['error' => 'API Key not configured'], 500);
            }

            $response = Http::withHeaders(['key' => $apiKey])
                ->timeout(30)
                ->get("https://rajaongkir.komerce.id/api/v1/destination/city/{$provinceId}");

            if (!$response->successful()) {
                Log::error('RajaOngkir cities failed', [
                    'province_id' => $provinceId,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json(['error' => 'Failed to fetch cities'], $response->status());
            }

            $body = $response->json();
            $rawCities = $body['rajaongkir']['results'] ?? $body['data'] ?? $body ?? [];

            if (empty($rawCities)) {
                return response()->json([], 200);
            }

            // Normalisasi data kota
            $cities = array_map(function ($c) {
                return [
                    'city_id'   => $c['city_id'] ?? $c['id'] ?? null,
                    'name'      => $c['city_name'] ?? $c['name'] ?? 'Unknown City',
                    'type'      => $c['type'] ?? $c['city_type'] ?? 'Kota', // Kota / Kabupaten
                    'postal_code' => $c['postal_code'] ?? null,
                ];
            }, $rawCities);

            // Filter valid
            $cities = array_filter($cities, fn($c) => !empty($c['city_id']));

            return response()->json(array_values($cities));

        } catch (\Exception $e) {
            Log::error('RajaOngkir cities exception', [
                'province_id' => $provinceId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}