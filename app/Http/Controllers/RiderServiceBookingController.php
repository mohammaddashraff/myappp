<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesRider;
use App\Models\Service;
use App\Models\ServiceBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RiderServiceBookingController extends Controller
{
    use ResolvesRider;

    public function index(Request $request): View
    {
        $rider = $this->riderFrom($request);

        return view('riders.marketplace.bookings.index', [
            'bookings' => $rider->serviceBookings()
                ->with('service')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create(Request $request, Service $service): View
    {
        abort_unless($service->status === 'active', 404);

        $rider = $this->riderFrom($request);

        return view('riders.marketplace.bookings.create', [
            'service' => $service,
            'motorcycles' => $rider->motorcycles()->latest()->get(),
            'rider' => $rider,
        ]);
    }

    public function store(Request $request, Service $service): RedirectResponse
    {
        abort_unless($service->status === 'active', 404);

        $rider = $this->riderFrom($request);

        $validated = $request->validate([
            'motorcycle_id' => ['nullable', 'integer', 'exists:motorcycles,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time' => ['required', 'date_format:H:i'],
            'location_option' => ['required', Rule::in(['visit_workshop', 'pickup_service'])],
            'notes' => ['nullable', 'string', 'max:1000'],
            'contact_phone' => ['required', 'string', 'max:30'],
        ], [
            'booking_date.required' => 'Service date is required.',
            'preferred_time.required' => 'Preferred time is required.',
            'contact_phone.required' => 'Contact phone is required.',
        ]);

        $motorcycle = $this->ownedMotorcycleFrom($request, $validated['motorcycle_id'] ?? null);

        if ($validated['location_option'] === 'pickup_service' && ! $service->pickup_available) {
            return back()->withErrors(['location_option' => 'Pickup service is not available for this service.'])->withInput();
        }

        $booking = $rider->serviceBookings()->create([
            'service_id' => $service->id,
            'booking_number' => ServiceBooking::nextNumber(),
            'motorcycle_id' => $motorcycle?->id,
            'booking_date' => $validated['booking_date'],
            'preferred_time' => $validated['preferred_time'],
            'location_option' => $validated['location_option'],
            'notes' => $validated['notes'] ?? null,
            'contact_phone' => $validated['contact_phone'],
            'estimated_price' => $service->estimated_price,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('rider.bookings.show', $booking)
            ->with('status', 'Service booking created.');
    }

    public function show(Request $request, ServiceBooking $booking): View
    {
        abort_unless($this->riderFrom($request)->is($booking->rider), 404);

        return view('riders.marketplace.bookings.show', [
            'booking' => $booking->load(['service', 'motorcycle']),
            'timeline' => [ServiceBooking::STATUS_PENDING, ServiceBooking::STATUS_ACCEPTED, ServiceBooking::STATUS_COMPLETED],
        ]);
    }
}
