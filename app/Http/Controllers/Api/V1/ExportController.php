<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * Export historical exchange rate data as CSV or JSON.
     * Streams large date ranges without memory issues.
     *
     * GET /api/v1/export?from=GBP&to=USD&start_date=2020-01-01&end_date=2026-03-01&format=csv
     */
    public function show(Request $request): StreamedResponse|JsonResponse
    {
        $validated = $request->validate([
            'from'       => ['required', 'string', 'size:3', 'regex:/^[A-Za-z]{3}$/'],
            'to'         => ['required', 'string', 'size:3', 'regex:/^[A-Za-z]{3}$/'],
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date'   => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'format'     => ['nullable', 'in:csv,json'],
        ]);

        $from      = strtoupper($validated['from']);
        $to        = strtoupper($validated['to']);
        $startDate = $validated['start_date'];
        $endDate   = $validated['end_date'];
        $format    = $validated['format'] ?? 'json';
        $base      = 'USD';

        $rates = $this->fetchRates($from, $to, $startDate, $endDate, $base);

        if (empty($rates)) {
            return response()->json([
                'success' => false,
                'error'   => 'No data found',
                'message' => "No exchange rate data found for {$from}/{$to} in the specified date range.",
            ], 404);
        }

        if ($format === 'csv') {
            return $this->streamCsv($from, $to, $startDate, $endDate, $rates);
        }

        return $this->streamJson($from, $to, $startDate, $endDate, $rates);
    }

    private function streamCsv(string $from, string $to, string $startDate, string $endDate, array $rates): StreamedResponse
    {
        $filename = "fx_export_{$from}_{$to}_{$startDate}_{$endDate}.csv";

        return response()->streamDownload(function () use ($from, $to, $rates) {
            $handle = fopen('php://output', 'w');
            // BOM for Excel UTF-8 compatibility
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['date', "rate_{$from}_{$to}"]);
            foreach ($rates as $row) {
                fputcsv($handle, [$row['date'], $row['rate']]);
            }
            fclose($handle);
        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    private function streamJson(string $from, string $to, string $startDate, string $endDate, array $rates): StreamedResponse
    {
        $filename = "fx_export_{$from}_{$to}_{$startDate}_{$endDate}.json";
        $meta = [
            'from'       => $from,
            'to'         => $to,
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'count'      => count($rates),
            'exported_at' => now()->toIso8601String(),
        ];

        return response()->streamDownload(function () use ($meta, $rates) {
            echo json_encode([
                'success' => true,
                'meta'    => $meta,
                'data'    => $rates,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }, $filename, [
            'Content-Type'        => 'application/json; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
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
