<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerProfileController extends Controller
{
    public function show(Request $request, User $seller): View
    {
        $seller->load([
            'receivedReviews' => fn ($query) => $query->with('reviewer')->latest(),
        ]);

        $currentAds = Ad::query()
            ->whereBelongsTo($seller)
            ->where('status', Ad::STATUS_PUBLISHED)
            ->latest()
            ->limit(8)
            ->get();

        $pastAds = Ad::query()
            ->whereBelongsTo($seller)
            ->where('status', Ad::STATUS_SOLD)
            ->latest('sold_at')
            ->limit(8)
            ->get();

        $viewerReview = $request->user()->givenReviews()
            ->where('reviewed_user_id', $seller->id)
            ->first();

        return view('seller-profiles.show', [
            'seller' => $seller,
            'currentAds' => $currentAds,
            'pastAds' => $pastAds,
            'viewerReview' => $viewerReview,
        ]);
    }
}
