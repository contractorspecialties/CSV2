<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingIsCompleted
{
    /**
     * Confirm profile configuration details are populated before exposing core modules.
     */
    public function handle(Request $request, Closure $next): Response
    {
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
