<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirController extends Controller
{
    /**
     * LIST KOTA RESMI INDONESIA
     * (jika nama ada di list ini → otomatis type = Kota)
     */
    private $cityList = [
        "BANDA ACEH", "SABANG", "LANGSA", "LHOKSEUMAWE", "SUBULUSSALAM",
        "MEDAN", "PEMATANGSIANTAR", "SIBOLGA", "TANJUNGBALAI", "BINJAI",
        "PADANGSIDIMPUAN", "GUNUNGSITOLI",
        "PADANG", "SOLOK", "SAWAHLUNTO", "PADANG PANJANG", "BUKITTINGGI", "PAYAKUMBUH", "PARIAMAN",
        "PEKANBARU", "DUMAI",
        "JAMBI", "SUNGAI PENÙH",
        "PALEMBANG", "PRABUMULIH", "PAGAR ALAM", "LUBUKLINGGAU",
        "BENGKULU",
        "BANDAR LAMPUNG", "METRO",
        "PANGKAL PINANG",
        "TANJUNG PINANG", "BATAM",
        "JAKARTA", "BOGOR", "DEPOK", "TANGERANG", "TANGERANG SELATAN", "BEKASI",
        "BANDUNG", "CIMAHI", "CIREBON", "BANJAR",
        "SEMARANG", "SURAKARTA", "MAGELANG", "PEKALONGAN", "SALATIGA", "TEGAL",
        "YOGYAKARTA",
        "SURABAYA", "BATU", "MADIUN", "MOJOKERTO", "PASURUAN", "PROBOLINGGO", "KEDIRI", "BLITAR", "MALANG",
        "DENPASAR",
        "MATARAM", "BIMA",
        "KUPANG",
        "PONTIANAK", "SINGKAWANG",
        "BANJARMASIN", "BANJARBARU",
        "PALANGKA RAYA",
        "SAMARINDA", "BALIKPAPAN", "BONTANG",
        "TARAKAN",
        "MANADO", "BITUNG", "TOMOHON", "KOTAMOBAGU",
        "PALU", "PARIGI MOUTONG",
        "MAKASSAR", "PALOPO", "PAREPARE",
        "KENDARI", "BAU-BAU",
        "GORONTALO",
        "AMBON", "TUAL",
        "TERNATE", "TIDORE KEPULAUAN",
        "JAYAPURA", "MERAUKE", "SORONG",
    ];

    /**
     * Tambahan khusus Lampung (karena API salah)
     */
    private $lampungCities = ["BANDAR LAMPUNG", "METRO"];

    /**
     * Tambahan khusus DKI Jakarta (API salah semua)
     */
    private $jakartaCities = [
        "JAKARTA SELATAN",
        "JAKARTA TIMUR",
        "JAKARTA PUSAT",
        "JAKARTA BARAT",
        "JAKARTA UTARA"
    ];

    /**
     * ======================================================
     * 1. LIST PROVINSI
     * ======================================================
     */
    public function provinces()
    {
        try {

            $apiKey = config('rajaongkir.api_key');

            $response = Http::withHeaders(['key' => $apiKey])
                ->timeout(30)
                ->get('https://rajaongkir.komerce.id/api/v1/destination/province');

            if (!$response->successful()) {
                return response()->json(['error' => 'Failed'], 500);
            }

            $body = $response->json();

            $raw = $body['rajaongkir']['results']
                ?? $body['data']
                ?? $body
                ?? [];

            $provinces = array_map(function ($p) {
                return [
                'province_id' => $p['province_id'] ?? $p['id'],
                'name' => $p['province'] ?? $p['name'],
                ];
            }, $raw);

            return response()->json($provinces);

        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * ======================================================
     * 2. CITIES WITH FIXED TYPE
     * ======================================================
     */
    public function cities(Request $request)
    {
        $provinceId = $request->query('province_id');

        if (!$provinceId) {
            return response()->json(['error' => 'province_id required'], 400);
        }

        try {
            $apiKey = config('rajaongkir.api_key');

            $response = Http::withHeaders(['key' => $apiKey])
                ->timeout(30)
                ->get("https://rajaongkir.komerce.id/api/v1/destination/city/{$provinceId}");

            if (!$response->successful()) {
                return response()->json(['error' => 'Failed'], 500);
            }

            $body = $response->json();
            $raw = $body['rajaongkir']['results']
                ?? $body['data']
                ?? $body
                ?? [];

            $cities = array_map(function ($c) use ($provinceId) {

                $name = strtoupper($c['city_name'] ?? $c['name']);
                $originalType = $c['type'] ?? "Kota";

                /**
                 * FIX KHUSUS PROVINSI LAMPUNG (ID = 8)
                 */
                if ($provinceId == 8) {
                    $type = in_array($name, $this->lampungCities) ? "Kota" : "Kabupaten";
                }

                /**
                 * FIX KHUSUS DKI JAKARTA (ID = 10)
                 */
                elseif ($provinceId == 10) {

                    if (in_array($name, $this->jakartaCities)) {
                        $type = "Kota"; // Kota Administrasi
                    }
                    elseif ($name == "KEPULAUAN SERIBU") {
                        $type = "Kabupaten";
                    }
                    else {
                        $type = "Kota";
                    }
                }

                /**
                 * FIX PROVINSI LAIN → pakai daftar resmi kota
                 */
                else {
                    $type = in_array($name, $this->cityList)
                        ? "Kota"
                        : "Kabupaten";
                }

                return [
                'city_id' => $c['city_id'] ?? $c['id'],
                'city_name' => ucwords(strtolower($name)),
                'type' => $type
                ];
            }, $raw);

            return response()->json($cities);

        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * ======================================================
     * 3. HITUNG ONGKIR (DOMESTIC COST)
     * ======================================================
     * Origin: Bandar Lampung (city_id = 114 di RajaOngkir)
     */
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'destination' => 'required|string', // city_id tujuan
            'weight' => 'required|numeric|min:1',
            'courier' => 'required|string', // misal: jne atau jne:pos:tiki
        ]);

        try {
            $apiKey = config('rajaongkir.api_key');
            $origin = config('rajaongkir.origin_city_id', '114'); // Bandar Lampung

            $response = Http::asForm()
                ->withHeaders(['key' => $apiKey])
                ->timeout(30)
                ->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
                'origin'          => $origin,
                'originType'      => 'city',
                'destination'     => $request->destination,
                'destinationType' => 'city',
                'weight'          => (int)$request->weight,
                'courier'         => $request->courier,
            ]);

            if (!$response->successful()) {
                Log::error('RajaOngkir calculate error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return response()->json(['error' => 'Gagal menghubungi API RajaOngkir'], 500);
            }

            $body = $response->json();

            // Normalisasi berbagai format respons API
            $results = $body['rajaongkir']['results']
                ?? $body['data']
                ?? $body['results']
                ?? [];

            if (empty($results)) {
                return response()->json(['error' => 'Tidak ada layanan kurir tersedia untuk rute ini'], 404);
            }

            // Flattening: ubah nested costs menjadi flat list layanan
            $services = [];
            foreach ($results as $item) {
                $courierCode = $item['code'] ?? $item['courier'] ?? $request->courier;
                $courierName = $item['name'] ?? strtoupper($courierCode);

                // Jika format bersarang (Standard RajaOngkir)
                if (isset($item['costs']) && is_array($item['costs'])) {
                    foreach ($item['costs'] as $costItem) {
                        $price = $costItem['cost'][0]['value'] ?? $costItem['price'] ?? 0;
                        $etd   = $costItem['cost'][0]['etd']   ?? $costItem['etd']   ?? '-';
                        $services[] = [
                            'courier'      => strtolower($courierCode),
                            'courier_name' => strtoupper($courierName),
                            'service'      => $costItem['service'] ?? $costItem['type'] ?? 'REG',
                            'description'  => $costItem['description'] ?? '',
                            'price'        => (int) $price,
                            'etd'          => $etd,
                        ];
                    }
                }
                // Jika format flat (Komerce API)
                else {
                    $price = $item['cost'] ?? $item['price'] ?? 0;
                    if (is_array($price)) $price = $price[0]['value'] ?? 0;

                    $nameParts = explode(' ', $courierName);
                    $courierFirstName = reset($nameParts);

                    $services[] = [
                        'courier'      => strtolower($courierCode),
                        'courier_name' => $courierFirstName, // Ambil awalan saja
                        'service'      => $item['service'] ?? 'REG',
                        'description'  => $item['description'] ?? '',
                        'price'        => (int) $price,
                        'etd'          => $item['etd'] ?? '-',
                    ];
                }
            }

            if (empty($services)) {
                return response()->json(['error' => 'Tidak ada layanan kurir tersedia untuk rute ini'], 404);
            }

            // Urutkan dari harga terendah
            usort($services, fn($a, $b) => $a['price'] - $b['price']);

            return response()->json(['services' => $services]);

        }
        catch (\Exception $e) {
            Log::error('RajaOngkir calculateShipping exception: ' . $e->getMessage());
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }
}
