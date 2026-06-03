<?php

namespace Database\Factories;

use App\Models\Motorcycle;
use App\Models\MotorcycleDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MotorcycleDocument>
 */
class MotorcycleDocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $documentType = fake()->randomElement(['vehicle_license', 'insurance', 'maintenance', 'inspection']);

        return [
            'motorcycle_id' => Motorcycle::factory(),
            'document_type' => $documentType,
            'title' => str($documentType)->replace('_', ' ')->title()->toString(),
            'file_path' => null,
            'issued_at' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'expires_at' => fake()->dateTimeBetween('+1 month', '+1 year')->format('Y-m-d'),
            'reminder_at' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'status' => 'active',
            'notes' => null,
        ];
    }
}
