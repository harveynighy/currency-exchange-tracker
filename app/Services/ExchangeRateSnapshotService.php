<?php

namespace App\Services;

use App\Models\ExchangeRate;
use App\Models\ExchangeRateSnapshot;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class ExchangeRateSnapshotService
{

    protected string $apiKey;
    protected string $apiUrl;
    protected string $apiVersion = 'v6';

    public function __construct()
    {
        $this->apiKey = env('EXCHANGE_API_KEY');
        // GET https://v6.exchangerate-api.com/v6/YOUR-API-KEY/history/USD/YEAR/MONTH/DAY
        $this->apiUrl = 'https://' . $this->apiVersion . '.exchangerate-api.com/' . $this->apiVersion . '/' . $this->apiKey . '/history';
    }

    // 1, Check DB for rates, no rates? Get rates from API and store in DB for next time.
    public function getRatesForDate(int $year, int $month, int $day, string $base = 'GBP', string $provider = 'default'): array
    {
        $base = $this->normalizeValidateCurrency($base);
        $provider = strtolower(trim($provider));
        $date = $this->normalizeDate($year, $month, $day);

        // In DB?
        $snapshot = $this->findSnapshot($date, $base, $provider);
        if ($snapshot && $snapshot->is_complete) {
            return $this->snapshotRatesMap($snapshot);
        }

        $lockKey = $this->lockKey($date, $base, $provider);

        return Cache::lock($lockKey, 30)->block(5, function () use ($date, $year, $month, $day, $base, $provider) {

            // Check DB again (inside lock)
            $snapshot = $this->findSnapshot($date, $base, $provider);
            if ($snapshot && $snapshot->is_complete) {
                return $this->snapshotRatesMap($snapshot);
            }

            // Fetch from API
            $payload = $this->fetchFromProvider($year, $month, $day, $base, $provider);

            $rates = $payload['conversion_rates'];
            $fetchedAt = now();

            // 5) Store snapshot + rates (transaction + upsert inside)
            $snapshot = $this->storeSnapshotAndRates($year, $month, $day, $base, $provider, $rates, $fetchedAt);

            // 6) Return rates
            return $this->snapshotRatesMap($snapshot);
        });
    }


    // Internal methods
    private function normalizeValidateCurrency(string $code): string
    {
        $code = strtoupper(trim($code));

        if (!preg_match('/^[A-Z]{3}$/', $code)) {
            throw new \InvalidArgumentException('Invalid currency code format');
        }

        return $code;
    }

    private function normalizeDate(int $year, int $month, int $day): string
    {
        // Validates real dates like 2025-02-31 (invalid)
        if (!checkdate($month, $day, $year)) {
            throw new \InvalidArgumentException("Invalid date: {$year}-{$month}-{$day}");
        }

        // Canonical format with zero padding
        return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }

    private function lockKey(string $date, string $base, string $provider): string
    {
        $provider = strtolower(trim($provider));
        $base = $this->normalizeValidateCurrency($base);

        return "rates:snapshot:{$provider}:{$base}:{$date}";
    }

    private function findSnapshot(string $date, string $base, string $provider): ?ExchangeRateSnapshot
    {
        return ExchangeRateSnapshot::where('rate_date', $date)
            ->where('base', $base)
            ->where('provider', $provider)
            ->first();
    }

    private function snapshotRatesMap(ExchangeRateSnapshot $snapshot): array
    {
        return $snapshot->rates()
            ->pluck('rate', 'currency')
            ->toArray();
    }

    //Provider cannot have leading zeros
    private function fetchFromProvider(int $year, int $month, int $day, string $base, string $provider): array
    {
        $currency = $this->normalizeValidateCurrency($base);
        $provider = strtolower(trim($provider));

        // Rate limit key (global). You can later add user id/ip if you want.
        $key = "exchange-rate-history-api:{$provider}:{$currency}:{$year}-{$month}-{$day}";

        if (RateLimiter::tooManyAttempts($key, 10)) {
            throw new \RuntimeException('Too many requests. Please try again later.');
        }

        RateLimiter::hit($key, 60); // 10 attempts per minute

        try {
            $response = Http::timeout(10)
                // Optional stability improvement:
                // ->retry(2, 200, throw: false) // retry twice with 200ms delay
                ->get("{$this->apiUrl}/{$currency}/{$year}/{$month}/{$day}");

            if (!$response->successful()) {
                Log::error('Exchange Rate API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'currency' => $currency,
                    'provider' => $provider,
                    'date' => "{$year}-{$month}-{$day}",
                ]);

                throw new \RuntimeException("Failed to fetch exchange rates: HTTP {$response->status()}");
            }

            $data = $response->json();

            if (!is_array($data) || !isset($data['conversion_rates']) || !is_array($data['conversion_rates'])) {
                Log::error('Invalid API response structure', [
                    'currency' => $currency,
                    'provider' => $provider,
                    'date' => "{$year}-{$month}-{$day}",
                    'response' => $data,
                ]);

                throw new \RuntimeException('Invalid response from exchange rate API');
            }

            return $data;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Connection error to Exchange Rate API', [
                'error' => $e->getMessage(),
                'currency' => $currency,
                'provider' => $provider,
                'date' => "{$year}-{$month}-{$day}",
            ]);

            throw new \RuntimeException('Unable to connect to exchange rate service', previous: $e);
        }
    }

    private function storeSnapshotAndRates(
        int    $year,
        int    $month,
        int    $day,
        string $base,
        string $provider,
        array  $rates,
               $fetchedAt
    ): ExchangeRateSnapshot
    {
        $base = $this->normalizeValidateCurrency($base);
        $provider = strtolower(trim($provider));
        $date = $this->normalizeDate($year, $month, $day);

        return DB::transaction(function () use ($date, $base, $provider, $rates, $fetchedAt) {
            // Create or find the snapshot row for this date/base/provider
            $snapshot = ExchangeRateSnapshot::firstOrCreate(
                [
                    'rate_date' => $date,
                    'base' => $base,
                    'provider' => $provider,
                ],
                [
                    'fetched_at' => $fetchedAt,
                    'is_complete' => false,
                ]
            );

            $now = now();
            $rows = [];

            foreach ($rates as $currency => $rate) {
                $currency = $this->normalizeValidateCurrency((string)$currency);

                $rows[] = [
                    'exchange_rate_snapshot_id' => $snapshot->id,
                    'currency' => $currency,
                    'rate' => $rate,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // If there are no rates, don't mark complete
            if (empty($rows)) {
                $snapshot->forceFill([
                    'fetched_at' => $fetchedAt,
                    'is_complete' => false,
                ])->save();

                return $snapshot;
            }

            // Upsert rates (requires unique index on snapshot_id + currency)
            ExchangeRate::upsert(
                $rows,
                ['exchange_rate_snapshot_id', 'currency'],
                ['rate', 'updated_at']
            );

            // snapshot complete
            $snapshot->forceFill([
                'fetched_at' => $fetchedAt,
                'is_complete' => true,
            ])->save();

            return $snapshot->fresh();
        });
    }


}
