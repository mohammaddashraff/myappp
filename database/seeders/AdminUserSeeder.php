<?php

namespace Database\Seeders;

use App\Models\Rider;
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
        Rider::query()->firstOrCreate(
            ['user_id' => $superAdmin->id],
            ['full_name' => $superAdmin->name],
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ],
        );
        $admin->assignRole(AccessRoles::ADMIN);
        Rider::query()->firstOrCreate(
            ['user_id' => $admin->id],
            ['full_name' => $admin->name],
        );
    }
}
