<?php

namespace Database\Factories;

use App\Models\Ad;
use App\Models\AdInteraction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AdInteraction>
 */
class AdInteractionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ad_id' => Ad::factory(),
            'user_id' => User::factory(),
            'type' => fake()->randomElement([
                AdInteraction::TYPE_VIEW,
                AdInteraction::TYPE_PHONE_REVEAL,
            ]),
        ];
    }
}
