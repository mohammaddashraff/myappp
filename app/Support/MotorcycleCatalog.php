<?php

namespace App\Support;

use App\Models\MotorcycleBrand;
use App\Models\MotorcycleModel;
use Illuminate\Support\Collection;

class MotorcycleCatalog
{
    public function sync(): void
    {
        foreach (config('motorcycles.brands', []) as $brandData) {
            $brand = MotorcycleBrand::query()->updateOrCreate(
                ['name' => $brandData['name']],
                [
                    'country' => $brandData['country'],
                    'is_active' => true,
                ],
            );

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

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function brandsForForm(): Collection
    {
        $this->sync();

        return MotorcycleBrand::query()
            ->where('is_active', true)
            ->with([
                'models' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('id'),
            ])
            ->orderBy('id')
            ->get()
            ->map(function (MotorcycleBrand $brand): array {
                return [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'models' => $brand->models->map(fn (MotorcycleModel $model): array => [
                        'id' => $model->id,
                        'name' => $model->name,
                        'type' => $model->type,
                        'default_engine_cc' => $model->default_engine_cc,
                    ])->values()->all(),
                ];
            })
            ->values();
    }
}
