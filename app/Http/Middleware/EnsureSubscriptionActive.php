<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->isSuperAdmin()) {
            return $next($request);
        }

        $clinic = $user->clinic;

        if (! $clinic) {
            return $next($request);
        }

        $status = $clinic->subscription_status;

        if ($status === 'suspended') {
            return response()->view('errors.subscription_suspended', [
                'clinic' => $clinic,
            ], 403);
        }

        if ($status === 'expired') {
            return response()->view('errors.subscription_expired', [
                'clinic' => $clinic,
            ], 402);
        }

        return $next($request);
    }
}
