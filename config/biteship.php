<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Biteship API Configuration
    |--------------------------------------------------------------------------
    */

    'api_key'     => env('BITESHIP_API_KEY'),
    'environment' => env('BITESHIP_ENVIRONMENT', 'sandbox'),
    'base_url'    => 'https://api.biteship.com',

    /*
    |--------------------------------------------------------------------------
    | Origin (Alamat Toko / Pengirim)
    |--------------------------------------------------------------------------
    */
    'origin' => [
        'name'         => env('BITESHIP_ORIGIN_NAME', 'Toko E-Commerce TSA'),
        'address'      => env('BITESHIP_ORIGIN_ADDRESS', 'Jl. Toko Raya No. 1'),
        'district'     => env('BITESHIP_ORIGIN_DISTRICT', 'Teluk Betung Selatan'),
        'city'         => env('BITESHIP_ORIGIN_CITY', 'Bandar Lampung'),
        'province'     => env('BITESHIP_ORIGIN_PROVINCE', 'Lampung'),
        'postal_code'  => env('BITESHIP_ORIGIN_POSTAL', '35218'),
        'phone'        => env('BITESHIP_ORIGIN_PHONE', '08123456789'),
    ],
];
