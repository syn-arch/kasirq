<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'product_name' => 'Sabun batang',
            'price' => 3000,
        ]);

        Product::create([
            'product_name' => 'Mi Instan',
            'price' => 2000,
        ]);

        Product::create([
            'product_name' => 'Kopi sachet',
            'price' => 1500,
        ]);

        Product::create([
            'product_name' => 'Sabun batang',
            'price' => 3000,
        ]);

        Product::create([
            'product_name' => 'Air minum galon',
            'price' => 20000,
        ]);
    }
}
