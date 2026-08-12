<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Red Ribbon Owner
        $redRibbonOwner = User::firstOrCreate(
            ['email' => 'redribbon@gmail.com'],
            [
                'name' => 'Red Ribbon Owner',
                'password' => Hash::make('redribbon123'),
            ]
        );
        $redRibbonOwner->assignRole('owner');

        // Create Goldilocks Owner
        $goldilocksOwner = User::firstOrCreate(
            ['email' => 'goldilocks@gmail.com'],
            [
                'name' => 'Goldilocks Owner',
                'password' => Hash::make('goldilocks123'),
            ]
        );
        $goldilocksOwner->assignRole('owner');

        // Create Red Ribbon Shop
        Shop::firstOrCreate([
            'shop_name' => 'Red Ribbon',
            'user_id' => $redRibbonOwner->id,
        ], [
            'shop_image' => null,
            'address' => 'Victorias City',
            'description' => 'Popular bakery offering cakes and desserts',
        ]);

        // Create Goldilocks Shop
        Shop::firstOrCreate([
            'shop_name' => 'Goldilocks',
            'user_id' => $goldilocksOwner->id,
        ], [
            'shop_image' => null,
            'address' => 'Victorias City',
            'description' => 'Famous Filipino bakeshop known for cakes and pastries',
        ]);
    }
}
