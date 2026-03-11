<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use App\Models\ExchangeRateSnapshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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

        // Calculate date range
        $endDate = now();
        $startDate = match($period) {
            '7d' => now()->subDays(7),
            '30d' => now()->subDays(30),
            '90d' => now()->subDays(90),
            '1y' => now()->subYear(),
            '5y' => now()->subYears(5),
            '10y' => now()->subYears(10),
            'all' => now()->subYears(50), // Max available data
            default => now()->subDays(30),
        };

        $cacheKey = "chart_data_{$from}_{$to}_{$period}";
        
        $data = Cache::remember($cacheKey, 3600, function () use ($from, $to, $startDate, $endDate) {
            // Get snapshots with GBP base (our historical data is GBP-based)
            $snapshots = ExchangeRateSnapshot::where('base', 'GBP')
                ->where('rate_date', '>=', $startDate)
                ->where('rate_date', '<=', $endDate)
                ->where('is_complete', true)
                ->orderBy('rate_date', 'asc')
                ->get();

            $chartData = [];
            
            foreach ($snapshots as $snapshot) {
                // Get rates for both currencies
                $fromRate = ExchangeRate::where('exchange_rate_snapshot_id', $snapshot->id)
                    ->where('currency', $from)
                    ->value('rate');
                    
                $toRate = ExchangeRate::where('exchange_rate_snapshot_id', $snapshot->id)
                    ->where('currency', $to)
                    ->value('rate');

                if ($fromRate && $toRate) {
                    // Calculate cross rate: FROM -> TO
                    // If GBP -> FROM rate is X, and GBP -> TO rate is Y
                    // Then FROM -> TO rate is Y/X
                    $crossRate = $toRate / $fromRate;
                    
                    $chartData[] = [
                        'date' => $snapshot->rate_date->format('Y-m-d'),
                        'rate' => round($crossRate, 6),
                    ];
                }
            }

            return $chartData;
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
