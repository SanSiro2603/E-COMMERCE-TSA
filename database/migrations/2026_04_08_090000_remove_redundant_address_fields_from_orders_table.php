<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Hapus field-field alamat yang redundan dari tabel orders.
     * Data alamat sudah tersimpan di tabel `addresses` dan diakses via `address_id`.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop foreign key dulu jika ada sebelum drop column
            // (address_id sudah ada, tidak perlu diubah)

            $redundantColumns = [
                'recipient_name',
                'recipient_phone',
                'shipping_address',
                'province',
                'province_id',
                'city',
                'city_id',
                'postal_code',
            ];

            // Filter hanya kolom yang benar-benar ada
            $existing = array_filter(
                $redundantColumns,
                fn($col) => Schema::hasColumn('orders', $col)
            );

            if (!empty($existing)) {
                $table->dropColumn(array_values($existing));
            }
        });
    }

    /**
     * Rollback: kembalikan field-field tersebut jika diperlukan.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'recipient_name')) {
                $table->string('recipient_name')->nullable()->after('status');
            }
            if (!Schema::hasColumn('orders', 'recipient_phone')) {
                $table->string('recipient_phone')->nullable()->after('recipient_name');
            }
            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable()->after('recipient_phone');
            }
            if (!Schema::hasColumn('orders', 'province')) {
                $table->string('province')->nullable()->after('shipping_address');
            }
            if (!Schema::hasColumn('orders', 'province_id')) {
                $table->string('province_id')->nullable()->after('province');
            }
            if (!Schema::hasColumn('orders', 'city')) {
                $table->string('city')->nullable()->after('province_id');
            }
            if (!Schema::hasColumn('orders', 'city_id')) {
                $table->string('city_id')->nullable()->after('city');
            }
            if (!Schema::hasColumn('orders', 'postal_code')) {
                $table->string('postal_code')->nullable()->after('city_id');
            }
        });
    }
};
