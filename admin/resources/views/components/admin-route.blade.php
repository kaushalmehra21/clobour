@php
    $user = auth()->user();
    $prefix = $user && $user->is_super_admin ? 'super-admin' : 'colony';
    $routeName = "{$prefix}.{$name}";
@endphp
{{ route($routeName, $parameters ?? [], $absolute ?? true) }}

