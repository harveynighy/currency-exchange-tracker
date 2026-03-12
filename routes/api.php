<?php

use App\Http\Controllers\Api\V1\BulkRatesController;
use App\Http\Controllers\Api\V1\CompareRatesController;
use App\Http\Controllers\Api\V1\CurrenciesController;
use App\Http\Controllers\Api\V1\ExportController;
use App\Http\Controllers\Api\V1\HistoricalRatesController;
use App\Http\Controllers\Api\V1\MultiRatesController;
use App\Http\Controllers\Api\V1\NearestRateController;
use App\Http\Controllers\Api\V1\StatsController;
use App\Http\Controllers\Api\V1\VolatilityController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

Route::prefix('v1')->middleware('api.auth')->group(function () {

    // ── Free + ────────────────────────────────────────────────────────────
    Route::get('/history', [HistoricalRatesController::class, 'show']);
        Route::get('/currencies', [CurrenciesController::class, 'index']);

    // ── Pro + ─────────────────────────────────────────────────────────────
    Route::middleware('api.plan:pro,business')->group(function () {
        Route::get('/stats',   [StatsController::class,       'show']);
        Route::get('/bulk',    [BulkRatesController::class,   'show']);
        Route::get('/nearest', [NearestRateController::class, 'show']);
        Route::get('/compare', [CompareRatesController::class,'show']);
    });

    // ── Business + ────────────────────────────────────────────────────────
    Route::middleware('api.plan:business')->group(function () {
        Route::get('/multi',      [MultiRatesController::class, 'show']);
        Route::get('/volatility', [VolatilityController::class, 'show']);
        Route::get('/export',     [ExportController::class,     'show']);
    });
});
