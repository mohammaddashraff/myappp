<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $user = $request->user()->loadMissing('subscription');

        return view('dashboard', [
            'recentAds' => Ad::query()
                ->whereBelongsTo($user)
                ->latest()
                ->limit(5)
                ->get(),
            'subscription' => $user->subscription,
        ]);
    }
}
