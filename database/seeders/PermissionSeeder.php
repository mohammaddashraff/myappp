<?php

namespace Database\Seeders;

use App\Support\AccessRoles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public const PERMISSIONS = [
        'manage users',
        'manage subscriptions',
        'manage ads',
    ];

    public const ROLE_PERMISSIONS = [
        AccessRoles::SUPER_ADMIN => self::PERMISSIONS,
        AccessRoles::ADMIN => [
            'manage users',
            'manage subscriptions',
            'manage ads',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (self::PERMISSIONS as $permission) {
            Permission::findOrCreate($permission, 'web');
        }
    }
}
