<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('providers.seller.profile.edit', ['profile' => $request->user()->sellerProfile]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'seller_type' => ['nullable', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
            'logo' => ['nullable', 'string', 'max:500'],
        ]);

        $request->user()->sellerProfile->update($validated);

        return back()->with('status', 'Seller profile updated.');
    }
}
