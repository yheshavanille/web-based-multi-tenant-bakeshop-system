<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Cake', 'Muffins', 'Breads', 'Cookies', 'Brownies'];

        foreach ($categories as $category) {
            Category::firstOrCreate([
                'name' => $category,
            ], [
                'is_default' => true,
                'shop_id' => null,
            ]);
        }
    }
}
