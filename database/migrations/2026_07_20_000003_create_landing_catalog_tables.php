<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_catalog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name_en');
            $table->string('name_id')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_id')->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_alt_en')->nullable();
            $table->string('image_alt_id')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('landing_catalog_families', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('landing_catalog_categories')->restrictOnDelete();
            $table->string('slug');
            $table->string('name_en');
            $table->string('name_id')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['category_id', 'slug']);
        });

        Schema::create('landing_catalog_animals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('landing_catalog_categories')->restrictOnDelete();
            $table->foreignId('family_id')->constrained('landing_catalog_families')->restrictOnDelete();
            $table->string('slug')->unique();
            $table->string('name_en');
            $table->string('name_id')->nullable();
            $table->string('latin_name')->nullable();
            $table->string('main_image_path')->nullable();
            $table->string('main_image_alt_en')->nullable();
            $table->string('main_image_alt_id')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_id')->nullable();
            $table->text('details_en')->nullable();
            $table->text('details_id')->nullable();
            $table->text('shipping_en')->nullable();
            $table->text('shipping_id')->nullable();
            $table->text('care_en')->nullable();
            $table->text('care_id')->nullable();
            $table->text('legal_en')->nullable();
            $table->text('legal_id')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('landing_catalog_animal_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained('landing_catalog_animals')->cascadeOnDelete();
            $table->string('image_path');
            $table->string('alt_en')->nullable();
            $table->string('alt_id')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_catalog_animal_images');
        Schema::dropIfExists('landing_catalog_animals');
        Schema::dropIfExists('landing_catalog_families');
        Schema::dropIfExists('landing_catalog_categories');
    }
};
