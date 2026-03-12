<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class NearestRateController extends Controller
{
    /**
     * Return the nearest available exchange rate to the requested date.
     * Searches up to 30 days before and after.
     *
     * GET /api/v1/nearest?from=GBP&to=USD&date=2025-12-25
     */
    public function show(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'from' => ['required', 'string', 'size:3', 'regex:/^[A-Za-z]{3}$/'],
            'to'   => ['required', 'string', 'size:3', 'regex:/^[A-Za-z]{3}$/'],
            'date' => ['required', 'date_format:Y-m-d'],
        ]);

        $from  = strtoupper($validated['from']);
        $to    = strtoupper($validated['to']);
        $date  = $validated['date'];
        $base  = 'USD';

        $cacheKey = "api_nearest_{$from}_{$to}_{$date}";

        $result = Cache::remember($cacheKey, 3600, function () use ($from, $to, $date, $base) {
            // Search a 30-day window around the requested date, ordered by proximity
            $searchStart = date('Y-m-d', strtotime($date . ' -30 days'));
            $searchEnd   = date('Y-m-d', strtotime($date . ' +30 days'));

            $snapshots = DB::table('exchange_rate_snapshots as s')
                ->whereDate('s.rate_date', '>=', $searchStart)
                ->whereDate('s.rate_date', '<=', $searchEnd)
                ->where('s.base', $base)
                ->where('s.is_complete', true)
                ->orderByRaw("ABS(JULIANDAY(s.rate_date) - JULIANDAY(?))", [$date])
                ->orderBy('s.rate_date', 'desc');

            if ($from === $to) {
                $row = $snapshots->select('s.rate_date')->first();
                if (!$row) return null;
                return [
                    'from'          => $from,
                    'to'            => $to,
                    'requested_date' => $date,
                    'actual_date'   => substr($row->rate_date, 0, 10),
                    'days_apart'    => abs((int) date_diff(
                        date_create($date),
                        date_create(substr($row->rate_date, 0, 10))
                    )->days),
                    'rate'          => 1.0,
                ];
            }

            if ($from === $base) {
                $row = $snapshots
                    ->join('exchange_rates as to_r', fn ($j) => $j->on('to_r.exchange_rate_snapshot_id', '=', 's.id')->where('to_r.currency', $to))
                    ->select('s.rate_date', 'to_r.rate as rate')
                    ->first();

                if (!$row) return null;
                return $this->buildResult($from, $to, $date, $row->rate_date, (float) $row->rate);
            }

            if ($to === $base) {
                $row = $snapshots
                    ->join('exchange_rates as from_r', fn ($j) => $j->on('from_r.exchange_rate_snapshot_id', '=', 's.id')->where('from_r.currency', $from))
                    ->select('s.rate_date', 'from_r.rate as from_rate')
                    ->first();

                if (!$row || (float) $row->from_rate <= 0) return null;
                return $this->buildResult($from, $to, $date, $row->rate_date, 1 / (float) $row->from_rate);
            }

            $row = $snapshots
                ->join('exchange_rates as from_r', fn ($j) => $j->on('from_r.exchange_rate_snapshot_id', '=', 's.id')->where('from_r.currency', $from))
                ->join('exchange_rates as to_r', fn ($j) => $j->on('to_r.exchange_rate_snapshot_id', '=', 's.id')->where('to_r.currency', $to))
                ->select('s.rate_date', 'from_r.rate as from_rate', 'to_r.rate as to_rate')
                ->first();

            if (!$row || (float) $row->from_rate <= 0) return null;

            return $this->buildResult(
                $from, $to, $date, $row->rate_date,
                (float) $row->to_rate / (float) $row->from_rate
            );
        });

        if ($result === null) {
            return response()->json([
                'success' => false,
                'error'   => 'No data found',
                'message' => "No exchange rate data found for {$from}/{$to} within 30 days of {$date}.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $result,
        ]);
    }

    private function buildResult(string $from, string $to, string $requestedDate, string $actualDate, float $rawRate): array
    {
        $actual = substr($actualDate, 0, 10);
        return [
            'from'           => $from,
            'to'             => $to,
            'requested_date' => $requestedDate,
            'actual_date'    => $actual,
            'days_apart'     => abs((int) date_diff(
                date_create($requestedDate),
                date_create($actual)
            )->days),
            'rate'           => round($rawRate, 6),
        ];
    }
}
