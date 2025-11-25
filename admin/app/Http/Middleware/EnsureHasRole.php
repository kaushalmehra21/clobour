<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureHasRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Authentication required.');
        }

        // Super admin has all roles
        if ($user->is_super_admin) {
            return $next($request);
        }

        if (! empty($roles)) {
            // Check roles in current colony context
            $colonyId = $user->current_colony_id;
            
            $hasRole = $user->roles()
                ->where(function ($query) use ($colonyId) {
                    $query->where('scope', 'global')
                        ->orWhere(function ($q) use ($colonyId) {
                            $q->where('scope', 'colony')
                              ->where('colony_id', $colonyId);
                        });
                })
                ->where(function ($query) use ($roles) {
                    $query->whereIn('slug', $roles)
                        ->orWhereIn('name', $roles);
                })
                ->exists();

            if (! $hasRole) {
                abort(403, 'You are not authorized to access this resource.');
            }
        }

        return $next($request);
    }
}
