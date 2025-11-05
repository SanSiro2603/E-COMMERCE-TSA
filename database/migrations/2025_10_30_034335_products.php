<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('stock')->default(0);
            $table->integer('weight')->default(1000)->comment('Berat dalam gram'); // Tambahan untuk ongkir
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('health_certificate')->nullable(); 
            $table->date('available_from')->nullable(); 
            $table->timestamps();

            $table->index(['category_id', 'is_active']);
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};