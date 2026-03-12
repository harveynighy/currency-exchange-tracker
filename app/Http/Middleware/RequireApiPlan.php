<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireApiPlan
{
    /**
     * Handle an incoming request, ensuring the authenticated user is on one
     * of the allowed plan tiers. Must run after AuthenticateApiKey.
     *
     * Usage: ->middleware(['api.auth', 'api.plan:pro,business'])
     */
    public function handle(Request $request, Closure $next, string ...$allowedPlans): Response
    {
        /** @var \App\Models\User|null $user */
        $user = $request->attributes->get('authenticated_user');

        if (!$user) {
            return response()->json([
                'success' => false,
                'error'   => 'Unauthorized',
                'message' => 'Authentication required.',
            ], 401);
        }

        $plan = $user->api_plan ?? 'free';

        if (!in_array($plan, $allowedPlans, true)) {
            $required = count($allowedPlans) === 1
                ? ucfirst($allowedPlans[0])
                : implode(' or ', array_map('ucfirst', $allowedPlans));

            return response()->json([
                'success'       => false,
                'error'         => 'Plan upgrade required',
                'message'       => "This endpoint requires a {$required} plan. Your current plan is " . ucfirst($plan) . '.',
                'current_plan'  => $plan,
                'required_plan' => $allowedPlans,
                'upgrade_url'   => url('/profile'),
            ], 403);
        }

        return $next($request);
    }
}
