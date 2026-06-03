<?php

namespace Database\Seeders;

use App\Models\Motorcycle;
use Illuminate\Database\Seeder;

class MotorcycleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Motorcycle::factory()->count(3)->create();
    }
}
