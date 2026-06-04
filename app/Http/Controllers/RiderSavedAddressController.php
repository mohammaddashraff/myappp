<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesRider;
use App\Models\RiderSavedAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RiderSavedAddressController extends Controller
{
    use ResolvesRider;

    public function store(Request $request): RedirectResponse
    {
        $rider = $this->riderFrom($request);
        $validated = $this->validateAddress($request);
        $isFirstAddress = ! $rider->savedAddresses()->exists();
        $isDefault = $request->boolean('is_default') || $isFirstAddress;

        if ($isDefault) {
            $rider->savedAddresses()->update(['is_default' => false]);
        }

        $rider->savedAddresses()->create([
            ...$validated,
            'is_default' => $isDefault,
        ]);

        return back()->with('status', 'rider-address-saved');
    }

    public function update(Request $request, RiderSavedAddress $riderSavedAddress): RedirectResponse
    {
        $rider = $this->riderFrom($request);
        abort_unless($riderSavedAddress->rider()->is($rider), 404);

        $validated = $this->validateAddress($request);
        $isDefault = $request->boolean('is_default');

        if ($isDefault) {
            $rider->savedAddresses()->whereKeyNot($riderSavedAddress->id)->update(['is_default' => false]);
        }

        $riderSavedAddress->update([
            ...$validated,
            'is_default' => $isDefault || ! $rider->savedAddresses()->whereKeyNot($riderSavedAddress->id)->exists(),
        ]);

        return back()->with('status', 'rider-address-updated');
    }

    public function destroy(Request $request, RiderSavedAddress $riderSavedAddress): RedirectResponse
    {
        $rider = $this->riderFrom($request);
        abort_unless($riderSavedAddress->rider()->is($rider), 404);

        $wasDefault = $riderSavedAddress->is_default;
        $riderSavedAddress->delete();

        if ($wasDefault) {
            $rider->savedAddresses()->latest()->first()?->update(['is_default' => true]);
        }

        return back()->with('status', 'rider-address-deleted');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validateAddress(Request $request): array
    {
        return $request->validate([
            'label' => ['required', 'string', 'max:80'],
            'recipient_name' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{8,30}$/'],
            'city' => ['required', 'string', 'max:120'],
            'area' => ['required', 'string', 'max:120'],
            'street' => ['required', 'string', 'max:180'],
            'building' => ['required', 'string', 'max:60'],
            'floor' => ['required', 'string', 'max:60'],
            'apartment' => ['required', 'string', 'max:60'],
            'landmark' => ['nullable', 'string', 'max:180'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
