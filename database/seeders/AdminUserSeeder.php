<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@restaurant.com'],
            ['name' => 'Super Admin', 'password' => Hash::make('SuperAdmin@123')]
        );
        $superAdmin->syncRoles(['Super Admin']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@restaurant.com'],
            ['name' => 'Admin', 'password' => Hash::make('Admin@123')]
        );
        $admin->syncRoles(['Admin']);
    }
}
