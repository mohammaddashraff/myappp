<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DriverSignupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/drivers/signup', [DriverSignupController::class, 'create'])
    ->name('drivers.signup.create');

Route::get('/drivers/signup/success', [DriverSignupController::class, 'success'])
    ->name('drivers.signup.success');

Route::get('/drivers/signup/{step}', [DriverSignupController::class, 'show'])
    ->whereIn('step', ['identity', 'contact', 'documents', 'vehicle', 'review'])
    ->name('drivers.signup.step');

Route::post('/drivers/signup/{step}', [DriverSignupController::class, 'store'])
    ->whereIn('step', ['identity', 'contact', 'documents', 'vehicle', 'review'])
    ->name('drivers.signup.step.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
