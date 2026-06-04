<?php

namespace App\Http\Controllers\Dealership;

use App\Http\Controllers\Controller;
use App\Models\DealerInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DealershipInquiryController extends Controller
{
    public function index(Request $request): View
    {
        $profile = $request->user()->dealershipProfile;

        return view('providers.dealership.inquiries.index', [
            'inquiries' => DealerInquiry::query()
                ->whereHas('motorcycle', fn ($query) => $query->whereBelongsTo($profile))
                ->with(['motorcycle', 'rider'])
                ->latest()
                ->paginate(15),
        ]);
    }

    public function update(Request $request, DealerInquiry $inquiry): RedirectResponse
    {
        $profile = $request->user()->dealershipProfile;

        abort_unless($inquiry->motorcycle()->whereBelongsTo($profile)->exists(), 403);

        $validated = $request->validate([
            'status' => ['required', Rule::in(DealerInquiry::statuses())],
        ]);

        if (! $inquiry->canTransitionTo($validated['status'])) {
            return back()->withErrors(['status' => 'This inquiry cannot move to that status.']);
        }

        $inquiry->update($validated);

        return back()->with('status', 'Inquiry status updated.');
    }
}
