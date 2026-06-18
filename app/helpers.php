<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('route_if_exists')) {
    function route_if_exists(string $name, mixed $parameters = null, bool $absolute = true): string
    {
        if (!Route::has($name)) {
            return '#';
        }
        try {
            return route($name, $parameters, $absolute);
        } catch (\Illuminate\Routing\Exceptions\UrlGenerationException) {
            return '#';
        }
    }
}
