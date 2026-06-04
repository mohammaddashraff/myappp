<?php

namespace Database\Factories;

use App\Models\SellerProfile;
use App\Models\User;
use App\Support\AccessRoles;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SellerProfile>
 */
class SellerProfileFactory extends Factory
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
            'store_name' => fake()->company(),
            'seller_type' => fake()->randomElement(['accessories', 'spare_parts', 'mixed']),
            'phone' => '+2010'.fake()->numerify('#######'),
            'address' => fake()->streetAddress(),
            'city' => fake()->randomElement(['Cairo', 'Giza', 'Alexandria']),
            'description' => fake()->sentence(12),
            'status' => AccessRoles::STATUS_APPROVED,
        ];
    }
}
