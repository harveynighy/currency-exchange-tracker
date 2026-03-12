<?php

namespace App\Http\Middleware;

use App\Models\ApiMonthlyUsage;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->bearerToken();

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized',
                'message' => 'API key is required. Use: Authorization: Bearer YOUR_API_KEY'
            ], 401);
        }

        $user = User::where('api_key', $apiKey)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized',
                'message' => 'Invalid API key'
            ], 401);
        }

        $usageMonth = now()->startOfMonth()->toDateString();
        $limit = $user->monthlyApiRequestLimit();

        ['usage' => $usage, 'quotaExceeded' => $quotaExceeded] = DB::transaction(function () use ($user, $usageMonth, $limit) {
            $usage = ApiMonthlyUsage::query()
                ->where('user_id', $user->id)
                ->whereDate('usage_month', $usageMonth)
                ->lockForUpdate()
                ->first();

            if (!$usage) {
                $usage = ApiMonthlyUsage::create([
                    'user_id' => $user->id,
                    'usage_month' => $usageMonth,
                    'request_count' => 0,
                ]);
            }

            if ($usage->request_count >= $limit) {
                return [
                    'usage' => $usage,
                    'quotaExceeded' => true,
                ];
            }

            $usage->increment('request_count');
            $usage->refresh();

            return [
                'usage' => $usage,
                'quotaExceeded' => false,
            ];
        });

        if ($quotaExceeded) {
            $resetAt = now()->startOfMonth()->addMonth()->toIso8601String();

            return response()->json([
                'success' => false,
                'error' => 'Monthly quota exceeded',
                'message' => "Your {$user->apiPlanName()} plan includes {$limit} API requests per month. Upgrade to continue.",
                'plan' => $user->api_plan,
                'monthly_limit' => $limit,
                'requests_used' => min($usage->request_count, $limit),
                'requests_remaining' => 0,
                'quota_resets_at' => $resetAt,
            ], 429);
        }

        $request->attributes->set('api_usage', $usage);

        // Attach user safely
        $request->attributes->set('authenticated_user', $user);

        return $next($request);
    }

}
