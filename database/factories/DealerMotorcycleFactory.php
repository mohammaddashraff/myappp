<?php

namespace Database\Factories;

use App\Models\Dealer;
use App\Models\DealerMotorcycle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DealerMotorcycle>
 */
class DealerMotorcycleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dealer_id' => Dealer::factory(),
            'brand' => fake()->randomElement(['Honda', 'Yamaha', 'SYM']),
            'model' => fake()->randomElement(['CBR', 'PCX', 'NMAX', 'Orbit']),
            'year' => fake()->numberBetween(2020, 2026),
            'engine_cc' => fake()->randomElement([125, 150, 155, 200, 250]),
            'condition' => fake()->randomElement(['new', 'used']),
            'price' => fake()->numberBetween(65000, 240000),
            'installment_available' => true,
            'installment_options' => 'Starting from 20% down payment over 24 months.',
            'description' => fake()->sentence(18),
            'image' => null,
            'images' => null,
            'status' => 'active',
        ];
    }
}
