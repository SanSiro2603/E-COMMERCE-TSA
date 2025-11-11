<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        // Ambil kategori aktif, atau buat satu default
        $categories = Category::where('is_active', true)->pluck('id');
        if ($categories->isEmpty()) {
            $categories = collect([
                Category::create([
                    'name' => 'Umum',
                    'slug' => 'umum',
                    'description' => 'Kategori default untuk produk dummy',
                    'is_active' => true
                ])->id
            ]);
        }

        for ($i = 1; $i <= 100; $i++) {
            $name = ucfirst($faker->words(rand(2, 4), true));

            Product::create([
                'category_id' => $faker->randomElement($categories->toArray()),
                'name' => $name,
                'slug' => Str::slug($name) . '-' . $i,
                'description' => $faker->sentence(rand(10, 20)),
                'price' => $faker->numberBetween(5000, 500000),
                'stock' => $faker->numberBetween(0, 100),
                'weight' => $faker->randomFloat(2, 0.1, 5),
                'unit' => $faker->randomElement(['kg', 'pcs', 'liter', 'pack']),
                'image' => null,
                'health_certificate' => null,
                'available_from' => $faker->optional()->dateTimeBetween('-1 month', '+1 month'),
                'is_active' => $faker->boolean(85),
            ]);
        }
    }
}
