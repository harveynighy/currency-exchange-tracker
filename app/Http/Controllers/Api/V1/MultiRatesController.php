<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MultiRatesController extends Controller
{
    /**
     * Return rates from one base currency to multiple target currencies for a single date.
     *
     * GET /api/v1/multi?from=GBP&targets[]=USD&targets[]=EUR&targets[]=JPY&date=2026-03-01
     */
    public function show(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'from'      => ['required', 'string', 'size:3', 'regex:/^[A-Za-z]{3}$/'],
            'targets'   => ['required', 'array', 'min:1', 'max:20'],
            'targets.*' => ['required', 'string', 'size:3', 'regex:/^[A-Za-z]{3}$/'],
            'date'      => ['required', 'date_format:Y-m-d'],
        ]);

        $from    = strtoupper($validated['from']);
        $targets = array_values(array_unique(array_map('strtoupper', $validated['targets'])));
        $date    = $validated['date'];
        $base    = 'USD';

        $cacheKey = "api_multi_{$from}_{$date}_" . implode('_', $targets);

        $result = Cache::remember($cacheKey, 3600, function () use ($from, $targets, $date, $base) {
            $snapshot = DB::table('exchange_rate_snapshots')
                ->whereDate('rate_date', $date)
                ->where('base', $base)
                ->where('is_complete', true)
                ->first();

            if (!$snapshot) {
                return null;
            }

            // Pull all needed currency rows in one query
            $needed   = array_unique(array_merge([$from], $targets));
            // Include USD rows even if not in needed (for inversion)
            $rows = DB::table('exchange_rates')
                ->where('exchange_rate_snapshot_id', $snapshot->id)
                ->whereIn('currency', array_filter($needed, fn ($c) => $c !== $base))
                ->get()
                ->keyBy('currency');

            // Determine the USD->from multiplier
            if ($from === $base) {
                $fromMultiplier = 1.0;
            } else {
                $fromRow = $rows->get($from);
                if (!$fromRow || (float) $fromRow->rate <= 0) return null;
                $fromMultiplier = (float) $fromRow->rate;
            }

            $rates = [];
            foreach ($targets as $target) {
                if ($target === $from) {
                    $rates[$target] = 1.0;
                    continue;
                }

                if ($target === $base) {
                    // USD -> from inverse
                    $rates[$target] = $fromMultiplier > 0
                        ? round(1 / $fromMultiplier, 6)
                        : null;
                    continue;
                }

                $targetRow = $rows->get($target);
                if (!$targetRow || (float) $targetRow->rate <= 0) {
                    $rates[$target] = null;
                    continue;
                }

                $rates[$target] = round((float) $targetRow->rate / $fromMultiplier, 6);
            }

            return [
                'date'   => substr($snapshot->rate_date, 0, 10),
                'from'   => $from,
                'count'  => count($rates),
                'rates'  => $rates,
            ];
        });

        if ($result === null) {
            return response()->json([
                'success' => false,
                'error'   => 'No data found',
                'message' => "No complete exchange rate snapshot found for {$date}.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $result,
        ]);
    }
}
