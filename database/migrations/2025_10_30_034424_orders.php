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
            
            // Shipping Information
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->text('shipping_address');
            $table->string('province'); // Nama provinsi
            $table->string('province_id'); // ID provinsi dari RajaOngkir
            $table->string('city'); // Nama kota
            $table->string('city_id'); // ID kota dari RajaOngkir
            $table->string('postal_code')->nullable();
            
            // Courier Information
            $table->string('courier')->nullable(); // jne, tiki, pos
            $table->string('courier_service')->nullable(); // REG, YES, OKE
            $table->string('courier_service_description')->nullable(); // Layanan Regular, dll
            $table->integer('estimated_delivery')->nullable(); // estimasi hari
            $table->string('tracking_number')->nullable();
            
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