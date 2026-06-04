<?php

namespace Database\Seeders;

use App\Support\AccessRoles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (AccessRoles::roles() as $roleName) {
            $role = Role::findOrCreate($roleName, 'web');
            $role->syncPermissions(PermissionSeeder::ROLE_PERMISSIONS[$roleName] ?? []);
        }
    }
}
