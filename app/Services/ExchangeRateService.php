<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class ExchangeRateService
{
    protected string $apiKey;
    protected string $apiUrl;
    protected string $apiVersion = 'v6';

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->apiVersion = (string) config('services.exchange_rate.api_version', 'v6');

        $apiKey = config('services.exchange_rate.api_key');

        if (! is_string($apiKey) || trim($apiKey) === '') {
            throw new \RuntimeException('Missing EXCHANGE_API_KEY. Please set it in your environment configuration.');
        }

        $this->apiKey = $apiKey;
        $this->apiUrl = 'https://' . $this->apiVersion . '.exchangerate-api.com/' . $this->apiVersion . '/' . $this->apiKey . '/latest';
    }

    public function getRates($baseCurrency = 'GBP')
    {
        // validate the currency code
        $baseCurrency = strtoupper(trim($baseCurrency));

        if (!preg_match('/^[A-Z]{3}$/', $baseCurrency)) {
            throw new \InvalidArgumentException('Invalid currency code format');
        }

        // rate limit api calls
        $key = 'exchange-rate-api:' . $baseCurrency;

        if (RateLimiter::tooManyAttempts($key, 10)) {
            throw new \Exception('Too many requests. Please try again later.');
        }

        RateLimiter::hit($key, 60); // 10 attempts per minute
        return Cache::remember("rates_{$baseCurrency}", 3600, function () use ($baseCurrency) {
            try {
                $response = Http::timeout(10) // timeout after 10 seconds
                ->get("{$this->apiUrl}/{$baseCurrency}");

                if ($response->successful()) {
                    $data = $response->json();

                    if (!isset($data['conversion_rates'])) {
                        Log::error('Invalid API response structure', ['response' => $data]);
                        throw new \Exception('Invalid response from exchange rate API');
                    }

                    return $data;
                }

                // log any errors with the API
                Log::error('Exchange Rate API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'currency' => $baseCurrency
                ]);

                throw new \Exception('Failed to fetch exchange rates: ' . $response->status());
            } catch (\Illuminate\Http\Client\ConnectionException $e) {

                // try using stale cache as fallback
                $staleData = Cache::get("rates_{$baseCurrency}_backup");
                if ($staleData) {
                    Log::warning('Using stale exchange rate data', ['currency' => $baseCurrency]);
                    return $staleData;
                }
                Log::error('Connection timeout to Exchange Rate API', ['error' => $e->getMessage()]);
                throw new \Exception('Unable to connect to exchange rate service');
            }
        });
    }
}
