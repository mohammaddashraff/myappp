<?php

namespace Database\Factories;

use App\Models\Rider;
use App\Models\RoadsideRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RoadsideRequest>
 */
class RoadsideRequestFactory extends Factory
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
            'request_number' => 'RSR-'.fake()->unique()->numerify('########'),
            'assistance_type' => 'Towing',
            'motorcycle_id' => null,
            'location' => fake()->address(),
            'description' => fake()->sentence(),
            'contact_phone' => '+2010'.fake()->numerify('#######'),
            'status' => 'pending',
        ];
    }
}
