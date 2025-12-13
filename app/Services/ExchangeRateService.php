<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ExchangeRateService
{
    protected $apiKey;
    protected $apiUrl;
    protected $apiVersion = 'v6';

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->apiKey = env('EXCHANGE_API_KEY');
        $this->apiUrl = 'https://' . $this->apiVersion . '.exchangerate-api.com/' . $this->apiVersion . '/latest/';
    }

    public function getRates($baseCurrency = 'GBP')
    {
        // Cache for 1 hour
        return Cache::remember("rates_{$baseCurrency}", 3600, function () use ($baseCurrency) {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
            ])->get("{$this->apiUrl}/{$baseCurrency}");

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to fetch exchange rates');
        });
    }
}
