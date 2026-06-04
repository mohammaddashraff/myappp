<?php

namespace App\Http\Controllers\ServiceCenter;

use App\Http\Controllers\Controller;
use App\Models\ServiceBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceCenterBookingController extends Controller
{
    public function index(Request $request): View
    {
        $profile = $request->user()->serviceCenterProfile;

        return view('providers.service-center.bookings.index', [
            'bookings' => ServiceBooking::query()
                ->whereHas('service', fn ($query) => $query->whereBelongsTo($profile))
                ->with(['service', 'rider', 'motorcycle'])
                ->latest()
                ->paginate(15),
        ]);
    }

    public function update(Request $request, ServiceBooking $booking): RedirectResponse
    {
        $profile = $request->user()->serviceCenterProfile;

        abort_unless($booking->service()->whereBelongsTo($profile)->exists(), 403);

        $validated = $request->validate([
            'status' => ['required', Rule::in(ServiceBooking::statuses())],
        ]);

        if (! $booking->canTransitionTo($validated['status'])) {
            return back()->withErrors(['status' => 'This booking cannot move to that status.']);
        }

        $booking->update($validated);

        return back()->with('status', 'Booking status updated.');
    }
}
