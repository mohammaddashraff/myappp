<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\View\View;

class AdminSubscriptionController extends Controller
{
    public function index(): View
    {
        return view('admin.subscriptions.index', [
            'subscriptions' => Subscription::query()
                ->with('user')
                ->latest()
                ->paginate(20),
        ]);
    }
}
