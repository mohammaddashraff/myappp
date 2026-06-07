<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\AccessRoles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ],
        );
        $superAdmin->assignRole(AccessRoles::SUPER_ADMIN);

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ],
        );
        $admin->assignRole(AccessRoles::ADMIN);
    }
}
