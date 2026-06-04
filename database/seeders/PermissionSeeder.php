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
        'manage roles',
        'manage permissions',
        'approve providers',
        'manage categories',
        'manage products',
        'manage services',
        'manage orders',
        'manage bookings',
        'manage roadside requests',
        'manage deliveries',
        'manage dealerships',
        'view reports',
        'manage own products',
        'manage own services',
        'manage own bookings',
        'manage own roadside requests',
        'manage own delivery tasks',
        'manage own dealership listings',
        'manage own motorcycles',
        'browse marketplace',
        'book services',
        'request roadside assistance',
    ];

    public const ROLE_PERMISSIONS = [
        AccessRoles::SUPER_ADMIN => self::PERMISSIONS,
        AccessRoles::ADMIN => [
            'manage users',
            'approve providers',
            'manage categories',
            'manage products',
            'manage services',
            'manage orders',
            'manage bookings',
            'manage roadside requests',
            'manage deliveries',
            'manage dealerships',
            'view reports',
        ],
        AccessRoles::RIDER => [
            'manage own motorcycles',
            'browse marketplace',
            'book services',
            'request roadside assistance',
        ],
        AccessRoles::SELLER => [
            'manage own products',
            'manage orders',
        ],
        AccessRoles::SERVICE_CENTER => [
            'manage own services',
            'manage own bookings',
        ],
        AccessRoles::ROADSIDE_PROVIDER => [
            'manage own roadside requests',
        ],
        AccessRoles::DELIVERY_PARTNER => [
            'manage own delivery tasks',
        ],
        AccessRoles::DEALERSHIP => [
            'manage own dealership listings',
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
