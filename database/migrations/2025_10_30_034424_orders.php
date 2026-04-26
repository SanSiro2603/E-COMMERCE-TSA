<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('order_number')->unique();

            // Pricing
            $table->decimal('subtotal', 12, 2); // Total harga produk sebelum ongkir
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('grand_total', 12, 2);

            // Status
            $table->enum('status', ['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'])
                ->default('pending');


            // Payment
            $table->string('payment_method')->nullable();
            $table->timestamp('paid_at')->nullable();

            // Notes
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes(); // Untuk cancel order

            $table->index(['user_id', 'status']);
            $table->index('order_number');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};