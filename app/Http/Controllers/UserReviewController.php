<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserReviewController extends Controller
{
    public function store(Request $request, User $seller): RedirectResponse
    {
        if ($request->user()->is($seller)) {
            return back()->withErrors([
                'rating' => __('app.review_self_blocked'),
            ]);
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $request->user()->givenReviews()->updateOrCreate(
            ['reviewed_user_id' => $seller->id],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ],
        );

        return redirect()
            ->route('sellers.show', $seller)
            ->with('status', __('app.review_saved'));
    }
}
