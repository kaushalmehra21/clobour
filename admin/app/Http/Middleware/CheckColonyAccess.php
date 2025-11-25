<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckColonyAccess
{
    /**
     * Handle an incoming request.
     * Verify user has access to the colony resource
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Authentication required.');
        }

        // Super admin has access to all colonies
        if ($user->is_super_admin) {
            return $next($request);
        }

        // Get colony_id from route parameter or request
        $colonyId = $request->route('colony') 
            ?? $request->route('colony_id')
            ?? $request->input('colony_id')
            ?? $user->current_colony_id;

        // Verify user belongs to this colony
        $hasAccess = $user->colonies()->where('colonies.id', $colonyId)->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to this colony.');
        }

        // For model resources, verify colony_id matches
        if ($request->route()->hasParameter('unit') || 
            $request->route()->hasParameter('resident') ||
            $request->route()->hasParameter('bill') ||
            $request->route()->hasParameter('complaint')) {
            
            $model = $request->route()->parameter(
                $request->route()->parameterNames()[0]
            );

            if ($model && method_exists($model, 'getColonyIdAttribute')) {
                if ($model->colony_id !== $user->current_colony_id) {
                    abort(403, 'You do not have access to this resource.');
                }
            }
        }

        return $next($request);
    }
}
