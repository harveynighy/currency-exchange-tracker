<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CurrencyConversionController extends Controller
{
    protected $exchangeService;

    public function __construct(ExchangeRateService $exchangeService)
    {
        $this->exchangeService = $exchangeService;
    }

    /**
     * convert currency from one to another
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function convert(Request $request): JsonResponse
    {
        // validate request parameters
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'from' => 'required|string|size:3',
            'to' => 'required|string|size:3',
        ]);

        try {
            $amount = $validated['amount'];
            $fromCurrency = strtoupper($validated['from']);
            $toCurrency = strtoupper($validated['to']);

            // get rates from service (uses cache)
            $ratesData = $this->exchangeService->getRates($fromCurrency);

            // check if target currency exists in rates
            if (!isset($ratesData['conversion_rates'][$toCurrency])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Target currency not found',
                    'message' => "Currency '{$toCurrency}' is not available"
                ], 404);
            }

            $rate = $ratesData['conversion_rates'][$toCurrency];
            $convertedAmount = $amount * $rate;

            // return clean DTO
            return response()->json([
                'success' => true,
                'data' => [
                    'amount' => (float) $amount,
                    'from' => $fromCurrency,
                    'to' => $toCurrency,
                    'rate' => (float) $rate,
                    'converted_amount' => (float) round($convertedAmount, 2),
                    'timestamp' => now()->toIso8601String(),
                ]
            ], 200);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid currency code',
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            // check for rate limiting
            if (str_contains($e->getMessage(), 'Too many requests')) {
                return response()->json([
                    'success' => false,
                    'error' => 'Rate limit exceeded',
                    'message' => 'Too many requests. Please try again later.',
                    'retry_after' => 60
                ], 429);
            }

            return response()->json([
                'success' => false,
                'error' => 'Service unavailable',
                'message' => 'Unable to fetch exchange rates. Please try again later.'
            ], 503);
        }
    }
}
