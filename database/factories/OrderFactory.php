<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Rider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
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
            'order_number' => 'ORD-'.fake()->unique()->numerify('########'),
            'subtotal' => 1000,
            'delivery_fee' => 75,
            'total' => 1075,
            'delivery_method' => 'delivery',
            'payment_method' => 'cash_on_delivery',
            'address' => fake()->address(),
            'status' => Order::STATUS_PENDING,
        ];
    }
}
