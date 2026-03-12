<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VolatilityController extends Controller
{
    /**
     * Return volatility analysis for a currency pair over a date range.
     * Includes standard deviation, annualised volatility, and daily change breakdown.
     *
     * GET /api/v1/volatility?from=GBP&to=USD&start_date=2026-01-01&end_date=2026-03-01
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

        $cacheKey = "api_volatility_{$from}_{$to}_{$startDate}_{$endDate}";

        $result = Cache::remember($cacheKey, 3600, function () use ($from, $to, $startDate, $endDate, $base) {
            $rates = $this->fetchRates($from, $to, $startDate, $endDate, $base);

            if (count($rates) < 2) {
                return null;
            }

            $values = array_column($rates, 'rate');

            // Daily log returns for volatility calculation
            $logReturns = [];
            for ($i = 1; $i < count($values); $i++) {
                if ($values[$i - 1] > 0 && $values[$i] > 0) {
                    $logReturns[] = log($values[$i] / $values[$i - 1]);
                }
            }

            if (empty($logReturns)) {
                return null;
            }

            // Daily change percentages (for display)
            $dailyChanges = [];
            for ($i = 1; $i < count($values); $i++) {
                if ($values[$i - 1] > 0) {
                    $dailyChanges[] = round((($values[$i] - $values[$i - 1]) / $values[$i - 1]) * 100, 4);
                }
            }

            $count        = count($logReturns);
            $meanReturn   = array_sum($logReturns) / $count;
            $variance     = array_sum(array_map(fn ($r) => ($r - $meanReturn) ** 2, $logReturns)) / ($count - 1);
            $dailyStddev  = sqrt($variance);

            // Annualise assuming ~252 trading days
            $annualisedVol = $dailyStddev * sqrt(252);

            // 30-day rolling volatility (if enough data)
            $rollingVol = null;
            if (count($logReturns) >= 30) {
                $window      = array_slice($logReturns, -30);
                $winMean     = array_sum($window) / 30;
                $winVariance = array_sum(array_map(fn ($r) => ($r - $winMean) ** 2, $window)) / 29;
                $rollingVol  = round(sqrt($winVariance) * sqrt(252) * 100, 4);
            }

            $maxUp   = max($dailyChanges);
            $maxDown = min($dailyChanges);
            $upDays  = count(array_filter($dailyChanges, fn ($c) => $c > 0));
            $downDays = count(array_filter($dailyChanges, fn ($c) => $c < 0));

            return [
                'from'       => $from,
                'to'         => $to,
                'start_date' => $startDate,
                'end_date'   => $endDate,
                'data_points' => count($values),
                'daily_volatility' => [
                    'std_dev'           => round($dailyStddev * 100, 6) . '%',
                    'std_dev_raw'       => round($dailyStddev, 8),
                ],
                'annualised_volatility' => round($annualisedVol * 100, 4) . '%',
                'rolling_30d_annualised_volatility' => $rollingVol !== null ? $rollingVol . '%' : null,
                'daily_changes' => [
                    'count'      => count($dailyChanges),
                    'average'    => round(array_sum($dailyChanges) / count($dailyChanges), 4) . '%',
                    'max_gain'   => $maxUp . '%',
                    'max_loss'   => $maxDown . '%',
                    'up_days'    => $upDays,
                    'down_days'  => $downDays,
                    'flat_days'  => count($dailyChanges) - $upDays - $downDays,
                ],
                'period_performance' => [
                    'open'       => round($values[0], 6),
                    'close'      => round(end($values), 6),
                    'change'     => round(end($values) - $values[0], 6),
                    'change_pct' => $values[0] > 0
                        ? round(((end($values) - $values[0]) / $values[0]) * 100, 4) . '%'
                        : null,
                ],
            ];
        });

        if ($result === null) {
            return response()->json([
                'success' => false,
                'error'   => 'Insufficient data',
                'message' => "At least 2 data points are required for volatility analysis. No data found for {$from}/{$to} in the specified range.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $result,
        ]);
    }

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
}
