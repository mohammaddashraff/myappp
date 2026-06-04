<?php

namespace App\Http\Controllers\Dealership;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DealershipProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('providers.dealership.profile.edit', ['profile' => $request->user()->dealershipProfile]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'dealership_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
            'logo' => ['nullable', 'string', 'max:500'],
        ]);

        $request->user()->dealershipProfile->update($validated);

        return back()->with('status', 'Dealership profile updated.');
    }
}
