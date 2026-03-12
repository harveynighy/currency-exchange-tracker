<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BulkRatesController extends Controller
{
    /**
     * Return all available exchange rates for a single date, relative to a base currency.
     *
     * GET /api/v1/bulk?date=2026-03-01&base=USD
     */
    public function show(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
            'base' => ['nullable', 'string', 'size:3', 'regex:/^[A-Za-z]{3}$/'],
        ]);

        $date     = $validated['date'];
        $base     = strtoupper($validated['base'] ?? 'USD');
        $dataset  = 'USD';

        $cacheKey = "api_bulk_{$base}_{$date}";

        $result = Cache::remember($cacheKey, 3600, function () use ($date, $base, $dataset) {
            // Find the snapshot for this date
            $snapshot = DB::table('exchange_rate_snapshots')
                ->whereDate('rate_date', $date)
                ->where('base', $dataset)
                ->where('is_complete', true)
                ->first();

            if (!$snapshot) {
                return null;
            }

            $rows = DB::table('exchange_rates')
                ->where('exchange_rate_snapshot_id', $snapshot->id)
                ->select('currency', 'rate')
                ->get()
                ->keyBy('currency');

            // Build rates relative to the requested base
            $rates = [];

            if ($base === $dataset) {
                // Rates are already USD-relative
                foreach ($rows as $currency => $row) {
                    if ((float) $row->rate > 0) {
                        $rates[$currency] = round((float) $row->rate, 6);
                    }
                }
                // Include the base itself
                $rates[$dataset] = 1.0;
            } else {
                // Cross-rate: convert everything through the requested base
                $baseRow = $rows->get($base);
                if (!$baseRow || (float) $baseRow->rate <= 0) {
                    return null;
                }
                $baseRate = (float) $baseRow->rate;

                foreach ($rows as $currency => $row) {
                    if ((float) $row->rate > 0) {
                        $rates[$currency] = round((float) $row->rate / $baseRate, 6);
                    }
                }
                // Add the USD inverse
                $rates[$dataset] = round(1 / $baseRate, 6);
                // The base currency itself is always 1
                $rates[$base] = 1.0;
            }

            ksort($rates);

            return [
                'date'       => substr($snapshot->rate_date, 0, 10),
                'base'       => $base,
                'count'      => count($rates),
                'rates'      => $rates,
            ];
        });

        if ($result === null) {
            return response()->json([
                'success' => false,
                'error'   => 'No data found',
                'message' => "No complete exchange rate snapshot found for date {$date}" .
                    ($base !== 'USD' ? " with base currency {$base}" : '') . '.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $result,
        ]);
    }
}
