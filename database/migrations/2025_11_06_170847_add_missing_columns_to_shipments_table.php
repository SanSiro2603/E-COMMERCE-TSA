<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('shipments', function (Blueprint $table) {
        $table->string('courier_service')->nullable()->after('courier');
        $table->json('history')->nullable()->after('delivered_at');
        
        // Update enum status (MySQL 8+ support alter enum)
        $table->enum('status', ['preparing', 'shipped', 'in_transit', 'delivered', 'failed', 'returned'])
              ->default('preparing')
              ->change();
        
        $table->index('status');
        $table->index('tracking_number');
        $table->index('shipped_at');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            //
        });
    }
};
