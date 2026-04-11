<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('order_id_midtrans')->nullable()->after('order_id');
            $table->string('payment_type')->nullable()->after('order_id_midtrans');
            $table->string('transaction_id')->nullable()->after('payment_type');
            $table->string('payment_code')->nullable()->after('transaction_id');
            $table->string('pdf_url')->nullable()->after('payment_code');
            $table->timestamp('transaction_time')->nullable()->after('pdf_url');
            $table->string('bank')->nullable()->after('transaction_time');
            $table->string('va_number')->nullable()->after('bank');
            $table->string('bill_key')->nullable()->after('va_number');
            $table->string('biller_code')->nullable()->after('bill_key');
            $table->string('store')->nullable()->after('biller_code');
            $table->string('gopay_url')->nullable()->after('store');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'order_id_midtrans',
                'payment_type',
                'transaction_id',
                'payment_code',
                'pdf_url',
                'transaction_time',
                'bank',
                'va_number',
                'bill_key',
                'biller_code',
                'store',
                'gopay_url',
            ]);
        });
    }
};