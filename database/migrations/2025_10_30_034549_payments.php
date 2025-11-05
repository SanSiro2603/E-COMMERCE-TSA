<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('payment_type')->nullable();
            $table->string('transaction_id')->unique()->nullable();
            $table->string('transaction_status')->nullable();
            $table->decimal('gross_amount', 12, 2);
            $table->string('payment_code')->nullable();
            $table->string('pdf_url')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->timestamp('expiry_time')->nullable();
            $table->text('snap_token')->nullable();
            $table->text('snap_url')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('transaction_id');
            $table->index('transaction_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};