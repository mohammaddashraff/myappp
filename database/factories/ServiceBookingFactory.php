<?php

namespace Database\Factories;

use App\Models\Rider;
use App\Models\Service;
use App\Models\ServiceBooking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceBooking>
 */
class ServiceBookingFactory extends Factory
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
            'service_id' => Service::factory(),
            'booking_number' => 'BKG-'.fake()->unique()->numerify('########'),
            'motorcycle_id' => null,
            'booking_date' => now()->addDay()->toDateString(),
            'preferred_time' => '10:00',
            'location_option' => 'visit_workshop',
            'notes' => fake()->sentence(),
            'contact_phone' => '+2010'.fake()->numerify('#######'),
            'estimated_price' => 350,
            'status' => 'pending',
        ];
    }
}
