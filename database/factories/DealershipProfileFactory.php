<?php

namespace Database\Factories;

use App\Models\DealershipProfile;
use App\Models\User;
use App\Support\AccessRoles;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DealershipProfile>
 */
class DealershipProfileFactory extends Factory
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
            'dealership_name' => fake()->company().' Showroom',
            'phone' => '+2010'.fake()->numerify('#######'),
            'address' => fake()->streetAddress(),
            'city' => fake()->randomElement(['Cairo', 'Giza', 'Alexandria']),
            'description' => fake()->sentence(12),
            'status' => AccessRoles::STATUS_APPROVED,
        ];
    }
}
