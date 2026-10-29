<?php

if (! function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        $base = rtrim(config('app.url') ?: url('/'), '/') . '/';
        if ($path === '') {
            return $base;
        }

        return $base . ltrim($path, '/');
    }
}

if (! function_exists('site_url')) {
    function site_url(string $path = ''): string
    {
        return base_url($path);
    }
}
