<?php

namespace Database\Factories;

use App\Models\DeliveryPartnerProfile;
use App\Models\User;
use App\Support\AccessRoles;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeliveryPartnerProfile>
 */
class DeliveryPartnerProfileFactory extends Factory
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
            'phone' => '+2010'.fake()->numerify('#######'),
            'national_id' => fake()->numerify('##############'),
            'license_number' => fake()->bothify('LIC-#####'),
            'motorcycle_id' => null,
            'status' => AccessRoles::STATUS_APPROVED,
        ];
    }
}
