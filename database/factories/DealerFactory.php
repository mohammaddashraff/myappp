<?php

namespace Database\Factories;

use App\Models\Dealer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dealer>
 */
class DealerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company().' Showroom',
            'location' => fake()->randomElement(['Cairo', 'Giza', 'Alexandria']),
            'brands_available' => ['Honda', 'Yamaha', 'SYM'],
            'phone' => '+202'.fake()->numerify('#######'),
            'rating' => fake()->randomFloat(1, 3.8, 5),
            'status' => 'active',
        ];
    }
}
