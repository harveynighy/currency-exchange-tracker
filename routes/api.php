<?php

use App\Http\Controllers\Api\V1\CurrencyConversionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('api.auth')->group(function () {
    Route::get('/convert', [CurrencyConversionController::class, 'convert']);
});
