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
        Schema::table('motorcycles', function (Blueprint $table) {
            $table->foreignId('brand_id')
                ->nullable()
                ->after('rider_id')
                ->constrained('motorcycle_brands')
                ->nullOnDelete();
            $table->foreignId('model_id')
                ->nullable()
                ->after('brand_id')
                ->constrained('motorcycle_models')
                ->nullOnDelete();
            $table->string('custom_brand')->nullable()->after('model_id');
            $table->string('custom_model')->nullable()->after('custom_brand');
            $table->unsignedSmallInteger('engine_cc')->nullable()->after('year');
            $table->string('image')->nullable()->after('color');
            $table->string('ownership_license_image')->nullable()->after('image');
            $table->string('motorcycle_registration_image')->nullable()->after('ownership_license_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('motorcycles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('brand_id');
            $table->dropConstrainedForeignId('model_id');
            $table->dropColumn([
                'custom_brand',
                'custom_model',
                'engine_cc',
                'image',
                'ownership_license_image',
                'motorcycle_registration_image',
            ]);
        });
    }
};
