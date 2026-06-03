<?php

namespace Database\Seeders;

use App\Models\MotorcycleBrand;
use App\Models\MotorcycleModel;
use Illuminate\Database\Seeder;

class MotorcycleModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (config('motorcycles.brands', []) as $brandData) {
            $brand = MotorcycleBrand::query()->where('name', $brandData['name'])->first();

            if (! $brand) {
                continue;
            }

            foreach ($brandData['models'] as $modelData) {
                MotorcycleModel::query()->updateOrCreate(
                    [
                        'brand_id' => $brand->id,
                        'name' => $modelData['name'],
                    ],
                    [
                        'type' => $modelData['type'] ?? null,
                        'default_engine_cc' => $modelData['default_engine_cc'] ?? null,
                        'is_active' => true,
                    ],
                );
            }
        }
    }
}
