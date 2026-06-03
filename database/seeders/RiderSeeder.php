<?php

namespace Database\Seeders;

use App\Models\Rider;
use Illuminate\Database\Seeder;

class RiderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rider::factory()->count(3)->create();
    }
}
