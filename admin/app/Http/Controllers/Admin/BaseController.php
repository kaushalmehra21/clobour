<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

abstract class BaseController extends Controller
{
    /**
     * Get current user's colony ID
     */
    protected function getColonyId()
    {
        $user = Auth::user();
        return $user->is_super_admin ? null : $user->current_colony_id;
    }

    /**
     * Get route prefix based on user type
     */
    protected function getRoutePrefix()
    {
        $user = Auth::user();
        return $user && $user->is_super_admin ? 'super-admin' : 'colony';
    }

    /**
     * Apply colony filter to query
     */
    protected function applyColonyFilter($query)
    {
        $user = Auth::user();
        
        if (!$user->is_super_admin && $user->current_colony_id) {
            $query->where('colony_id', $user->current_colony_id);
        }
        
        return $query;
    }
}

