<?php

if (!function_exists('admin_route')) {
    /**
     * Get the admin route prefix based on user type
     */
    function admin_route($name, $parameters = [], $absolute = true)
    {
        $user = auth()->user();
        
        if ($user && $user->is_super_admin) {
            $prefix = 'super-admin';
        } else {
            $prefix = 'colony';
        }
        
        return route("{$prefix}.{$name}", $parameters, $absolute);
    }
}

if (!function_exists('admin_prefix')) {
    /**
     * Get the admin route prefix
     */
    function admin_prefix()
    {
        $user = auth()->user();
        return $user && $user->is_super_admin ? 'super-admin' : 'colony';
    }
}

