<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProviderApplicationRequest;
use App\Models\ProviderApplication;
use App\Support\AccessRoles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProviderApplicationController extends Controller
{
    public function index(Request $request): View
    {
        return view('provider-applications.index', [
            'applications' => $request->user()
                ->providerApplications()
                ->latest()
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('provider-applications.create', [
            'providerRoles' => AccessRoles::providerRoles(),
        ]);
    }

    public function store(StoreProviderApplicationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $existingActiveApplication = $request->user()
            ->providerApplications()
            ->where('requested_role', $validated['requested_role'])
            ->active()
            ->exists();

        if ($existingActiveApplication) {
            return back()
                ->withErrors(['requested_role' => 'You already have an active application or provider profile for this role.'])
                ->withInput();
        }

        $request->user()->providerApplications()->create([
            ...$validated,
            'display_name' => $validated['display_name'] ?? $validated['business_name'],
            'status' => ProviderApplication::STATUS_PENDING,
        ]);

        return redirect()
            ->route('rider.provider-applications.index')
            ->with('status', 'provider-application-submitted');
    }
}
