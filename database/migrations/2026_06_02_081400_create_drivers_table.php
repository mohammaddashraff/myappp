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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('legal_name');
            $table->date('date_of_birth');
            $table->text('current_address');
            $table->string('phone_number')->unique();
            $table->string('backup_phone_number');
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_relationship');
            $table->string('emergency_contact_phone');
            $table->string('plate_number')->unique();
            $table->string('vehicle_owner_name');
            $table->string('chassis_number')->unique();
            $table->string('motor_number')->unique();
            $table->string('approval_status')->default('pending')->index();
            $table->boolean('consented_to_background_check')->default(false);
            $table->boolean('accepted_terms')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->string('national_id_front_photo_path')->nullable();
            $table->string('national_id_back_photo_path')->nullable();
            $table->string('selfie_photo_path')->nullable();
            $table->string('driver_license_photo_path')->nullable();
            $table->string('vehicle_license_photo_path')->nullable();
            $table->string('criminal_record_certificate_photo_path')->nullable();
            $table->string('drug_test_photo_path')->nullable();
            $table->string('vehicle_front_photo_path')->nullable();
            $table->string('vehicle_side_photo_path')->nullable();
            $table->string('vehicle_back_photo_path')->nullable();
            $table->string('delivery_box_photo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
