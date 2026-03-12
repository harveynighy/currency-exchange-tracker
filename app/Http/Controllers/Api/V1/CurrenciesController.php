<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CurrenciesController extends Controller
{
    /**
     * Return all currencies present in the historical dataset, with their full names.
     *
     * GET /api/v1/currencies
     */
    public function index(): JsonResponse
    {
        $currencies = Cache::remember('api_currencies_list', 86400, function () {
            $names = config('currencies.supported', []);

            // USD is the dataset base — not stored as an exchange_rate row, include it explicitly
            $inDataset = DB::table('exchange_rates')
                ->select('currency')
                ->groupBy('currency')
                ->orderBy('currency')
                ->pluck('currency')
                ->prepend('USD') // base currency
                ->unique()
                ->sort()
                ->values();

            $result = [];
            foreach ($inDataset as $code) {
                $result[$code] = [
                    'code' => $code,
                    'name' => $names[$code] ?? $code,
                ];
            }

            return $result;
        });

        return response()->json([
            'success' => true,
            'data'    => [
                'count'      => count($currencies),
                'currencies' => array_values($currencies),
            ],
        ]);
    }
}
