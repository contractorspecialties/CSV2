<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMobileWrapperHeaders
{
    /**
     * Intercept incoming requests to optimize the layout rendering loop for embedded native webviews.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Detect native application shell handshakes from user agents or explicit custom headers
        $isMobileWrapper = $request->hasHeader('X-Capacitor-Shell') ||
                           str_contains($request->userAgent(), 'CapacitorMobileShellPlatform');

        if ($isMobileWrapper) {
            // Store the native context state inside volatile session memory cache blocks
            session(['app_shell_active' => true]);
        }

        $response = $next($request);

        // Inject high-performance viewport optimization framing constraints into outbound headers
        if (session('app_shell_active')) {
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            $response->headers->set('X-Mobile-Wrapper-Engine', 'Capacitor-V4');
        }

        return $response;
    }
}
