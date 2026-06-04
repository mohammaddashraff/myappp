<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Oil Change', 'Full Inspection', 'Brake Service']),
            'category' => fake()->randomElement(['Oil Change', 'Full Inspection', 'Brake Service']),
            'description' => fake()->sentence(14),
            'estimated_price' => fake()->numberBetween(150, 1200),
            'estimated_duration' => fake()->randomElement(['45 minutes', '1 hour', '2 hours']),
            'service_center_name' => fake()->company(),
            'location' => fake()->randomElement(['Cairo', 'Giza', 'Alexandria']),
            'rating' => fake()->randomFloat(1, 3.8, 5),
            'working_hours' => '10:00 AM - 8:00 PM',
            'pickup_available' => true,
            'available_today' => true,
            'motorcycle_types' => ['motorcycle', 'scooter'],
            'notes' => 'Bring registration if available.',
            'status' => 'active',
        ];
    }
}
