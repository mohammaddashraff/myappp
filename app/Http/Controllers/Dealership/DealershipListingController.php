<?php

namespace App\Http\Controllers\Dealership;

use App\Http\Controllers\Controller;
use App\Models\Dealer;
use App\Models\DealerMotorcycle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DealershipListingController extends Controller
{
    public function index(Request $request): View
    {
        return view('providers.dealership.listings.index', [
            'listings' => $request->user()->dealershipProfile->motorcycleListings()->latest()->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('providers.dealership.listings.form', [
            'listing' => new DealerMotorcycle(['condition' => 'new', 'status' => 'active']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateListing($request);

        $request->user()->dealershipProfile->motorcycleListings()->create([
            ...$validated,
            'dealer_id' => $this->dealerIdFor($request),
            'installment_available' => $request->boolean('installment_available'),
            'year' => $validated['year'] ?? now()->year,
            'engine_cc' => $validated['engine_cc'] ?? 0,
            'price' => $validated['price'] ?? 0,
            'description' => $validated['description'] ?? '',
        ]);

        return redirect()->route('dealership.listings.index')->with('status', 'Motorcycle listing created.');
    }

    public function edit(Request $request, DealerMotorcycle $listing): View
    {
        $this->authorizeListing($request, $listing);

        return view('providers.dealership.listings.form', ['listing' => $listing]);
    }

    public function update(Request $request, DealerMotorcycle $listing): RedirectResponse
    {
        $this->authorizeListing($request, $listing);
        $validated = $this->validateListing($request);

        $listing->update([
            ...$validated,
            'installment_available' => $request->boolean('installment_available'),
            'year' => $validated['year'] ?? now()->year,
            'engine_cc' => $validated['engine_cc'] ?? 0,
            'price' => $validated['price'] ?? 0,
            'description' => $validated['description'] ?? '',
        ]);

        return redirect()->route('dealership.listings.index')->with('status', 'Motorcycle listing updated.');
    }

    public function destroy(Request $request, DealerMotorcycle $listing): RedirectResponse
    {
        $this->authorizeListing($request, $listing);
        $listing->delete();

        return redirect()->route('dealership.listings.index')->with('status', 'Motorcycle listing deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validateListing(Request $request): array
    {
        return $request->validate([
            'brand' => ['required', 'string', 'max:120'],
            'model' => ['required', 'string', 'max:120'],
            'year' => ['nullable', 'integer', 'min:1950', 'max:2100'],
            'engine_cc' => ['nullable', 'integer', 'min:0'],
            'condition' => ['required', 'in:new,used'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'installment_options' => ['nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:active,inactive,sold'],
        ]);
    }

    protected function authorizeListing(Request $request, DealerMotorcycle $listing): void
    {
        abort_unless($listing->dealership_profile_id === $request->user()->dealershipProfile?->id, 403);
    }

    protected function dealerIdFor(Request $request): int
    {
        return (int) Dealer::query()->firstOrCreate(
            ['name' => $request->user()->dealershipProfile->dealership_name],
            [
                'location' => $request->user()->dealershipProfile->city,
                'phone' => $request->user()->dealershipProfile->phone,
                'status' => 'active',
            ],
        )->id;
    }
}
