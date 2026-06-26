<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_shipping_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained('orders')->cascadeOnDelete();
            $table->string('label')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->string('province_id')->nullable();
            $table->string('province_name')->nullable()->index();
            $table->string('city_id')->nullable();
            $table->string('city_name')->nullable();
            $table->string('city_type')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('full_address')->nullable();
            $table->timestamps();
        });

        if (Schema::hasColumn('orders', 'shipping_recipient_name')) {
            DB::table('orders')
                ->where(function ($query) {
                    $query->whereNotNull('shipping_recipient_name')
                        ->orWhereNotNull('shipping_full_address')
                        ->orWhereNotNull('shipping_province_name');
                })
                ->orderBy('id')
                ->select([
                    'id',
                    'shipping_label',
                    'shipping_recipient_name',
                    'shipping_recipient_phone',
                    'shipping_province_id',
                    'shipping_province_name',
                    'shipping_city_id',
                    'shipping_city_name',
                    'shipping_city_type',
                    'shipping_postal_code',
                    'shipping_full_address',
                    'created_at',
                    'updated_at',
                ])
                ->chunkById(100, function ($orders) {
                    foreach ($orders as $order) {
                        DB::table('order_shipping_snapshots')->updateOrInsert(
                            ['order_id' => $order->id],
                            [
                                'label' => $order->shipping_label,
                                'recipient_name' => $order->shipping_recipient_name,
                                'recipient_phone' => $order->shipping_recipient_phone,
                                'province_id' => $order->shipping_province_id,
                                'province_name' => $order->shipping_province_name,
                                'city_id' => $order->shipping_city_id,
                                'city_name' => $order->shipping_city_name,
                                'city_type' => $order->shipping_city_type,
                                'postal_code' => $order->shipping_postal_code,
                                'full_address' => $order->shipping_full_address,
                                'created_at' => $order->created_at,
                                'updated_at' => $order->updated_at,
                            ]
                        );
                    }
                }, 'id');
        }

        Schema::table('orders', function (Blueprint $table) {
            foreach ($this->oldColumns() as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'shipping_label')) {
                $table->string('shipping_label')->nullable()->after('address_id');
            }
            if (!Schema::hasColumn('orders', 'shipping_recipient_name')) {
                $table->string('shipping_recipient_name')->nullable()->after('shipping_label');
            }
            if (!Schema::hasColumn('orders', 'shipping_recipient_phone')) {
                $table->string('shipping_recipient_phone')->nullable()->after('shipping_recipient_name');
            }
            if (!Schema::hasColumn('orders', 'shipping_province_id')) {
                $table->string('shipping_province_id')->nullable()->after('shipping_recipient_phone');
            }
            if (!Schema::hasColumn('orders', 'shipping_province_name')) {
                $table->string('shipping_province_name')->nullable()->after('shipping_province_id');
            }
            if (!Schema::hasColumn('orders', 'shipping_city_id')) {
                $table->string('shipping_city_id')->nullable()->after('shipping_province_name');
            }
            if (!Schema::hasColumn('orders', 'shipping_city_name')) {
                $table->string('shipping_city_name')->nullable()->after('shipping_city_id');
            }
            if (!Schema::hasColumn('orders', 'shipping_city_type')) {
                $table->string('shipping_city_type')->nullable()->after('shipping_city_name');
            }
            if (!Schema::hasColumn('orders', 'shipping_postal_code')) {
                $table->string('shipping_postal_code')->nullable()->after('shipping_city_type');
            }
            if (!Schema::hasColumn('orders', 'shipping_full_address')) {
                $table->text('shipping_full_address')->nullable()->after('shipping_postal_code');
            }
        });

        if (Schema::hasTable('order_shipping_snapshots')) {
            DB::table('order_shipping_snapshots')
                ->orderBy('order_id')
                ->chunkById(100, function ($snapshots) {
                    foreach ($snapshots as $snapshot) {
                        DB::table('orders')
                            ->where('id', $snapshot->order_id)
                            ->update([
                                'shipping_label' => $snapshot->label,
                                'shipping_recipient_name' => $snapshot->recipient_name,
                                'shipping_recipient_phone' => $snapshot->recipient_phone,
                                'shipping_province_id' => $snapshot->province_id,
                                'shipping_province_name' => $snapshot->province_name,
                                'shipping_city_id' => $snapshot->city_id,
                                'shipping_city_name' => $snapshot->city_name,
                                'shipping_city_type' => $snapshot->city_type,
                                'shipping_postal_code' => $snapshot->postal_code,
                                'shipping_full_address' => $snapshot->full_address,
                            ]);
                    }
                }, 'id');
        }

        Schema::dropIfExists('order_shipping_snapshots');
    }

    private function oldColumns(): array
    {
        return [
            'shipping_label',
            'shipping_recipient_name',
            'shipping_recipient_phone',
            'shipping_province_id',
            'shipping_province_name',
            'shipping_city_id',
            'shipping_city_name',
            'shipping_city_type',
            'shipping_postal_code',
            'shipping_full_address',
        ];
    }
};
