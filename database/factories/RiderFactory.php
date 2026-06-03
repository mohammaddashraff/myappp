<?php

namespace Database\Factories;

use App\Models\Rider;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rider>
 */
class RiderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'full_name' => fake()->name(),
            'date_of_birth' => fake()->dateTimeBetween('-45 years', '-18 years')->format('Y-m-d'),
            'current_address' => fake()->address(),
            'phone_number' => '+2010'.fake()->unique()->numerify('#######'),
            'backup_phone_number' => '+2011'.fake()->numerify('#######'),
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_relationship' => fake()->randomElement(['Brother', 'Sister', 'Parent', 'Spouse']),
            'emergency_contact_phone' => '+2012'.fake()->numerify('#######'),
            'profile_completed_at' => now(),
        ];
    }
}
