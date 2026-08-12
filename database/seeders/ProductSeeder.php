<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a shop (for demo purposes)
        $shop = Shop::first(['*']);

        if (!$shop) {
            return;
        }

        $categories = Category::all()->keyBy('name');

        // PRODUCTS FOR GOLDILOCKS / SAMPLE SHOP

        Product::firstOrCreate([
            'name' => 'Chocolate Cake',
            'shop_id' => $shop->id,
            'price' => 250,
            'description' => 'Rich and moist chocolate cake perfect for celebrations',
            'category_id' => $categories->get('Cake')?->id,
        ]);

        Product::firstOrCreate([
            'name' => 'Butter Mamon',
            'shop_id' => $shop->id,
            'price' => 50,
            'description' => 'Soft and fluffy butter-flavored bread',
            'category_id' => $categories->get('Muffins')?->id,
        ]);

        Product::firstOrCreate([
            'name' => 'Ube Ensaymada',
            'shop_id' => $shop->id,
            'price' => 80,
            'description' => 'Sweet purple yam topped with cheese and butter',
            'category_id' => $categories->get('Breads')?->id,
        ]);

        Product::firstOrCreate([
            'name' => 'Cheese Bread',
            'shop_id' => $shop->id,
            'price' => 40,
            'description' => 'Soft bread filled with creamy cheese',
            'category_id' => $categories->get('Breads')?->id,
        ]);
    }
}
