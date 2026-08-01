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
            if (! Schema::hasColumn('admin_logs', 'module')) {
                $table->string('module')->nullable()->after('action');
            }
            if (! Schema::hasColumn('admin_logs', 'model_id')) {
                $table->unsignedBigInteger('model_id')->nullable()->after('module');
            }
            if (! Schema::hasColumn('admin_logs', 'old_values')) {
                $table->json('old_values')->nullable()->after('description');
            }
            if (! Schema::hasColumn('admin_logs', 'new_values')) {
                $table->json('new_values')->nullable()->after('old_values');
            }
            if (! Schema::hasColumn('admin_logs', 'severity')) {
                $table->string('severity')->default('info')->after('new_values');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_logs', function (Blueprint $table) {
            $columnsToDrop = array_filter(['module', 'model_id', 'old_values', 'new_values', 'severity'], function ($col) {
                return Schema::hasColumn('admin_logs', $col);
            });
            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
