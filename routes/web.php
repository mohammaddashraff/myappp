<?php

use App\Http\Controllers\DriverApplicationStatusController;
use App\Http\Controllers\DriverDashboardController;
use App\Http\Controllers\DriverSignupController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/drivers/signup', [DriverSignupController::class, 'create'])
    ->name('drivers.signup.create');

Route::get('/drivers/signup/success', [DriverSignupController::class, 'success'])
    ->name('drivers.signup.success');

Route::get('/drivers/signup/{step}', [DriverSignupController::class, 'show'])
    ->whereIn('step', ['account', 'identity', 'contact', 'documents', 'vehicle', 'review'])
    ->name('drivers.signup.step');

Route::post('/drivers/signup/{step}', [DriverSignupController::class, 'store'])
    ->whereIn('step', ['account', 'identity', 'contact', 'documents', 'vehicle', 'review'])
    ->name('drivers.signup.step.store');

Route::get('/dashboard', function () {
    return redirect()->route('drivers.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/drivers/dashboard', DriverDashboardController::class)
        ->name('drivers.dashboard');

    Route::get('/drivers/application/status', DriverApplicationStatusController::class)
        ->name('drivers.application.status');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
