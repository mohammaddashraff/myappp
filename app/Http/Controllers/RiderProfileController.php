<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRiderProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiderProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('riders.profile.edit', [
            'rider' => $request->user()->rider,
        ]);
    }

    public function update(UpdateRiderProfileRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        $user->rider()->updateOrCreate(
            ['user_id' => $user->id],
            [
                ...$validated,
                'profile_completed_at' => now(),
            ],
        );

        $user->forceFill([
            'name' => $validated['full_name'],
        ])->save();

        return redirect()
            ->route('rider.dashboard')
            ->with('status', 'rider-profile-updated');
    }
}
