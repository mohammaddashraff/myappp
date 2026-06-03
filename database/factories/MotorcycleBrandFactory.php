<?php

namespace Database\Factories;

use App\Models\MotorcycleBrand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MotorcycleBrand>
 */
class MotorcycleBrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'country' => fake()->country(),
            'is_active' => true,
        ];
    }
}
