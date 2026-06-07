<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDirectoryController;
use App\Http\Controllers\Admin\AdminSubscriptionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerProfileController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/mobile-demo', function () {
    return view('mobile-demo');
})->name('mobile.demo');

Route::get('/locale/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['ar', 'en'], true), 404);

    session(['locale' => $locale]);

    return back();
})->name('locale.switch');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:super_admin|admin')
        ->group(function (): void {
            Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
            Route::get('/users', [AdminDirectoryController::class, 'users'])->name('users.index');
            Route::get('/subscriptions', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
        });

    Route::get('/ads', [AdController::class, 'index'])->name('ads.index');
    Route::get('/ads/my', [AdController::class, 'myAds'])->name('ads.my');
    Route::get('/ads/create', [AdController::class, 'create'])->name('ads.create');
    Route::post('/ads', [AdController::class, 'store'])->name('ads.store');
    Route::get('/ads/{ad}', [AdController::class, 'show'])->name('ads.show');
    Route::get('/ads/{ad}/edit', [AdController::class, 'edit'])->name('ads.edit');
    Route::match(['put', 'patch'], '/ads/{ad}', [AdController::class, 'update'])->name('ads.update');
    Route::patch('/ads/{ad}/mark-sold', [AdController::class, 'markSold'])->name('ads.mark-sold');
    Route::post('/ads/{ad}/reveal-phone', [AdController::class, 'revealPhone'])->name('ads.reveal-phone');

    Route::get('/sellers/{seller}', [SellerProfileController::class, 'show'])->name('sellers.show');
    Route::post('/sellers/{seller}/reviews', [UserReviewController::class, 'store'])->name('sellers.reviews.store');

    Route::get('/subscription', [SubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::post('/subscription', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('/subscription/checkout', [SubscriptionController::class, 'checkout'])->name('subscriptions.checkout');
    Route::post('/subscription/checkout', [SubscriptionController::class, 'pay'])->name('subscriptions.pay');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
