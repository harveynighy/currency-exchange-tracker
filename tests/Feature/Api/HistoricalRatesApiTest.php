<?php

namespace Tests\Feature\Api;

use App\Models\ApiMonthlyUsage;
use App\Models\ExchangeRate;
use App\Models\ExchangeRateSnapshot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HistoricalRatesApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_history_endpoint_returns_pair_data_for_date_range(): void
    {
        $user = User::factory()->create(['api_key' => 'test-api-key']);

        $firstSnapshot = ExchangeRateSnapshot::create([
            'rate_date' => '2026-03-01',
            'base' => 'USD',
            'provider' => 'historical_import',
            'fetched_at' => now(),
            'is_complete' => true,
        ]);

        $secondSnapshot = ExchangeRateSnapshot::create([
            'rate_date' => '2026-03-02',
            'base' => 'USD',
            'provider' => 'historical_import',
            'fetched_at' => now(),
            'is_complete' => true,
        ]);

        ExchangeRate::insert([
            [
                'exchange_rate_snapshot_id' => $firstSnapshot->id,
                'currency' => 'GBP',
                'rate' => 1.25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'exchange_rate_snapshot_id' => $secondSnapshot->id,
                'currency' => 'GBP',
                'rate' => 1.30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $user->api_key)
            ->getJson('/api/v1/history?from=GBP&to=USD&start_date=2026-03-01&end_date=2026-03-02');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.from', 'GBP')
            ->assertJsonPath('data.to', 'USD')
            ->assertJsonPath('data.count', 2)
            ->assertJsonPath('data.rates.0.date', '2026-03-01')
            ->assertJsonPath('data.rates.0.rate', 0.8)
            ->assertJsonPath('data.rates.1.date', '2026-03-02')
            ->assertJsonPath('data.rates.1.rate', 0.769231);
    }

    public function test_history_endpoint_requires_valid_date_range(): void
    {
        $user = User::factory()->create(['api_key' => 'test-api-key']);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $user->api_key)
            ->getJson('/api/v1/history?from=GBP&to=USD&start_date=2026-03-05&end_date=2026-03-01');

        $response->assertStatus(422);
    }

    public function test_free_plan_is_blocked_after_750_requests_in_a_month(): void
    {
        $user = User::factory()->create([
            'api_key' => 'test-api-key',
            'api_plan' => 'free',
        ]);

        $snapshot = ExchangeRateSnapshot::create([
            'rate_date' => '2026-03-01',
            'base' => 'USD',
            'provider' => 'historical_import',
            'fetched_at' => now(),
            'is_complete' => true,
        ]);

        ExchangeRate::create([
            'exchange_rate_snapshot_id' => $snapshot->id,
            'currency' => 'GBP',
            'rate' => 1.25,
        ]);

        ApiMonthlyUsage::create([
            'user_id' => $user->id,
            'usage_month' => now()->startOfMonth()->toDateString(),
            'request_count' => 750,
        ]);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $user->api_key)
            ->getJson('/api/v1/history?from=GBP&to=USD&start_date=2026-03-01&end_date=2026-03-01');

        $response
            ->assertStatus(429)
            ->assertJsonPath('error', 'Monthly quota exceeded')
            ->assertJsonPath('plan', 'free')
            ->assertJsonPath('monthly_limit', 750)
            ->assertJsonPath('requests_used', 750)
            ->assertJsonPath('requests_remaining', 0);
    }
}