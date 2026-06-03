<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class RiderGarageController extends Controller
{
    public function __invoke(Request $request): View
    {
        $rider = $request->user()
            ->rider()
            ->with('motorcycles.documents')
            ->first();

        return view('riders.garage', [
            'rider' => $rider,
            'motorcycles' => $rider?->motorcycles ?? collect(),
        ]);
    }
}
