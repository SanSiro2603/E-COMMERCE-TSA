<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_page_items', function (Blueprint $table) {
            $table->id();
            $table->string('page', 50);
            $table->string('section', 80);
            $table->string('item_key', 100);
            $table->string('title_en')->nullable();
            $table->string('title_id')->nullable();
            $table->longText('description_en')->nullable();
            $table->longText('description_id')->nullable();
            $table->string('image_path')->nullable();
            $table->json('metadata')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['page', 'section', 'item_key']);
            $table->index(['page', 'section', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_page_items');
    }
};
