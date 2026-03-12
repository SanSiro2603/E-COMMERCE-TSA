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
        Schema::table('orders', function (Blueprint $table) {
            // Biteship fields
            if (!Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('courier');
            }
            if (!Schema::hasColumn('orders', 'biteship_order_id')) {
                $table->string('biteship_order_id')->nullable()->after('tracking_number');
            }
            if (!Schema::hasColumn('orders', 'courier_service')) {
                $table->string('courier_service')->nullable()->after('biteship_order_id');
            }

            // Timestamps used by Order model helpers (canBeCancelled, canBeCompleted)
            if (!Schema::hasColumn('orders', 'shipped_at')) {
                $table->timestamp('shipped_at')->nullable()->after('paid_at');
            }
            if (!Schema::hasColumn('orders', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('shipped_at');
            }
            if (!Schema::hasColumn('orders', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('cancelled_at');
            }

            // address_id foreign key — jika belum ada (sudah di migration terpisah)
            if (!Schema::hasColumn('orders', 'address_id')) {
                $table->foreignId('address_id')->nullable()->constrained('addresses')->nullOnDelete()->after('notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = ['tracking_number', 'biteship_order_id', 'courier_service', 'shipped_at', 'cancelled_at', 'completed_at'];
            $existing = array_filter($columns, fn($col) => Schema::hasColumn('orders', $col));
            if ($existing) {
                $table->dropColumn(array_values($existing));
            }
        });
    }
};
