<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'shipping_label')) {
                $table->string('shipping_label')->nullable()->after('address_id');
            }
            if (!Schema::hasColumn('orders', 'shipping_recipient_name')) {
                $table->string('shipping_recipient_name')->nullable()->after('shipping_label');
            }
            if (!Schema::hasColumn('orders', 'shipping_recipient_phone')) {
                $table->string('shipping_recipient_phone')->nullable()->after('shipping_recipient_name');
            }
            if (!Schema::hasColumn('orders', 'shipping_province_id')) {
                $table->string('shipping_province_id')->nullable()->after('shipping_recipient_phone');
            }
            if (!Schema::hasColumn('orders', 'shipping_province_name')) {
                $table->string('shipping_province_name')->nullable()->after('shipping_province_id');
            }
            if (!Schema::hasColumn('orders', 'shipping_city_id')) {
                $table->string('shipping_city_id')->nullable()->after('shipping_province_name');
            }
            if (!Schema::hasColumn('orders', 'shipping_city_name')) {
                $table->string('shipping_city_name')->nullable()->after('shipping_city_id');
            }
            if (!Schema::hasColumn('orders', 'shipping_city_type')) {
                $table->string('shipping_city_type')->nullable()->after('shipping_city_name');
            }
            if (!Schema::hasColumn('orders', 'shipping_postal_code')) {
                $table->string('shipping_postal_code')->nullable()->after('shipping_city_type');
            }
            if (!Schema::hasColumn('orders', 'shipping_full_address')) {
                $table->text('shipping_full_address')->nullable()->after('shipping_postal_code');
            }
        });

        if (DB::table('orders')->exists() && Schema::hasTable('addresses')) {
            DB::table('orders')
                ->leftJoin('addresses', 'orders.address_id', '=', 'addresses.id')
                ->whereNull('orders.shipping_recipient_name')
                ->whereNotNull('addresses.id')
                ->update([
                    'orders.shipping_label' => DB::raw('addresses.label'),
                    'orders.shipping_recipient_name' => DB::raw('addresses.recipient_name'),
                    'orders.shipping_recipient_phone' => DB::raw('addresses.recipient_phone'),
                    'orders.shipping_province_id' => DB::raw('addresses.province_id'),
                    'orders.shipping_province_name' => DB::raw('addresses.province_name'),
                    'orders.shipping_city_id' => DB::raw('addresses.city_id'),
                    'orders.shipping_city_name' => DB::raw('addresses.city_name'),
                    'orders.shipping_city_type' => DB::raw('addresses.city_type'),
                    'orders.shipping_postal_code' => DB::raw('addresses.postal_code'),
                    'orders.shipping_full_address' => DB::raw('addresses.full_address'),
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            foreach ([
                'shipping_label',
                'shipping_recipient_name',
                'shipping_recipient_phone',
                'shipping_province_id',
                'shipping_province_name',
                'shipping_city_id',
                'shipping_city_name',
                'shipping_city_type',
                'shipping_postal_code',
                'shipping_full_address',
            ] as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
