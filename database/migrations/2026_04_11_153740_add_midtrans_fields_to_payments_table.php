<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Kolom yang belum ada (dari DESCRIBE)
            if (!Schema::hasColumn('payments', 'order_id_midtrans')) {
                $table->string('order_id_midtrans')->nullable()->after('order_id');
            }

            if (!Schema::hasColumn('payments', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('order_id_midtrans');
            }

            // Kolom yang SUDAH ADA di database - SKIP
            // transaction_id, payment_code, pdf_url, transaction_time

            // Kolom yang belum ada (lanjutan)
            if (!Schema::hasColumn('payments', 'bank')) {
                $table->string('bank')->nullable();
            }

            if (!Schema::hasColumn('payments', 'va_number')) {
                $table->string('va_number')->nullable();
            }

            if (!Schema::hasColumn('payments', 'bill_key')) {
                $table->string('bill_key')->nullable();
            }

            if (!Schema::hasColumn('payments', 'biller_code')) {
                $table->string('biller_code')->nullable();
            }

            if (!Schema::hasColumn('payments', 'store')) {
                $table->string('store')->nullable();
            }

            if (!Schema::hasColumn('payments', 'gopay_url')) {
                $table->string('gopay_url')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('payments', 'order_id_midtrans')) {
                $columnsToDrop[] = 'order_id_midtrans';
            }
            if (Schema::hasColumn('payments', 'payment_type')) {
                $columnsToDrop[] = 'payment_type';
            }
            if (Schema::hasColumn('payments', 'bank')) {
                $columnsToDrop[] = 'bank';
            }
            if (Schema::hasColumn('payments', 'va_number')) {
                $columnsToDrop[] = 'va_number';
            }
            if (Schema::hasColumn('payments', 'bill_key')) {
                $columnsToDrop[] = 'bill_key';
            }
            if (Schema::hasColumn('payments', 'biller_code')) {
                $columnsToDrop[] = 'biller_code';
            }
            if (Schema::hasColumn('payments', 'store')) {
                $columnsToDrop[] = 'store';
            }
            if (Schema::hasColumn('payments', 'gopay_url')) {
                $columnsToDrop[] = 'gopay_url';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};