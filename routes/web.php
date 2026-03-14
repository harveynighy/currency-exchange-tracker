<?php

use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\HistoricalRatesController;
use App\Http\Controllers\MoneyPageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ExchangeRateController::class, 'index'])->name('home');
Route::post('/convert', [ExchangeRateController::class, 'convert'])->name('convert');

// Historical Charts Routes
Route::get('/charts', [HistoricalRatesController::class, 'index'])->name('charts.index');
Route::get('/charts/data', [HistoricalRatesController::class, 'getData'])->name('charts.data');
Route::view('/faq', 'faq')->name('faq');
Route::view('/how-exchange-rates-work', 'seo.how-exchange-rates-work')->name('seo.how-exchange-rates-work');
Route::view('/currency-glossary', 'seo.currency-glossary')->name('seo.currency-glossary');

// Money pages (SEO landing pages)
Route::get('/exchange-rates', [MoneyPageController::class, 'index'])->name('money.index');
Route::get('/exchange-rate/{from}-to-{to}', [MoneyPageController::class, 'show'])->name('money.show');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');

Route::get('/sitemap.xml', function () {
    $moneyPairs = [
        ['from' => 'USD', 'to' => 'EUR'],
        ['from' => 'EUR', 'to' => 'USD'],
        ['from' => 'GBP', 'to' => 'USD'],
        ['from' => 'USD', 'to' => 'GBP'],
        ['from' => 'USD', 'to' => 'JPY'],
        ['from' => 'EUR', 'to' => 'GBP'],
        ['from' => 'USD', 'to' => 'CAD'],
        ['from' => 'USD', 'to' => 'AUD'],
        ['from' => 'USD', 'to' => 'CHF'],
        ['from' => 'USD', 'to' => 'INR'],
        ['from' => 'USD', 'to' => 'CNY'],
        ['from' => 'AUD', 'to' => 'USD'],
    ];

    $urls = [
        route('home'),
        route('charts.index'),
        route('faq'),
        route('seo.how-exchange-rates-work'),
        route('seo.currency-glossary'),
        route('money.index'),
        route('blog.index'),
        route('api-docs'),
        route('privacy-policy'),
        route('cookie-policy'),
        route('terms-of-service'),
        route('refund-policy'),
        route('acceptable-use-policy'),
        route('data-processing-agreement'),
        route('api-terms'),
    ];

    foreach ($moneyPairs as $pair) {
        $urls[] = route('money.show', ['from' => $pair['from'], 'to' => $pair['to']]);
    }

    $blogUrls = collect(config('blog.posts', []))
        ->filter(function (array $post) {
            if (!(bool) ($post['is_published'] ?? true)) {
                return false;
            }

            if (!isset($post['published_at'])) {
                return true;
            }

            return now()->gte(\Illuminate\Support\Carbon::parse($post['published_at']));
        })
        ->pluck('slug')
        ->map(fn ($slug) => route('blog.show', ['slug' => $slug]))
        ->all();

    $urls = array_merge($urls, $blogUrls);

    return response()
        ->view('sitemap', [
            'urls' => $urls,
            'lastmod' => now()->toAtomString(),
        ])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

// API Documentation
Route::view('/api-docs', 'api-docs')->name('api-docs');

Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

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
