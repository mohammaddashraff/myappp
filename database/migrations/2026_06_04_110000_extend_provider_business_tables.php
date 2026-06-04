<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('provider_applications', function (Blueprint $table) {
            $table->string('display_name')->nullable()->after('business_name');
        });

        Schema::table('seller_profiles', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('description');
        });

        Schema::table('dealership_profiles', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('description');
        });

        Schema::table('roadside_requests', function (Blueprint $table) {
            $table->foreignId('roadside_provider_profile_id')->nullable()->after('rider_id')->constrained()->nullOnDelete();
        });

        Schema::create('delivery_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_partner_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->text('pickup_address');
            $table->text('dropoff_address');
            $table->string('phone')->nullable();
            $table->string('status')->default('pending')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_tasks');

        Schema::table('roadside_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('roadside_provider_profile_id');
        });

        Schema::table('dealership_profiles', function (Blueprint $table) {
            $table->dropColumn('logo');
        });

        Schema::table('seller_profiles', function (Blueprint $table) {
            $table->dropColumn('logo');
        });

        Schema::table('provider_applications', function (Blueprint $table) {
            $table->dropColumn('display_name');
        });
    }
};
