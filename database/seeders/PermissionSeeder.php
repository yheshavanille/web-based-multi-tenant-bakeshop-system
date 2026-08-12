<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     protected $permissions = [
        // ADMIN
        'access admin dashboard',
        'manage users',
        'manage roles',
        'manage shops',

        // OWNER
        'access owner dashboard',
        'manage own products',

        // CUSTOMER
        'access customer dashboard',
        'browse shops',
        'view products',
    ];

    public function run(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
