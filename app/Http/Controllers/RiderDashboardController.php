<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class RiderDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user()->load([
            'driverApplication',
            'rider.motorcycles.documents',
        ]);

        return view('riders.dashboard', [
            'driverApplication' => $user->driverApplication,
            'rider' => $user->rider,
        ]);
    }
}
