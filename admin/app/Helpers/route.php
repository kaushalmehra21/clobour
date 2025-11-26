<?php

if(! function_exists('prefixActive')){
	function prefixActive($prefixName)
	{ 
		return	request()->route()->getPrefix() == $prefixName ? 'active' : '';
	}
}

if(! function_exists('prefixBlock')){
	function prefixBlock($prefixName)
	{ 
		return	request()->route()->getPrefix() == $prefixName ? 'block' : 'none';
	}
}

if(! function_exists('routeActive')){
	function routeActive($routeName)
	{ 
		return	request()->routeIs($routeName) ? 'active' : '';
	}
}

if (! function_exists('panel_route')) {
    /**
     * Resolve route name based on current panel (colony vs admin vs super-admin)
     */
    function panel_route(string $name, ...$parameters)
    {
        $route = request()?->route();
        $prefix = 'admin';

        if ($route) {
            $routeName = $route->getName();
            $routePrefix = $route->getPrefix();
            
            // Check route name pattern first
            if ($routeName && (str_starts_with($routeName, 'colony.') || str_contains($routeName, 'colony.'))) {
                $prefix = 'colony';
            } elseif ($routeName && (str_starts_with($routeName, 'super-admin.') || str_contains($routeName, 'super-admin.'))) {
                $prefix = 'super-admin';
            }
            // Fallback to prefix check
            elseif ($routePrefix) {
                if (str_contains($routePrefix, 'colony')) {
                    $prefix = 'colony';
                } elseif (str_contains($routePrefix, 'super-admin')) {
                    $prefix = 'super-admin';
                }
            }
        }

        return route($prefix . '.' . $name, ...$parameters);
    }
}