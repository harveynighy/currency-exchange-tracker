<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * Return statistical summary for a currency pair over a date range.
     * Includes min, max, mean, median, standard deviation and daily change data.
     *
     * GET /api/v1/stats?from=GBP&to=USD&start_date=2026-01-01&end_date=2026-03-01
     */
    public function show(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'from'       => ['required', 'string', 'size:3', 'regex:/^[A-Za-z]{3}$/'],
            'to'         => ['required', 'string', 'size:3', 'regex:/^[A-Za-z]{3}$/'],
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date'   => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ]);

        $from      = strtoupper($validated['from']);
        $to        = strtoupper($validated['to']);
        $startDate = $validated['start_date'];
        $endDate   = $validated['end_date'];
        $base      = 'USD';

        $cacheKey = "api_stats_{$from}_{$to}_{$startDate}_{$endDate}";

        $result = Cache::remember($cacheKey, 3600, function () use ($from, $to, $startDate, $endDate, $base) {
            $rates = $this->fetchRates($from, $to, $startDate, $endDate, $base);

            if (empty($rates)) {
                return null;
            }

            $values = array_column($rates, 'rate');
            sort($values);

            $count  = count($values);
            $min    = round(min($values), 6);
            $max    = round(max($values), 6);
            $mean   = round(array_sum($values) / $count, 6);
            $median = $this->calculateMedian($values);
            $stddev = $this->calculateStddev($values, $mean);

            // Daily changes
            $allRates   = array_column($rates, 'rate');
            $dailyChanges = [];
            for ($i = 1; $i < count($allRates); $i++) {
                $prev   = $allRates[$i - 1];
                $curr   = $allRates[$i];
                $change = $prev > 0 ? round((($curr - $prev) / $prev) * 100, 4) : 0;
                $dailyChanges[] = $change;
            }

            return [
                'from'               => $from,
                'to'                 => $to,
                'start_date'         => $startDate,
                'end_date'           => $endDate,
                'data_points'        => $count,
                'min'                => $min,
                'max'                => $max,
                'mean'               => $mean,
                'median'             => $median,
                'std_dev'            => $stddev,
                'range'              => round($max - $min, 6),
                'total_change'       => round(end($allRates) - $allRates[0], 6),
                'total_change_pct'   => $allRates[0] > 0
                    ? round(((end($allRates) - $allRates[0]) / $allRates[0]) * 100, 4)
                    : null,
                'daily_changes'      => [
                    'count'   => count($dailyChanges),
                    'average' => count($dailyChanges) > 0
                        ? round(array_sum($dailyChanges) / count($dailyChanges), 4)
                        : null,
                    'max'     => count($dailyChanges) > 0 ? max($dailyChanges) : null,
                    'min'     => count($dailyChanges) > 0 ? min($dailyChanges) : null,
                ],
            ];
        });

        if ($result === null) {
            return response()->json([
                'success' => false,
                'error'   => 'No data found',
                'message' => "No exchange rate data found for {$from}/{$to} in the specified date range.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $result,
        ]);
    }

    /**
     * Fetch ordered rates array using cross-rate logic (same as HistoricalRatesController).
     */
    private function fetchRates(string $from, string $to, string $startDate, string $endDate, string $base): array
    {
        $snapshots = DB::table('exchange_rate_snapshots as s')
            ->whereDate('s.rate_date', '>=', $startDate)
            ->whereDate('s.rate_date', '<=', $endDate)
            ->where('s.base', $base)
            ->where('s.is_complete', true)
            ->orderBy('s.rate_date', 'asc');

        if ($from === $to) {
            return $snapshots->select('s.rate_date')->get()
                ->map(fn ($r) => ['date' => substr($r->rate_date, 0, 10), 'rate' => 1.0])
                ->values()->toArray();
        }

        if ($from === $base) {
            return $snapshots
                ->join('exchange_rates as to_r', fn ($j) => $j->on('to_r.exchange_rate_snapshot_id', '=', 's.id')->where('to_r.currency', $to))
                ->select('s.rate_date', 'to_r.rate as to_rate')
                ->get()
                ->map(fn ($r) => ['date' => substr($r->rate_date, 0, 10), 'rate' => round((float) $r->to_rate, 6)])
                ->values()->toArray();
        }

        if ($to === $base) {
            return $snapshots
                ->join('exchange_rates as from_r', fn ($j) => $j->on('from_r.exchange_rate_snapshot_id', '=', 's.id')->where('from_r.currency', $from))
                ->select('s.rate_date', 'from_r.rate as from_rate')
                ->get()
                ->filter(fn ($r) => (float) $r->from_rate > 0)
                ->map(fn ($r) => ['date' => substr($r->rate_date, 0, 10), 'rate' => round(1 / (float) $r->from_rate, 6)])
                ->values()->toArray();
        }

        return $snapshots
            ->join('exchange_rates as from_r', fn ($j) => $j->on('from_r.exchange_rate_snapshot_id', '=', 's.id')->where('from_r.currency', $from))
            ->join('exchange_rates as to_r', fn ($j) => $j->on('to_r.exchange_rate_snapshot_id', '=', 's.id')->where('to_r.currency', $to))
            ->select('s.rate_date', 'from_r.rate as from_rate', 'to_r.rate as to_rate')
            ->get()
            ->filter(fn ($r) => (float) $r->from_rate > 0)
            ->map(fn ($r) => [
                'date' => substr($r->rate_date, 0, 10),
                'rate' => round((float) $r->to_rate / (float) $r->from_rate, 6),
            ])
            ->values()->toArray();
    }

    private function calculateMedian(array $sortedValues): float
    {
        $count = count($sortedValues);
        if ($count === 0) return 0;
        $mid = (int) floor($count / 2);
        return $count % 2 === 0
            ? round(($sortedValues[$mid - 1] + $sortedValues[$mid]) / 2, 6)
            : round($sortedValues[$mid], 6);
    }

    private function calculateStddev(array $values, float $mean): float
    {
        $count = count($values);
        if ($count < 2) return 0.0;
        $variance = array_sum(array_map(fn ($v) => ($v - $mean) ** 2, $values)) / ($count - 1);
        return round(sqrt($variance), 6);
    }
}
