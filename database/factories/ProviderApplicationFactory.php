<?php

namespace Database\Factories;

use App\Models\ProviderApplication;
use App\Models\User;
use App\Support\AccessRoles;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProviderApplication>
 */
class ProviderApplicationFactory extends Factory
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
            'requested_role' => fake()->randomElement(AccessRoles::providerRoles()),
            'business_name' => fake()->company(),
            'phone' => '+2010'.fake()->numerify('#######'),
            'address' => fake()->streetAddress(),
            'city' => fake()->randomElement(['Cairo', 'Giza', 'Alexandria']),
            'description' => fake()->sentence(12),
            'documents' => [],
            'status' => AccessRoles::STATUS_PENDING,
            'admin_notes' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (): array => [
            'status' => AccessRoles::STATUS_APPROVED,
            'reviewed_at' => now(),
        ]);
    }
}
