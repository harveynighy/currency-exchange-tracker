<?php

use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ExchangeRateController::class, 'index'])->name('home');
Route::post('/convert', [ExchangeRateController::class, 'convert'])->name('convert');

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
