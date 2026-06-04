<?php

namespace App\Http\Controllers\DeliveryPartner;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeliveryPartnerProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('providers.delivery-partner.profile.edit', ['profile' => $request->user()->deliveryPartnerProfile]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'national_id' => ['nullable', 'string', 'max:80'],
            'license_number' => ['nullable', 'string', 'max:80'],
        ]);

        $request->user()->deliveryPartnerProfile->update($validated);

        return back()->with('status', 'Delivery profile updated.');
    }
}
