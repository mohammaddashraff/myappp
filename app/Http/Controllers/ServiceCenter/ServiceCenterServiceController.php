<?php

namespace App\Http\Controllers\ServiceCenter;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceCenterServiceController extends Controller
{
    public function index(Request $request): View
    {
        return view('providers.service-center.services.index', [
            'services' => $request->user()->serviceCenterProfile->services()->latest()->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('providers.service-center.services.form', ['service' => new Service(['status' => 'active'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $profile = $request->user()->serviceCenterProfile;
        $validated = $this->validateService($request);

        $profile->services()->create([
            ...$validated,
            'estimated_price' => $validated['estimated_price'] ?? 0,
            'service_center_name' => $profile->center_name,
            'location' => $profile->city,
            'pickup_available' => $request->boolean('pickup_available'),
            'available_today' => $request->boolean('available_today'),
        ]);

        return redirect()->route('service-center.services.index')->with('status', 'Service created.');
    }

    public function edit(Request $request, Service $service): View
    {
        $this->authorizeService($request, $service);

        return view('providers.service-center.services.form', ['service' => $service]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $this->authorizeService($request, $service);
        $profile = $request->user()->serviceCenterProfile;
        $validated = $this->validateService($request);

        $service->update([
            ...$validated,
            'estimated_price' => $validated['estimated_price'] ?? 0,
            'service_center_name' => $profile->center_name,
            'location' => $profile->city,
            'pickup_available' => $request->boolean('pickup_available'),
            'available_today' => $request->boolean('available_today'),
        ]);

        return redirect()->route('service-center.services.index')->with('status', 'Service updated.');
    }

    public function destroy(Request $request, Service $service): RedirectResponse
    {
        $this->authorizeService($request, $service);
        $service->delete();

        return redirect()->route('service-center.services.index')->with('status', 'Service deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validateService(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:2000'],
            'estimated_price' => ['nullable', 'numeric', 'min:0'],
            'estimated_duration' => ['required', 'string', 'max:120'],
            'working_hours' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:active,inactive'],
        ]);
    }

    protected function authorizeService(Request $request, Service $service): void
    {
        abort_unless($service->service_center_profile_id === $request->user()->serviceCenterProfile?->id, 403);
    }
}
