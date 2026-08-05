<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $legacy = DB::table('system_settings')->where('key', 'shopping_enabled')->first();
        $current = DB::table('system_settings')->where('key', 'customer_login_enabled')->first();

        if (! $current) {
            DB::table('system_settings')->insert([
                'key'        => 'customer_login_enabled',
                'value'      => $legacy?->value ?? '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($legacy) {
            DB::table('system_settings')->where('id', $legacy->id)->delete();
        }
    }

    public function down(): void
    {
        $current = DB::table('system_settings')->where('key', 'customer_login_enabled')->first();

        if ($current && ! DB::table('system_settings')->where('key', 'shopping_enabled')->exists()) {
            DB::table('system_settings')->insert([
                'key'        => 'shopping_enabled',
                'value'      => $current->value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('system_settings')->where('key', 'customer_login_enabled')->delete();
    }
};
