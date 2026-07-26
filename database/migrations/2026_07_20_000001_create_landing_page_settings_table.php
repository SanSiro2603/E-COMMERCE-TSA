<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page', 50);
            $table->string('key', 100);
            $table->longText('value_en')->nullable();
            $table->longText('value_id')->nullable();
            $table->string('asset_path')->nullable();
            $table->timestamps();

            $table->unique(['page', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_page_settings');
    }
};
