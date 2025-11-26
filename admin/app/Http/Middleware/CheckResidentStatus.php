<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckResidentStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        if (method_exists($user, 'status') && $user->status !== 'active') {
            abort(403, 'Resident account not active.');
        }

        return $next($request);
    }
}

