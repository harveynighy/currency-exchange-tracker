<?php

use App\Http\Controllers\ExchangeRateController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ExchangeRateController::class, 'index'])->name('home');
Route::post('/convert', [ExchangeRateController::class, 'convert'])->name('convert');
