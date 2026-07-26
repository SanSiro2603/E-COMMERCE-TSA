<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('admin_logs', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->after('description');
            $table->string('device_type')->nullable()->after('ip_address');
            $table->string('device_name')->nullable()->after('device_type');
            $table->string('operating_system')->nullable()->after('device_name');
            $table->string('browser')->nullable()->after('operating_system');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_logs', function (Blueprint $table) {
            $table->dropColumn([
                'ip_address',
                'device_type',
                'device_name',
                'operating_system',
                'browser',
            ]);
        });
    }
};
