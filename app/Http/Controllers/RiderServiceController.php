<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiderServiceController extends Controller
{
    public function index(Request $request): View
    {
        $services = Service::query()
            ->where('status', 'active')
            ->tap(fn (Builder $query) => $this->applyFilters($query, $request))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $filterOptions = Service::query()
            ->where('status', 'active')
            ->get(['category', 'location']);

        return view('riders.marketplace.services.index', [
            'services' => $services,
            'categories' => $filterOptions->pluck('category')->unique()->sort()->values(),
            'locations' => $filterOptions->pluck('location')->unique()->sort()->values(),
        ]);
    }

    public function show(Service $service): View
    {
        abort_unless($service->status === 'active', 404);

        return view('riders.marketplace.services.show', [
            'service' => $service,
        ]);
    }

    protected function applyFilters(Builder $query, Request $request): void
    {
        $query->when($request->filled('q'), function (Builder $query) use ($request): void {
            $term = '%'.$request->string('q')->toString().'%';
            $query->where(function (Builder $query) use ($term): void {
                $query->where('name', 'like', $term)
                    ->orWhere('description', 'like', $term);
            });
        });

        foreach (['category', 'location'] as $field) {
            $query->when($request->filled($field), fn (Builder $query): Builder => $query->where($field, $request->string($field)->toString()));
        }

        $query->when($request->filled('min_price'), fn (Builder $query): Builder => $query->where('estimated_price', '>=', (float) $request->input('min_price')));
        $query->when($request->filled('max_price'), fn (Builder $query): Builder => $query->where('estimated_price', '<=', (float) $request->input('max_price')));
        $query->when($request->filled('rating'), fn (Builder $query): Builder => $query->where('rating', '>=', (float) $request->input('rating')));
        $query->when($request->boolean('available_today'), fn (Builder $query): Builder => $query->where('available_today', true));

        $query->when($request->filled('motorcycle_type'), function (Builder $query) use ($request): void {
            $query->where('motorcycle_types', 'like', '%'.$request->string('motorcycle_type')->toString().'%');
        });
    }
}
