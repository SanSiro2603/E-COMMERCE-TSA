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
            if (! Schema::hasColumn('admin_logs', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('severity');
            }
            if (! Schema::hasColumn('admin_logs', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_logs', function (Blueprint $table) {
            $columnsToDrop = array_filter(['latitude', 'longitude'], function ($col) {
                return Schema::hasColumn('admin_logs', $col);
            });
            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
