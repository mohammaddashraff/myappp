<?php

use App\Http\Controllers\DriverApplicationStatusController;
use App\Http\Controllers\DriverDashboardController;
use App\Http\Controllers\DriverSignupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiderDashboardController;
use App\Http\Controllers\RiderMotorcycleController;
use App\Http\Controllers\RiderProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/locale/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['ar', 'en'], true), 404);

    session(['locale' => $locale]);

    return back();
})->name('locale.switch');

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
    return redirect()->route('rider.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/rider/dashboard', RiderDashboardController::class)
        ->name('rider.dashboard');

    Route::get('/rider/garage', [RiderMotorcycleController::class, 'index'])
        ->name('rider.garage');
    Route::get('/rider/motorcycle-brands/{motorcycleBrand}/models', [RiderMotorcycleController::class, 'models'])
        ->name('rider.motorcycle-brands.models');
    Route::get('/rider/motorcycles/create', [RiderMotorcycleController::class, 'create'])
        ->name('rider.motorcycles.create');
    Route::post('/rider/motorcycles', [RiderMotorcycleController::class, 'store'])
        ->name('rider.motorcycles.store');
    Route::get('/rider/motorcycles/{motorcycle}', [RiderMotorcycleController::class, 'show'])
        ->name('rider.motorcycles.show');
    Route::get('/rider/motorcycles/{motorcycle}/edit', [RiderMotorcycleController::class, 'edit'])
        ->name('rider.motorcycles.edit');
    Route::match(['put', 'patch'], '/rider/motorcycles/{motorcycle}', [RiderMotorcycleController::class, 'update'])
        ->name('rider.motorcycles.update');
    Route::delete('/rider/motorcycles/{motorcycle}', [RiderMotorcycleController::class, 'destroy'])
        ->name('rider.motorcycles.destroy');

    Route::get('/rider/profile/edit', [RiderProfileController::class, 'edit'])
        ->name('rider.profile.edit');

    Route::patch('/rider/profile', [RiderProfileController::class, 'update'])
        ->name('rider.profile.update');

    Route::get('/drivers/dashboard', DriverDashboardController::class)
        ->name('drivers.dashboard');

    Route::get('/drivers/application/status', DriverApplicationStatusController::class)
        ->name('drivers.application.status');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
