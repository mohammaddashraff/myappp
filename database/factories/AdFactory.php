<?php

namespace Database\Factories;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ad>
 */
class AdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->text(180),
            'category' => fake()->randomElement(Ad::categories()),
            'price' => fake()->numberBetween(25000, 180000),
            'location' => fake()->randomElement(['Cairo', 'Giza', 'Alexandria']),
            'condition' => fake()->randomElement(['new', 'used']),
            'contact_phone' => '+2010'.fake()->numerify('#######'),
            'images' => ['https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1200&q=80'],
            'status' => Ad::STATUS_PUBLISHED,
            'sold_at' => null,
        ];
    }
}
