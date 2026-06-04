<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Motorcycle;
use App\Models\Rider;
use Illuminate\Http\Request;

trait ResolvesRider
{
    protected function riderFrom(Request $request): Rider
    {
        $rider = $request->user()?->rider;

        abort_unless($rider instanceof Rider, 403);

        return $rider;
    }

    protected function ownedMotorcycleFrom(Request $request, ?int $motorcycleId): ?Motorcycle
    {
        if ($motorcycleId === null) {
            return null;
        }

        return $this->riderFrom($request)
            ->motorcycles()
            ->whereKey($motorcycleId)
            ->firstOrFail();
    }
}
