<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CompareRatesController extends Controller
{
    /**
     * Compare the exchange rate between two specific dates.
     * Useful for year-over-year or any custom period comparison.
     *
     * GET /api/v1/compare?from=GBP&to=USD&date=2026-03-01&compare_date=2025-03-01
     */
    public function show(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'from'         => ['required', 'string', 'size:3', 'regex:/^[A-Za-z]{3}$/'],
            'to'           => ['required', 'string', 'size:3', 'regex:/^[A-Za-z]{3}$/'],
            'date'         => ['required', 'date_format:Y-m-d'],
            'compare_date' => ['required', 'date_format:Y-m-d', 'different:date'],
        ]);

        $from        = strtoupper($validated['from']);
        $to          = strtoupper($validated['to']);
        $date        = $validated['date'];
        $compareDate = $validated['compare_date'];
        $base        = 'USD';

        $cacheKey = "api_compare_{$from}_{$to}_{$date}_{$compareDate}";

        $result = Cache::remember($cacheKey, 3600, function () use ($from, $to, $date, $compareDate, $base) {
            $rateA = $this->fetchSingleRate($from, $to, $date, $base);
            $rateB = $this->fetchSingleRate($from, $to, $compareDate, $base);

            if ($rateA === null && $rateB === null) {
                return null;
            }

            $change    = ($rateA !== null && $rateB !== null) ? round($rateA - $rateB, 6) : null;
            $changePct = ($rateA !== null && $rateB !== null && $rateB > 0)
                ? round((($rateA - $rateB) / $rateB) * 100, 4)
                : null;

            return [
                'from'         => $from,
                'to'           => $to,
                'date'         => [
                    'date' => $date,
                    'rate' => $rateA,
                ],
                'compare_date' => [
                    'date' => $compareDate,
                    'rate' => $rateB,
                ],
                'change'         => $change,
                'change_pct'     => $changePct,
                'direction'      => $change === null ? null : ($change > 0 ? 'up' : ($change < 0 ? 'down' : 'unchanged')),
            ];
        });

        if ($result === null) {
            return response()->json([
                'success' => false,
                'error'   => 'No data found',
                'message' => "No exchange rate data found for {$from}/{$to} on either {$date} or {$compareDate}.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $result,
        ]);
    }

    /**
     * Fetch a single day's rate, returning null if no snapshot exists.
     */
    private function fetchSingleRate(string $from, string $to, string $date, string $base): ?float
    {
        $snapshot = DB::table('exchange_rate_snapshots as s')
            ->whereDate('s.rate_date', $date)
            ->where('s.base', $base)
            ->where('s.is_complete', true);

        if ($from === $to) {
            return $snapshot->exists() ? 1.0 : null;
        }

        if ($from === $base) {
            $row = $snapshot
                ->join('exchange_rates as to_r', fn ($j) => $j->on('to_r.exchange_rate_snapshot_id', '=', 's.id')->where('to_r.currency', $to))
                ->select('to_r.rate')
                ->first();

            return $row ? round((float) $row->rate, 6) : null;
        }

        if ($to === $base) {
            $row = $snapshot
                ->join('exchange_rates as from_r', fn ($j) => $j->on('from_r.exchange_rate_snapshot_id', '=', 's.id')->where('from_r.currency', $from))
                ->select('from_r.rate')
                ->first();

            if (!$row || (float) $row->rate <= 0) return null;
            return round(1 / (float) $row->rate, 6);
        }

        $row = $snapshot
            ->join('exchange_rates as from_r', fn ($j) => $j->on('from_r.exchange_rate_snapshot_id', '=', 's.id')->where('from_r.currency', $from))
            ->join('exchange_rates as to_r', fn ($j) => $j->on('to_r.exchange_rate_snapshot_id', '=', 's.id')->where('to_r.currency', $to))
            ->select('from_r.rate as from_rate', 'to_r.rate as to_rate')
            ->first();

        if (!$row || (float) $row->from_rate <= 0) return null;
        return round((float) $row->to_rate / (float) $row->from_rate, 6);
    }
}
