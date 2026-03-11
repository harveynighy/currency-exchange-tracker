<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRateSnapshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HistoricalRatesController extends Controller
{
    public function index()
    {
        $currencies = config('currencies.supported');
        
        return view('charts.index', [
            'currencies' => $currencies,
            'defaultFrom' => 'GBP',
            'defaultTo' => 'USD',
        ]);
    }

    public function getData(Request $request)
    {
        $request->validate([
            'from' => 'required|string|size:3',
            'to' => 'required|string|size:3',
            'period' => 'in:7d,30d,90d,1y,5y,10y,all',
        ]);

        $from = strtoupper($request->from);
        $to = strtoupper($request->to);
        $period = $request->period ?? '30d';

        if ($from === $to) {
            return response()->json(['success' => true, 'data' => [], 'from' => $from, 'to' => $to, 'period' => $period]);
        }

        // Calculate date range
        $endDate = now()->format('Y-m-d');
        $startDate = match($period) {
            '7d'  => now()->subDays(7)->format('Y-m-d'),
            '30d' => now()->subDays(30)->format('Y-m-d'),
            '90d' => now()->subDays(90)->format('Y-m-d'),
            '1y'  => now()->subYear()->format('Y-m-d'),
            '5y'  => now()->subYears(5)->format('Y-m-d'),
            '10y' => now()->subYears(10)->format('Y-m-d'),
            'all' => '1990-01-01',
            default => now()->subDays(30)->format('Y-m-d'),
        };

        $cacheKey = "chart_data_{$from}_{$to}_{$period}";

        $data = Cache::remember($cacheKey, 3600, function () use ($from, $to, $startDate, $endDate) {
            // GBP is the base currency — its rate against itself is always 1.
            // Use a single JOIN query to fetch both rates in one round-trip.
            $db = \Illuminate\Support\Facades\DB::table('exchange_rate_snapshots as s')
                ->whereBetween('s.rate_date', [$startDate, $endDate])
                ->where('s.base', 'GBP')
                ->where('s.is_complete', true)
                ->orderBy('s.rate_date', 'asc');

            if ($from === 'GBP' && $to === 'GBP') {
                return [];
            } elseif ($from === 'GBP') {
                // Rate is simply the GBP→TO rate
                $rows = $db->join('exchange_rates as to_r', function ($join) use ($to) {
                        $join->on('to_r.exchange_rate_snapshot_id', '=', 's.id')
                             ->where('to_r.currency', $to);
                    })
                    ->select('s.rate_date', 'to_r.rate as to_rate')
                    ->get();

                return $rows->map(fn($row) => [
                    'date' => substr($row->rate_date, 0, 10),
                    'rate' => round((float) $row->to_rate, 6),
                ])->values()->toArray();

            } elseif ($to === 'GBP') {
                // Rate is 1 / (GBP→FROM rate)
                $rows = $db->join('exchange_rates as from_r', function ($join) use ($from) {
                        $join->on('from_r.exchange_rate_snapshot_id', '=', 's.id')
                             ->where('from_r.currency', $from);
                    })
                    ->select('s.rate_date', 'from_r.rate as from_rate')
                    ->get();

                return $rows->filter(fn($row) => (float) $row->from_rate > 0)
                    ->map(fn($row) => [
                        'date' => substr($row->rate_date, 0, 10),
                        'rate' => round(1 / (float) $row->from_rate, 6),
                    ])->values()->toArray();

            } else {
                // Cross rate: (GBP→TO) / (GBP→FROM)
                $rows = $db->join('exchange_rates as from_r', function ($join) use ($from) {
                        $join->on('from_r.exchange_rate_snapshot_id', '=', 's.id')
                             ->where('from_r.currency', $from);
                    })
                    ->join('exchange_rates as to_r', function ($join) use ($to) {
                        $join->on('to_r.exchange_rate_snapshot_id', '=', 's.id')
                             ->where('to_r.currency', $to);
                    })
                    ->select('s.rate_date', 'from_r.rate as from_rate', 'to_r.rate as to_rate')
                    ->get();

                return $rows->filter(fn($row) => (float) $row->from_rate > 0)
                    ->map(fn($row) => [
                        'date' => substr($row->rate_date, 0, 10),
                        'rate' => round((float) $row->to_rate / (float) $row->from_rate, 6),
                    ])->values()->toArray();
            }
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'from' => $from,
            'to' => $to,
            'period' => $period,
        ]);
    }
}
