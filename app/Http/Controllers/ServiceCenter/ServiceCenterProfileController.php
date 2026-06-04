<?php

namespace App\Http\Controllers\ServiceCenter;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceCenterProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('providers.service-center.profile.edit', ['profile' => $request->user()->serviceCenterProfile]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'center_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
            'working_hours' => ['nullable', 'string', 'max:255'],
        ]);

        $request->user()->serviceCenterProfile->update($validated);

        return back()->with('status', 'Service center profile updated.');
    }
}
