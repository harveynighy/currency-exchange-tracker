<?php

namespace Tests\Unit;

use App\Services\ExchangeRateService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExchangeRateServiceTest extends TestCase
{
    public function test_get_rates_returns_exchange_rates()
    {
        // Mock the API response
        Http::fake([
            '*' => Http::response([
                'conversion_rates' => [
                    'USD' => 1,
                    'EUR' => 0.85,
                    'GBP' => 0.73,
                ],
            ], 200),
        ]);

        $service = new ExchangeRateService();
        $rates = $service->getRates('USD');

        $this->assertArrayHasKey('conversion_rates', $rates);
        $this->assertIsArray($rates['conversion_rates']);
    }
}
