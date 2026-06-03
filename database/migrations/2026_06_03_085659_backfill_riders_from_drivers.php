<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('drivers')
            ->whereNotNull('user_id')
            ->orderBy('id')
            ->chunkById(100, function ($drivers): void {
                foreach ($drivers as $driver) {
                    $riderId = DB::table('riders')
                        ->where('user_id', $driver->user_id)
                        ->value('id');

                    if ($riderId === null) {
                        $riderId = DB::table('riders')->insertGetId([
                            'user_id' => $driver->user_id,
                            'full_name' => $driver->legal_name,
                            'date_of_birth' => $driver->date_of_birth,
                            'current_address' => $driver->current_address,
                            'phone_number' => $driver->phone_number,
                            'backup_phone_number' => $driver->backup_phone_number,
                            'emergency_contact_name' => $driver->emergency_contact_name,
                            'emergency_contact_relationship' => $driver->emergency_contact_relationship,
                            'emergency_contact_phone' => $driver->emergency_contact_phone,
                            'profile_completed_at' => $driver->submitted_at ?? now(),
                            'created_at' => $driver->created_at ?? now(),
                            'updated_at' => $driver->updated_at ?? now(),
                        ]);
                    }

                    $motorcycleExists = DB::table('motorcycles')
                        ->where('rider_id', $riderId)
                        ->where(function ($query) use ($driver): void {
                            $query
                                ->where('plate_number', $driver->plate_number)
                                ->orWhere('chassis_number', $driver->chassis_number)
                                ->orWhere('motor_number', $driver->motor_number);
                        })
                        ->exists();

                    if (! $motorcycleExists) {
                        DB::table('motorcycles')->insert([
                            'rider_id' => $riderId,
                            'nickname' => 'الموتوسيكل الأساسي',
                            'type' => 'motorcycle',
                            'owner_name' => $driver->vehicle_owner_name,
                            'plate_number' => $driver->plate_number,
                            'chassis_number' => $driver->chassis_number,
                            'motor_number' => $driver->motor_number,
                            'is_primary' => true,
                            'created_at' => $driver->created_at ?? now(),
                            'updated_at' => $driver->updated_at ?? now(),
                        ]);
                    }
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
