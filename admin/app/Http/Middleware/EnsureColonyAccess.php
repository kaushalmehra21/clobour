<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureColonyAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Authentication required.');
        }

        if ($user->is_super_admin) {
            return $next($request);
        }

        if (! $user->colony_id) {
            abort(403, 'Colony context missing.');
        }

        $request->merge(['colony_id' => $user->colony_id]);
        return $next($request);
    }
}

