<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ([
            'delivery_tasks',
            'dealer_inquiries',
            'battery_replacement_requests',
            'roadside_requests',
            'service_bookings',
            'order_items',
            'orders',
            'cart_items',
            'wishlist_items',
            'rider_saved_addresses',
            'dealer_motorcycles',
            'dealers',
            'services',
            'products',
            'delivery_partner_profiles',
            'roadside_provider_profiles',
            'service_center_profiles',
            'dealership_profiles',
            'seller_profiles',
            'provider_applications',
            'motorcycle_documents',
            'motorcycles',
            'motorcycle_models',
            'motorcycle_brands',
            'riders',
            'drivers',
        ] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();

        if (Schema::hasTable('model_has_roles') && Schema::hasTable('roles')) {
            DB::table('model_has_roles')->where('role_id', function ($query): void {
                $query->select('id')->from('roles')->where('name', 'rider')->limit(1);
            })->delete();
        }

        if (Schema::hasTable('role_has_permissions') && Schema::hasTable('permissions')) {
            DB::table('role_has_permissions')->whereIn('permission_id', function ($query): void {
                $query->select('id')->from('permissions')->whereIn('name', ['browse ads', 'publish own ads']);
            })->delete();
        }

        if (Schema::hasTable('permissions')) {
            DB::table('permissions')->whereIn('name', ['browse ads', 'publish own ads'])->delete();
        }

        if (Schema::hasTable('roles')) {
            DB::table('roles')->where('name', 'rider')->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Retired legacy tables are intentionally not recreated.
    }
};
