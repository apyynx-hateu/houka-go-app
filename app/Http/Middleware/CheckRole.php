<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Usage: middleware('role:ADMIN') or middleware('role:TUTOR')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $isAdmin = Auth::guard('petinggi')->check();
        $isTutor = Auth::guard('tutor')->check();

        if (!$isAdmin && !$isTutor) {
            return redirect()->route('login');
        }

        if (empty($roles)) {
            return $next($request);
        }

        // Normalize roles to uppercase
        $normalizedRoles = array_map('strtoupper', $roles);

        $allowed = false;
        if ($isAdmin && (in_array('ADMIN', $normalizedRoles) || in_array('PETINGGI', $normalizedRoles))) {
            Auth::shouldUse('petinggi');
            $allowed = true;
        }

        if ($isTutor && in_array('TUTOR', $normalizedRoles)) {
            Auth::shouldUse('tutor');
            $allowed = true;
        }

        if (!$allowed) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
