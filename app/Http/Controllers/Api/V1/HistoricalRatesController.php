<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ExchangeRateSnapshotService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HistoricalRatesController extends Controller
{
    protected ExchangeRateSnapshotService $snapshotService;

    public function __construct(ExchangeRateSnapshotService $snapshotService)
    {
        $this->snapshotService = $snapshotService;
    }

    /**
     * Get historical exchange rates for a specific date and base currency
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        // Validate request parameters
        $validated = $request->validate([
            'base' => 'required|string|size:3',
            'year' => 'required|integer|min:1900|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'day' => 'required|integer|min:1|max:31',
        ]);

        try {
            $base = strtoupper($validated['base']);
            $year = (int)$validated['year'];
            $month = (int)$validated['month'];
            $day = (int)$validated['day'];

            $rates = $this->snapshotService->getRatesForDate(
                $year,
                $month,
                $day,
                $base
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'base' => $base,
                    'date' => sprintf('%04d-%01d-%01d', $year, $month, $day),
                    'rates' => $rates,
                ],
            ], 200);

        } catch (\Throwable $e) {
            \Log::error('Historical rates request failed', [
                'exception' => $e->getMessage(),
                'class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),

                // Request context (VERY useful)
                'base' => $base ?? null,
                'year' => $year ?? null,
                'month' => $month ?? null,
                'day' => $day ?? null,
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Service unavailable',
                'message' => app()->isLocal()
                    ? $e->getMessage()
                    : 'Unable to fetch historical exchange rates.'
            ], 503);
        }

    }
}
