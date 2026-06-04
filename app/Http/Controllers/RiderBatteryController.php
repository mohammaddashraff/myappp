<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesRider;
use App\Models\BatteryReplacementRequest;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiderBatteryController extends Controller
{
    use ResolvesRider;

    public function index(Request $request): View
    {
        $batteries = Product::query()
            ->where('status', 'active')
            ->where('type', Product::TYPE_BATTERY)
            ->when($request->filled('q'), function (Builder $query) use ($request): void {
                $term = '%'.$request->string('q')->toString().'%';
                $query->where(function (Builder $query) use ($term): void {
                    $query->where('name', 'like', $term)
                        ->orWhere('brand', 'like', $term);
                });
            })
            ->when($request->filled('location'), fn (Builder $query): Builder => $query->where('location', $request->string('location')->toString()))
            ->when($request->boolean('installation_available'), fn (Builder $query): Builder => $query->where('installation_available', true))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('riders.marketplace.batteries.index', [
            'batteries' => $batteries,
            'locations' => Product::query()
                ->where('status', 'active')
                ->where('type', Product::TYPE_BATTERY)
                ->pluck('location')
                ->unique()
                ->sort()
                ->values(),
        ]);
    }

    public function createInstallation(Request $request, Product $product): View
    {
        abort_unless($product->type === Product::TYPE_BATTERY && $product->status === 'active', 404);

        $rider = $this->riderFrom($request);

        return view('riders.marketplace.batteries.installation', [
            'battery' => $product,
            'motorcycles' => $rider->motorcycles()->latest()->get(),
            'rider' => $rider,
        ]);
    }

    public function storeInstallation(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->type === Product::TYPE_BATTERY && $product->status === 'active', 404);

        $rider = $this->riderFrom($request);

        $validated = $request->validate([
            'motorcycle_id' => ['nullable', 'integer', 'exists:motorcycles,id'],
            'location' => ['required', 'string', 'max:1000'],
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time' => ['required', 'date_format:H:i'],
            'contact_phone' => ['required', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'location.required' => 'Location is required.',
            'preferred_date.required' => 'Preferred date is required.',
            'preferred_time.required' => 'Preferred time is required.',
            'contact_phone.required' => 'Contact phone is required.',
        ]);

        $motorcycle = $this->ownedMotorcycleFrom($request, $validated['motorcycle_id'] ?? null);

        $batteryRequest = $rider->batteryReplacementRequests()->create([
            'request_number' => BatteryReplacementRequest::nextNumber(),
            'battery_product_id' => $product->id,
            'motorcycle_id' => $motorcycle?->id,
            'location' => $validated['location'],
            'preferred_date' => $validated['preferred_date'],
            'preferred_time' => $validated['preferred_time'],
            'contact_phone' => $validated['contact_phone'],
            'notes' => $validated['notes'] ?? null,
            'status' => BatteryReplacementRequest::STATUS_PENDING,
        ]);

        return redirect()
            ->route('rider.requests.show', ['type' => 'battery', 'id' => $batteryRequest->id])
            ->with('status', 'Battery replacement request created.');
    }
}
