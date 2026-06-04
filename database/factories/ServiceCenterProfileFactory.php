<?php

namespace Database\Factories;

use App\Models\ServiceCenterProfile;
use App\Models\User;
use App\Support\AccessRoles;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceCenterProfile>
 */
class ServiceCenterProfileFactory extends Factory
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
            'center_name' => fake()->company().' Service',
            'phone' => '+2010'.fake()->numerify('#######'),
            'address' => fake()->streetAddress(),
            'city' => fake()->randomElement(['Cairo', 'Giza', 'Alexandria']),
            'description' => fake()->sentence(12),
            'working_hours' => '10:00 - 20:00',
            'status' => AccessRoles::STATUS_APPROVED,
        ];
    }
}
