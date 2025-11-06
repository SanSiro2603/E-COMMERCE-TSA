<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_API_KEY');
        $this->baseUrl = env('RAJAONGKIR_BASE_URL', 'https://api.rajaongkir.com/starter');
    }

    public function provinces()
    {
        $response = Http::withHeaders(['key' => $this->apiKey])
            ->get("{$this->baseUrl}/province");

        return response()->json($response['rajaongkir']['results'] ?? []);
    }

    public function cities(Request $request)
    {
        $provinceId = $request->province_id;

        $response = Http::withHeaders(['key' => $this->apiKey])
            ->get("{$this->baseUrl}/city", ['province' => $provinceId]);

        return response()->json($response['rajaongkir']['results'] ?? []);
    }
}