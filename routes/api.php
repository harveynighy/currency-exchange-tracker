<?php

use App\Http\Controllers\Api\V1\HistoricalRatesController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

Route::prefix('v1')->middleware('api.auth')->group(function () {
    Route::get('/history', [HistoricalRatesController::class, 'show']);
});
