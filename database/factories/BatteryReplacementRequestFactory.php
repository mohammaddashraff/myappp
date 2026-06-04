<?php

namespace Database\Factories;

use App\Models\BatteryReplacementRequest;
use App\Models\Product;
use App\Models\Rider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BatteryReplacementRequest>
 */
class BatteryReplacementRequestFactory extends Factory
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
            'request_number' => 'BAT-'.fake()->unique()->numerify('########'),
            'battery_product_id' => Product::factory()->battery(),
            'motorcycle_id' => null,
            'location' => fake()->address(),
            'preferred_date' => now()->addDay()->toDateString(),
            'preferred_time' => '12:00',
            'contact_phone' => '+2010'.fake()->numerify('#######'),
            'notes' => fake()->sentence(),
            'status' => 'pending',
        ];
    }
}
