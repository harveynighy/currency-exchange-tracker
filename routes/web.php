<?php

use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\HistoricalRatesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ExchangeRateController::class, 'index'])->name('home');
Route::post('/convert', [ExchangeRateController::class, 'convert'])->name('convert');

// Historical Charts Routes
Route::get('/charts', [HistoricalRatesController::class, 'index'])->name('charts.index');
Route::get('/charts/data', [HistoricalRatesController::class, 'getData'])->name('charts.data');

// API Documentation
Route::view('/api-docs', 'api-docs')->name('api-docs');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password');
    Route::post('/profile/api-key/generate', [ProfileController::class, 'generateApiKey'])
        ->name('profile.api-key.generate');
    Route::delete('/profile/api-key/revoke', [ProfileController::class, 'revokeApiKey'])
        ->name('profile.api-key.revoke');

    Route::post('/billing/checkout', [BillingController::class, 'checkout'])
        ->name('billing.checkout');
    Route::post('/billing/portal', [BillingController::class, 'portal'])
        ->name('billing.portal');
    Route::get('/billing/success', [BillingController::class, 'success'])
        ->name('billing.success');
    Route::get('/profile/invoices', [BillingController::class, 'invoices'])
        ->name('profile.invoices');
});

// Register Routes
Route::view('/register', 'auth.register')
    ->middleware('guest')
    ->name('register');
Route::post('/register', Register::class)
    ->middleware('guest');

//Logout Route
Route::post('/logout', Logout::class)
    ->middleware('auth')
    ->name('logout');


//Login
Route::view('/login', 'auth.login')
    ->middleware('guest')
    ->name('login');

Route::post('/login', Login::class)
    ->middleware('guest');

// Policy Routes
Route::view('/privacy-policy', 'policies.privacy-policy')->name('privacy-policy');
Route::view('/cookie-policy', 'policies.cookie-policy')->name('cookie-policy');
Route::view('/data-processing-agreement', 'policies.data-processing-agreement')->name('data-processing-agreement');
Route::view('/terms-of-service', 'policies.terms-of-service')->name('terms-of-service');
Route::view('/refund-policy', 'policies.refund-policy')->name('refund-policy');
Route::view('/acceptable-use-policy', 'policies.acceptable-use-policy')->name('acceptable-use-policy');
Route::view('/api-terms', 'policies.api-terms')->name('api-terms');
