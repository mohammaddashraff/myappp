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
        Schema::table('products', function (Blueprint $table): void {
            $table->foreignId('seller_profile_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        Schema::table('services', function (Blueprint $table): void {
            $table->foreignId('service_center_profile_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        Schema::table('dealer_motorcycles', function (Blueprint $table): void {
            $table->foreignId('dealership_profile_id')->nullable()->after('dealer_id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dealer_motorcycles', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('dealership_profile_id');
        });

        Schema::table('services', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('service_center_profile_id');
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('seller_profile_id');
        });
    }
};
