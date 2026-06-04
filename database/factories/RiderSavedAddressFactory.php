<?php

namespace Database\Factories;

use App\Models\Rider;
use App\Models\RiderSavedAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RiderSavedAddress>
 */
class RiderSavedAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rider_id' => Rider::factory(),
            'label' => fake()->randomElement(['Home', 'Work', 'Garage']),
            'recipient_name' => fake()->name(),
            'phone' => '+2010'.fake()->numerify('#######'),
            'city' => fake()->randomElement(['Cairo', 'Giza', 'Alexandria']),
            'area' => fake()->randomElement(['Nasr City', 'Dokki', 'Maadi']),
            'street' => fake()->streetName(),
            'building' => fake()->buildingNumber(),
            'floor' => (string) fake()->numberBetween(1, 12),
            'apartment' => (string) fake()->numberBetween(1, 120),
            'landmark' => fake()->optional()->sentence(4),
            'notes' => fake()->optional()->sentence(8),
            'is_default' => false,
        ];
    }

    public function default(): static
    {
        return $this->state(fn (): array => [
            'is_default' => true,
        ]);
    }
}
