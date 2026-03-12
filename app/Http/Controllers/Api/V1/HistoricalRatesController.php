<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HistoricalRatesController extends Controller
{
    /**
     * Get historical exchange rates for a currency pair over a date range.
     */
    public function show(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'from' => ['required', 'string', 'size:3', 'regex:/^[A-Za-z]{3}$/'],
            'to' => ['required', 'string', 'size:3', 'regex:/^[A-Za-z]{3}$/'],
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ]);

        $from = strtoupper($validated['from']);
        $to = strtoupper($validated['to']);
        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];
        $base = 'USD';

        $cacheKey = "api_history_{$from}_{$to}_{$startDate}_{$endDate}";

        $rates = Cache::remember($cacheKey, 3600, function () use ($from, $to, $startDate, $endDate, $base) {
            $snapshots = DB::table('exchange_rate_snapshots as s')
                ->whereDate('s.rate_date', '>=', $startDate)
                ->whereDate('s.rate_date', '<=', $endDate)
                ->where('s.base', $base)
                ->where('s.is_complete', true)
                ->orderBy('s.rate_date', 'asc');

            if ($from === $to) {
                return $snapshots
                    ->select('s.rate_date')
                    ->get()
                    ->map(fn ($row) => [
                        'date' => substr($row->rate_date, 0, 10),
                        'rate' => 1.0,
                    ])
                    ->values()
                    ->toArray();
            }

            if ($from === $base) {
                return $snapshots
                    ->join('exchange_rates as to_r', function ($join) use ($to) {
                        $join->on('to_r.exchange_rate_snapshot_id', '=', 's.id')
                            ->where('to_r.currency', $to);
                    })
                    ->select('s.rate_date', 'to_r.rate as to_rate')
                    ->get()
                    ->map(fn ($row) => [
                        'date' => substr($row->rate_date, 0, 10),
                        'rate' => round((float) $row->to_rate, 6),
                    ])
                    ->values()
                    ->toArray();
            }

            if ($to === $base) {
                return $snapshots
                    ->join('exchange_rates as from_r', function ($join) use ($from) {
                        $join->on('from_r.exchange_rate_snapshot_id', '=', 's.id')
                            ->where('from_r.currency', $from);
                    })
                    ->select('s.rate_date', 'from_r.rate as from_rate')
                    ->get()
                    ->filter(fn ($row) => (float) $row->from_rate > 0)
                    ->map(fn ($row) => [
                        'date' => substr($row->rate_date, 0, 10),
                        'rate' => round(1 / (float) $row->from_rate, 6),
                    ])
                    ->values()
                    ->toArray();
            }

            return $snapshots
                ->join('exchange_rates as from_r', function ($join) use ($from) {
                    $join->on('from_r.exchange_rate_snapshot_id', '=', 's.id')
                        ->where('from_r.currency', $from);
                })
                ->join('exchange_rates as to_r', function ($join) use ($to) {
                    $join->on('to_r.exchange_rate_snapshot_id', '=', 's.id')
                        ->where('to_r.currency', $to);
                })
                ->select('s.rate_date', 'from_r.rate as from_rate', 'to_r.rate as to_rate')
                ->get()
                ->filter(fn ($row) => (float) $row->from_rate > 0)
                ->map(fn ($row) => [
                    'date' => substr($row->rate_date, 0, 10),
                    'rate' => round((float) $row->to_rate / (float) $row->from_rate, 6),
                ])
                ->values()
                ->toArray();
        });

        return response()->json([
            'success' => true,
            'data' => [
                'from' => $from,
                'to' => $to,
                'base_dataset' => $base,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'count' => count($rates),
                'rates' => $rates,
            ],
        ]);
    }
}
