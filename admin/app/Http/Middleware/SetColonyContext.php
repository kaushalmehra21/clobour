<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SetColonyContext
{
    /**
     * Handle an incoming request.
     * Automatically set colony context for tenant routes
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Super admin can access all colonies
        if ($user->is_super_admin) {
            // Allow super admin to switch colony context via query parameter
            if ($request->has('colony_id')) {
                $colonyId = $request->get('colony_id');
                $user->current_colony_id = $colonyId;
                $user->save();
            }
            return $next($request);
        }

        // For colony users, ensure they have a current colony set
        if (!$user->current_colony_id) {
            // Get primary colony or first colony
            $userColony = $user->colonies()->wherePivot('is_primary', true)->first()
                ?? $user->colonies()->first();

            if ($userColony) {
                $user->current_colony_id = $userColony->id;
                $user->save();
            } else {
                abort(403, 'You are not assigned to any colony.');
            }
        }

        // Verify user has access to current colony
        $hasAccess = $user->colonies()->where('colonies.id', $user->current_colony_id)->exists();
        
        if (!$hasAccess) {
            abort(403, 'You do not have access to this colony.');
        }

        return $next($request);
    }
}
