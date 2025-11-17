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
        "BANDA ACEH","SABANG","LANGSA","LHOKSEUMAWE","SUBULUSSALAM",
        "MEDAN","PEMATANGSIANTAR","SIBOLGA","TANJUNGBALAI","BINJAI",
        "PADANGSIDIMPUAN","GUNUNGSITOLI",
        "PADANG","SOLOK","SAWAHLUNTO","PADANG PANJANG","BUKITTINGGI","PAYAKUMBUH","PARIAMAN",
        "PEKANBARU","DUMAI",
        "JAMBI","SUNGAI PENÙH",
        "PALEMBANG","PRABUMULIH","PAGAR ALAM","LUBUKLINGGAU",
        "BENGKULU",
        "BANDAR LAMPUNG","METRO",
        "PANGKAL PINANG",
        "TANJUNG PINANG","BATAM",
        "JAKARTA","BOGOR","DEPOK","TANGERANG","TANGERANG SELATAN","BEKASI",
        "BANDUNG","CIMAHI","CIREBON","BANJAR",
        "SEMARANG","SURAKARTA","MAGELANG","PEKALONGAN","SALATIGA","TEGAL",
        "YOGYAKARTA",
        "SURABAYA","BATU","MADIUN","MOJOKERTO","PASURUAN","PROBOLINGGO","KEDIRI","BLITAR","MALANG",
        "DENPASAR",
        "MATARAM","BIMA",
        "KUPANG",
        "PONTIANAK","SINGKAWANG",
        "BANJARMASIN","BANJARBARU",
        "PALANGKA RAYA",
        "SAMARINDA","BALIKPAPAN","BONTANG",
        "TARAKAN",
        "MANADO","BITUNG","TOMOHON","KOTAMOBAGU",
        "PALU","PARIGI MOUTONG",
        "MAKASSAR","PALOPO","PAREPARE",
        "KENDARI","BAU-BAU",
        "GORONTALO",
        "AMBON","TUAL",
        "TERNATE","TIDORE KEPULAUAN",
        "JAYAPURA","MERAUKE","SORONG",
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
                    'name'        => $p['province'] ?? $p['name'],
                ];
            }, $raw);

            return response()->json($provinces);

        } catch (\Exception $e) {
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
                    } elseif ($name == "KEPULAUAN SERIBU") {
                        $type = "Kabupaten";
                    } else {
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
                    'city_id'   => $c['city_id'] ?? $c['id'],
                    'city_name' => ucwords(strtolower($name)),
                    'type'      => $type
                ];
            }, $raw);

            return response()->json($cities);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}
