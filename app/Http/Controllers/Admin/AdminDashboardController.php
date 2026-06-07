<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'totalUsers' => User::query()->count(),
            'activeSubscriptions' => Subscription::query()->where('status', Subscription::STATUS_ACTIVE)->count(),
            'inactiveSubscriptions' => Subscription::query()->where('status', Subscription::STATUS_INACTIVE)->count(),
            'publishedAds' => Ad::query()->where('status', Ad::STATUS_PUBLISHED)->count(),
            'draftAds' => Ad::query()->where('status', Ad::STATUS_DRAFT)->count(),
            'soldAds' => Ad::query()->where('status', Ad::STATUS_SOLD)->count(),
            'recentSubscriptions' => Subscription::query()->with('user')->latest()->limit(6)->get(),
        ]);
    }
}
