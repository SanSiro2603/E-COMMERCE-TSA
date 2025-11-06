<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirController extends Controller
{
    /**
     * Menampilkan daftar provinsi dari API Raja Ongkir
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function provinces()
    {
        try {
            // Validasi API Key
            $apiKey = config('rajaongkir.api_key');
            
            if (empty($apiKey)) {
                Log::error('RajaOngkir: API Key tidak ditemukan di config');
                return response()->json([
                    'error' => 'API Key not configured'
                ], 500);
            }

            Log::info('RajaOngkir: Fetching provinces');

            // Request ke API
            $response = Http::withHeaders([
                'key' => $apiKey,
                'Accept' => 'application/json',
            ])
            ->timeout(30)
            ->get('https://rajaongkir.komerce.id/api/v1/destination/province');

            // Log status response
            Log::info('RajaOngkir provinces response', [
                'status' => $response->status(),
                'successful' => $response->successful()
            ]);

            // Cek jika request gagal
            if (!$response->successful()) {
                Log::error('RajaOngkir provinces error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json([
                    'error' => 'Failed to fetch provinces',
                    'status' => $response->status()
                ], $response->status());
            }

            $body = $response->json();

            // Log sample data untuk debugging
            Log::info('RajaOngkir provinces data received', [
                'has_data_key' => isset($body['data']),
                'is_array' => is_array($body),
                'count' => is_array($body) ? count($body) : 0,
                'sample' => is_array($body) ? array_slice($body, 0, 2) : $body
            ]);

            // Auto-detect format response
            // Format 1: Langsung array of provinces
            if (is_array($body) && count($body) > 0 && isset($body[0]['province_id'])) {
                Log::info('RajaOngkir: Using direct array format');
                return response()->json($body);
            }

            // Format 2: Ada wrapper 'data'
            if (isset($body['data']) && is_array($body['data'])) {
                Log::info('RajaOngkir: Using wrapped data format', [
                    'count' => count($body['data'])
                ]);
                return response()->json($body['data']);
            }

            
            Log::warning('RajaOngkir: Unknown response format', [
                'body_structure' => array_keys($body ?? [])
            ]);
            
            return response()->json([]);

        } catch (\Exception $e) {
            Log::error('RajaOngkir provinces exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan daftar kota berdasarkan provinsi dari API Raja Ongkir
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cities(Request $request)
    {
        try {
            // Validasi province_id
            $provinceId = $request->query('province_id');
            
            if (!$provinceId) {
                Log::warning('RajaOngkir cities: province_id not provided');
                return response()->json([
                    'error' => 'province_id is required'
                ], 400);
            }

            // Validasi API Key
            $apiKey = config('rajaongkir.api_key');
            
            if (empty($apiKey)) {
                Log::error('RajaOngkir: API Key tidak ditemukan di config');
                return response()->json([
                    'error' => 'API Key not configured'
                ], 500);
            }

            Log::info('RajaOngkir: Fetching cities', [
                'province_id' => $provinceId
            ]);

            // Request ke API
            $response = Http::withHeaders([
                'key' => $apiKey,
                'Accept' => 'application/json',
            ])
            ->timeout(30)
            ->get("https://rajaongkir.komerce.id/api/v1/destination/city/{$provinceId}");

            // Log status response
            Log::info('RajaOngkir cities response', [
                'province_id' => $provinceId,
                'status' => $response->status(),
                'successful' => $response->successful()
            ]);

            // Cek jika request gagal
            if (!$response->successful()) {
                Log::error('RajaOngkir cities error', [
                    'province_id' => $provinceId,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json([
                    'error' => 'Failed to fetch cities',
                    'status' => $response->status()
                ], $response->status());
            }

            $body = $response->json();

            // Log sample data
            Log::info('RajaOngkir cities data received', [
                'province_id' => $provinceId,
                'has_data_key' => isset($body['data']),
                'is_array' => is_array($body),
                'count' => is_array($body) ? count($body) : 0
            ]);

            // Auto-detect format response
            // Format 1: Langsung array of cities
            if (is_array($body) && count($body) > 0 && isset($body[0]['city_id'])) {
                Log::info('RajaOngkir cities: Using direct array format');
                return response()->json($body);
            }

            // Format 2: Ada wrapper 'data'
            if (isset($body['data']) && is_array($body['data'])) {
                Log::info('RajaOngkir cities: Using wrapped data format', [
                    'count' => count($body['data'])
                ]);
                return response()->json($body['data']);
            }

            // Jika format tidak dikenali
            Log::warning('RajaOngkir cities: Unknown response format', [
                'province_id' => $provinceId,
                'body_structure' => array_keys($body ?? [])
            ]);
            
            return response()->json([]);

        } catch (\Exception $e) {
            Log::error('RajaOngkir cities exception', [
                'province_id' => $provinceId ?? null,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}