<?php

return [
    'accepted'             => ':attribute harus diterima.',
    'active_url'           => ':attribute bukan URL yang valid.',
    'after'                => ':attribute harus tanggal setelah :date.',
    'after_or_equal'       => ':attribute harus tanggal setelah atau sama dengan :date.',
    'alpha'                => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'           => ':attribute hanya boleh berisi huruf, angka, dan tanda hubung.',
    'alpha_num'            => ':attribute hanya boleh berisi huruf dan angka.',
    'array'                => ':attribute harus berupa array.',
    'before'               => ':attribute harus tanggal sebelum :date.',
    'before_or_equal'      => ':attribute harus tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'numeric' => ':attribute harus antara :min hingga :max.',
        'file'    => ':attribute harus antara :min hingga :max kilobytes.',
        'string'  => ':attribute harus antara :min hingga :max karakter.',
        'array'   => ':attribute harus antara :min hingga :max item.',
    ],
    'boolean'              => ':attribute harus true atau false.',
    'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
    'date'                 => ':attribute bukan tanggal yang valid.',
    'email'                => ':attribute harus berupa alamat email yang valid.',
    'required'             => ':attribute wajib diisi.',
    'string'               => ':attribute harus berupa teks.',
    'min'                  => [
        'string' => ':attribute minimal :min karakter.',
    ],
    'max'                  => [
        'string' => ':attribute maksimal :max karakter.',
    ],
    'same'                 => ':attribute dan :other harus sama.',
    'unique'               => ':attribute sudah digunakan.',
    'attributes' => [
        'name' => 'Nama Lengkap',
        'email' => 'Alamat Email',
        'password' => 'Password',
        'password_confirmation' => 'Konfirmasi Password',
        'phone' => 'Nomor Telepon',
    ],
];
