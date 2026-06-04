<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesRider;
use App\Models\RoadsideRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RiderRoadsideRequestController extends Controller
{
    use ResolvesRider;

    /**
     * @return array<int, string>
     */
    public static function assistanceTypes(): array
    {
        return [
            'Towing',
            'Flat Tire Help',
            'Battery Jumpstart',
            'Battery Replacement',
            'Fuel Delivery',
            'Breakdown Support',
            'Accident Support',
        ];
    }

    public function create(Request $request): View
    {
        $rider = $this->riderFrom($request);

        return view('riders.marketplace.roadside.create', [
            'assistanceTypes' => self::assistanceTypes(),
            'motorcycles' => $rider->motorcycles()->latest()->get(),
            'rider' => $rider,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $rider = $this->riderFrom($request);

        $validated = $request->validate([
            'assistance_type' => ['required', Rule::in(self::assistanceTypes())],
            'motorcycle_id' => ['nullable', 'integer', 'exists:motorcycles,id'],
            'location' => ['required', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:1000'],
            'contact_phone' => ['required', 'string', 'max:30'],
        ], [
            'location.required' => 'Location is required for roadside assistance.',
            'contact_phone.required' => 'Contact phone is required.',
        ]);

        $motorcycle = $this->ownedMotorcycleFrom($request, $validated['motorcycle_id'] ?? null);

        $roadsideRequest = $rider->roadsideRequests()->create([
            'request_number' => RoadsideRequest::nextNumber(),
            'assistance_type' => $validated['assistance_type'],
            'motorcycle_id' => $motorcycle?->id,
            'location' => $validated['location'],
            'description' => $validated['description'] ?? null,
            'contact_phone' => $validated['contact_phone'],
            'status' => RoadsideRequest::STATUS_PENDING,
        ]);

        return redirect()
            ->route('rider.requests.show', ['type' => 'roadside', 'id' => $roadsideRequest->id])
            ->with('status', 'Roadside assistance request created.');
    }
}
