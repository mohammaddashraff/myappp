<?php

namespace Database\Factories;

use App\Models\Motorcycle;
use App\Models\Rider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Motorcycle>
 */
class MotorcycleFactory extends Factory
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
            'nickname' => fake()->randomElement(['Daily ride', 'Work motorcycle', 'Main motorcycle']),
            'brand_id' => null,
            'model_id' => null,
            'type' => fake()->randomElement(Motorcycle::allowedTypes()),
            'brand' => fake()->randomElement(['Bajaj', 'Honda', 'TVS', 'Yamaha']),
            'model' => fake()->bothify('Model ##'),
            'year' => fake()->numberBetween(2015, 2026),
            'engine_cc' => fake()->randomElement([125, 150, 180, 200, 250]),
            'color' => fake()->safeColorName(),
            'custom_brand' => null,
            'custom_model' => null,
            'image' => null,
            'ownership_license_image' => null,
            'motorcycle_registration_image' => null,
            'owner_name' => fake()->name(),
            'plate_number' => fake()->unique()->bothify('???-####'),
            'chassis_number' => fake()->unique()->bothify('CHS########'),
            'motor_number' => fake()->unique()->bothify('MTR########'),
            'registration_expires_at' => fake()->dateTimeBetween('+1 month', '+2 years')->format('Y-m-d'),
            'is_primary' => true,
        ];
    }
}
