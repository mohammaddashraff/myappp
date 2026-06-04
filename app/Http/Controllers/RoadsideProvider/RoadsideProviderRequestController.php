<?php

namespace App\Http\Controllers\RoadsideProvider;

use App\Http\Controllers\Controller;
use App\Models\RoadsideRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoadsideProviderRequestController extends Controller
{
    public function index(Request $request): View
    {
        $profile = $request->user()->roadsideProviderProfile;
        $filter = $request->query('filter', 'available');

        $requests = RoadsideRequest::query()
            ->when($filter === 'available', fn ($query) => $query->whereNull('roadside_provider_profile_id')->pending())
            ->when($filter === 'active', fn ($query) => $query->whereBelongsTo($profile)->whereIn('status', [RoadsideRequest::STATUS_ACCEPTED, RoadsideRequest::STATUS_ON_THE_WAY]))
            ->when($filter === 'completed', fn ($query) => $query->whereBelongsTo($profile)->completed())
            ->with(['rider', 'motorcycle'])
            ->latest()
            ->paginate(15);

        return view('providers.roadside-provider.requests.index', [
            'requests' => $requests,
            'filter' => $filter,
        ]);
    }

    public function accept(Request $request, RoadsideRequest $roadsideRequest): RedirectResponse
    {
        abort_unless($roadsideRequest->roadside_provider_profile_id === null && $roadsideRequest->status === RoadsideRequest::STATUS_PENDING, 403);

        $roadsideRequest->update([
            'roadside_provider_profile_id' => $request->user()->roadsideProviderProfile->id,
            'status' => RoadsideRequest::STATUS_ACCEPTED,
        ]);

        return back()->with('status', 'Request accepted.');
    }

    public function update(Request $request, RoadsideRequest $roadsideRequest): RedirectResponse
    {
        abort_unless($roadsideRequest->roadside_provider_profile_id === $request->user()->roadsideProviderProfile?->id, 403);

        $validated = $request->validate([
            'status' => ['required', Rule::in(RoadsideRequest::statuses())],
        ]);

        if (! $roadsideRequest->canTransitionTo($validated['status'])) {
            return back()->withErrors(['status' => 'This request cannot move to that status.']);
        }

        $roadsideRequest->update($validated);

        return back()->with('status', 'Roadside request updated.');
    }
}
