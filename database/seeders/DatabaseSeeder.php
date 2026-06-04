<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\AccessRoles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            AdminUserSeeder::class,
            MotorcycleBrandSeeder::class,
            MotorcycleModelSeeder::class,
            MarketplaceSeeder::class,
        ]);

        // User::factory(10)->create();

        $testUser = User::query()->updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ],
        );
        $testUser->assignRole(AccessRoles::RIDER);
    }
}
