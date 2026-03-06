<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureClinicAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Super admin has unrestricted access
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Clinic users must belong to a clinic and the clinic must be active
        if (! $user->clinic_id) {
            abort(403, 'Your account is not associated with any clinic.');
        }

        if (! $user->is_active) {
            Auth::logout();
            return redirect()->route('login')
                             ->with('error', 'Your account has been deactivated. Please contact your clinic admin.');
        }

        return $next($request);
    }
}
