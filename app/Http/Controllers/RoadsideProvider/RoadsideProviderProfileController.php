<?php

namespace App\Http\Controllers\RoadsideProvider;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoadsideProviderProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('providers.roadside-provider.profile.edit', ['profile' => $request->user()->roadsideProviderProfile]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'provider_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:120'],
            'coverage_area' => ['nullable', 'string', 'max:255'],
        ]);

        $request->user()->roadsideProviderProfile->update($validated);

        return back()->with('status', 'Roadside provider profile updated.');
    }
}
