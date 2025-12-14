<?php

use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\ExchangeRateController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ExchangeRateController::class, 'index'])->name('home');
Route::post('/convert', [ExchangeRateController::class, 'convert'])->name('convert');

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
