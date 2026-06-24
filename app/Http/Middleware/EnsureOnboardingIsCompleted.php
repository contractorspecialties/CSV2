<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingIsCompleted
{
    /**
     * Confirm profile configuration details are populated before exposing core modules.
     * Incorporates a global master key circuit breaker for platform administration layers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 👑 ADMINISTRATIVE MASTER KEY CIRCUIT BREAKER
        // If the authenticated profile holds global system authority rights,
        // waive all operational tenant constraints and advance the pipeline seamlessly.
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        $user = auth()->user();

        // If the authenticated contractor has not finished their profile configuration steps
        if ($user && !$user->onboarding_completed_at) {
            // Prevent infinite cascading execution loops if they are hitting the configuration setup path
            if (!$request->routeIs('onboarding.*')) {
                return redirect()->route('onboarding.view');
            }
        }

        return $next($request);
    }
}
