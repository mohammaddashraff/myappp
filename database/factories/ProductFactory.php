<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => Product::TYPE_ACCESSORY,
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(14),
            'category' => fake()->randomElement(['Helmets', 'Gloves', 'Phone Holders']),
            'brand' => fake()->randomElement(['LS2', 'Motul', 'NGK', 'Bosch']),
            'price' => fake()->numberBetween(250, 4500),
            'stock_quantity' => fake()->numberBetween(1, 20),
            'condition' => 'new',
            'image' => null,
            'location' => fake()->randomElement(['Cairo', 'Giza', 'Alexandria']),
            'seller_name' => fake()->company(),
            'delivery_available' => true,
            'pickup_available' => true,
            'installation_available' => false,
            'compatible_motorcycle_types' => ['motorcycle', 'scooter'],
            'compatible_motorcycle_brands' => ['Honda', 'Yamaha'],
            'compatible_motorcycle_models' => ['CBR', 'PCX'],
            'estimated_delivery_time' => '2-4 days',
            'warranty_info' => '7 days store warranty',
            'return_policy' => 'Return within 14 days if unused',
            'voltage' => null,
            'capacity' => null,
            'status' => 'active',
        ];
    }

    public function sparePart(): static
    {
        return $this->state(fn (): array => [
            'type' => Product::TYPE_SPARE_PART,
            'category' => fake()->randomElement(['Tires', 'Brakes', 'Chains', 'Oils']),
        ]);
    }

    public function battery(): static
    {
        return $this->state(fn (): array => [
            'type' => Product::TYPE_BATTERY,
            'category' => 'Batteries',
            'installation_available' => true,
            'voltage' => '12V',
            'capacity' => '7Ah',
            'warranty_info' => '12 months warranty',
        ]);
    }
}
