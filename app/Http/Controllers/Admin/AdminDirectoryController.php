<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class AdminDirectoryController extends Controller
{
    public function users(): View
    {
        return view('admin.directories.users', [
            'users' => User::query()->with(['roles', 'subscription'])->latest()->paginate(20),
        ]);
    }
}
