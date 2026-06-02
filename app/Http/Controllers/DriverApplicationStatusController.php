<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DriverApplicationStatusController extends Controller
{
    public function __invoke(Request $request): RedirectResponse|View
    {
        $driverApplication = $request->user()->driverApplication;

        if ($driverApplication === null || $driverApplication->approval_status !== 'pending') {
            return redirect()->route('drivers.dashboard');
        }

        return view('drivers.status', [
            'driver' => $driverApplication,
        ]);
    }
}
