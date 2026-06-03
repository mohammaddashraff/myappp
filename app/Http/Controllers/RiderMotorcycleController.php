<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMotorcycleRequest;
use App\Http\Requests\UpdateMotorcycleRequest;
use App\Models\Motorcycle;
use App\Models\MotorcycleBrand;
use App\Support\MotorcycleCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RiderMotorcycleController extends Controller
{
    public function __construct(
        protected MotorcycleCatalog $motorcycleCatalog,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $rider = $request->user()
            ->rider()
            ->with([
                'motorcycles.brandRelation',
                'motorcycles.modelRelation',
            ])
            ->first();

        return view('riders.garage', [
            'rider' => $rider,
            'motorcycles' => $rider?->motorcycles?->sortByDesc('created_at')->values() ?? collect(),
            'status' => session('status'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        abort_unless($request->user()->rider !== null, 403);

        return view('riders.motorcycles.create', [
            'brands' => $this->brandFormOptions(),
            'motorcycle' => new Motorcycle,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMotorcycleRequest $request): RedirectResponse
    {
        $rider = $request->user()->rider;
        abort_unless($rider !== null, 403);

        $motorcycle = $rider->motorcycles()->create(
            $this->payloadFromRequest($request),
        );

        return redirect()
            ->route('rider.garage')
            ->with('status', 'motorcycle-added');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Motorcycle $motorcycle): View
    {
        $motorcycle = $this->ownedMotorcycle($request, $motorcycle)->load([
            'brandRelation',
            'modelRelation',
        ]);

        return view('riders.motorcycles.show', [
            'motorcycle' => $motorcycle,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Motorcycle $motorcycle): View
    {
        $motorcycle = $this->ownedMotorcycle($request, $motorcycle)->load([
            'brandRelation',
            'modelRelation',
        ]);

        return view('riders.motorcycles.edit', [
            'brands' => $this->brandFormOptions(),
            'motorcycle' => $motorcycle,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMotorcycleRequest $request, Motorcycle $motorcycle): RedirectResponse
    {
        $motorcycle = $this->ownedMotorcycle($request, $motorcycle);
        $motorcycle->update($this->payloadFromRequest($request, $motorcycle));

        return redirect()
            ->route('rider.garage')
            ->with('status', 'motorcycle-updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Motorcycle $motorcycle): RedirectResponse
    {
        $motorcycle = $this->ownedMotorcycle($request, $motorcycle);

        foreach (['image', 'ownership_license_image', 'motorcycle_registration_image'] as $fileField) {
            if ($motorcycle->{$fileField}) {
                Storage::disk('public')->delete($motorcycle->{$fileField});
            }
        }

        $motorcycle->delete();

        return redirect()
            ->route('rider.garage')
            ->with('status', 'motorcycle-deleted');
    }

    public function models(MotorcycleBrand $motorcycleBrand): JsonResponse
    {
        $this->motorcycleCatalog->sync();

        return response()->json([
            'models' => $motorcycleBrand->models()
                ->where('is_active', true)
                ->orderBy('id')
                ->get(['id', 'name', 'type', 'default_engine_cc']),
        ]);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    protected function brandFormOptions(): Collection
    {
        return $this->motorcycleCatalog->brandsForForm();
    }

    protected function ownedMotorcycle(Request $request, Motorcycle $motorcycle): Motorcycle
    {
        abort_unless($request->user()->rider?->is($motorcycle->rider), 404);

        return $motorcycle;
    }

    protected function payloadFromRequest(StoreMotorcycleRequest|UpdateMotorcycleRequest $request, ?Motorcycle $motorcycle = null): array
    {
        $this->motorcycleCatalog->sync();

        $validated = $request->validated();
        $brand = MotorcycleBrand::query()->findOrFail($validated['brand_id']);
        $model = $brand->models()->findOrFail($validated['model_id']);
        $resolvedBrand = $brand->name === 'Other' ? trim((string) ($validated['custom_brand'] ?? '')) : $brand->name;
        $resolvedModel = $model->name === 'Other' ? trim((string) ($validated['custom_model'] ?? '')) : $model->name;

        $payload = [
            'brand_id' => $brand->id,
            'model_id' => $model->id,
            'custom_brand' => $brand->name === 'Other' ? $resolvedBrand : null,
            'custom_model' => $model->name === 'Other' ? $resolvedModel : null,
            'type' => $validated['type'],
            'brand' => $resolvedBrand,
            'model' => $resolvedModel,
            'nickname' => trim(sprintf('%s %s', $resolvedBrand, $resolvedModel)),
            'year' => $validated['year'],
            'engine_cc' => (int) $validated['engine_cc'],
            'plate_number' => $validated['plate_number'],
            'color' => $validated['color'] ?? null,
            'owner_name' => $request->user()->rider?->full_name,
        ];

        foreach (['image', 'ownership_license_image', 'motorcycle_registration_image'] as $field) {
            if ($request->hasFile($field)) {
                if ($motorcycle?->{$field}) {
                    Storage::disk('public')->delete($motorcycle->{$field});
                }

                $payload[$field] = $request->file($field)->store('motorcycles', 'public');
            }
        }

        return $payload;
    }
}
