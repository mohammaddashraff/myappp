<?php

namespace Database\Factories;

use App\Models\RoadsideProviderProfile;
use App\Models\User;
use App\Support\AccessRoles;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RoadsideProviderProfile>
 */
class RoadsideProviderProfileFactory extends Factory
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
            'provider_name' => fake()->company().' Roadside',
            'phone' => '+2010'.fake()->numerify('#######'),
            'address' => fake()->streetAddress(),
            'city' => fake()->randomElement(['Cairo', 'Giza', 'Alexandria']),
            'coverage_area' => fake()->randomElement(['Greater Cairo', 'Giza', 'Alexandria']),
            'status' => AccessRoles::STATUS_APPROVED,
        ];
    }
}
