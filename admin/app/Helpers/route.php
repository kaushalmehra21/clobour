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
            if ($route->named('colony.*')) {
                $prefix = 'colony';
            } elseif ($route->named('super-admin.*')) {
                $prefix = 'super-admin';
            }
        }

        return route($prefix . '.' . $name, ...$parameters);
    }
}