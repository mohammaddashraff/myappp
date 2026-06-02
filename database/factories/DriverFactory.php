<?php

namespace Database\Factories;

use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'legal_name' => fake()->name(),
            'date_of_birth' => fake()->dateTimeBetween('-45 years', '-20 years')->format('Y-m-d'),
            'current_address' => fake()->address(),
            'phone_number' => '+2010'.fake()->unique()->numerify('#######'),
            'backup_phone_number' => '+2011'.fake()->numerify('#######'),
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_relationship' => fake()->randomElement(['Brother', 'Sister', 'Parent', 'Spouse']),
            'emergency_contact_phone' => '+2012'.fake()->numerify('#######'),
            'plate_number' => fake()->unique()->bothify('???-####'),
            'vehicle_owner_name' => fake()->name(),
            'chassis_number' => fake()->unique()->bothify('CHS########'),
            'motor_number' => fake()->unique()->bothify('MTR########'),
            'approval_status' => 'pending',
            'consented_to_background_check' => true,
            'accepted_terms' => true,
            'submitted_at' => now(),
        ];
    }
}
