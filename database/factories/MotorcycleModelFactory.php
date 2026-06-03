<?php

namespace Database\Factories;

use App\Models\MotorcycleBrand;
use App\Models\MotorcycleModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MotorcycleModel>
 */
class MotorcycleModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'brand_id' => MotorcycleBrand::factory(),
            'name' => fake()->unique()->word(),
            'type' => fake()->randomElement(array_keys(config('motorcycles.types', []))),
            'default_engine_cc' => fake()->randomElement([125, 150, 180, 200, 250]),
            'is_active' => true,
        ];
    }
}
