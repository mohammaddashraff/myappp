<?php

namespace Database\Factories;

use App\Models\Dealer;
use App\Models\DealerInquiry;
use App\Models\DealerMotorcycle;
use App\Models\Rider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DealerInquiry>
 */
class DealerInquiryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dealer = Dealer::factory();

        return [
            'rider_id' => Rider::factory(),
            'dealer_id' => $dealer,
            'dealer_motorcycle_id' => DealerMotorcycle::factory()->for($dealer),
            'inquiry_number' => 'INQ-'.fake()->unique()->numerify('########'),
            'rider_name' => fake()->name(),
            'phone' => '+2010'.fake()->numerify('#######'),
            'message' => fake()->sentence(),
            'preferred_contact_method' => 'phone',
            'status' => 'pending',
        ];
    }
}
