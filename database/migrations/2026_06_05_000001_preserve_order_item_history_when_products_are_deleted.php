<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'product_name')) {
                $table->string('product_name')->nullable()->after('product_id');
            }
            if (!Schema::hasColumn('order_items', 'product_image')) {
                $table->string('product_image')->nullable()->after('product_name');
            }
            if (!Schema::hasColumn('order_items', 'product_category_name')) {
                $table->string('product_category_name')->nullable()->after('product_image');
            }
        });

        if (DB::table('order_items')->exists()) {
            DB::table('order_items')
                ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->whereNull('order_items.product_name')
                ->whereNotNull('products.id')
                ->update([
                    'order_items.product_name' => DB::raw('products.name'),
                    'order_items.product_image' => DB::raw('products.image'),
                    'order_items.product_category_name' => DB::raw('categories.name'),
                ]);
        }

        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            try {
                DB::statement('ALTER TABLE order_items DROP FOREIGN KEY order_items_product_id_foreign');
            } catch (Throwable $e) {
                // The key may already have been changed on some environments.
            }

            DB::statement('ALTER TABLE order_items MODIFY product_id BIGINT UNSIGNED NULL');

            try {
                DB::statement('ALTER TABLE order_items ADD CONSTRAINT order_items_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL');
            } catch (Throwable $e) {
                // Keep the migration idempotent if the desired constraint already exists.
            }
        }
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            foreach (['product_name', 'product_image', 'product_category_name'] as $column) {
                if (Schema::hasColumn('order_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
