<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DriverDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('drivers.dashboard', [
            'driverApplication' => $request->user()->driverApplication,
        ]);
    }
}
