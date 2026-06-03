<?php

namespace Database\Seeders;

use App\Models\MotorcycleBrand;
use Illuminate\Database\Seeder;

class MotorcycleBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (config('motorcycles.brands', []) as $brand) {
            MotorcycleBrand::query()->updateOrCreate(
                ['name' => $brand['name']],
                [
                    'country' => $brand['country'],
                    'is_active' => true,
                ],
            );
        }
    }
}
