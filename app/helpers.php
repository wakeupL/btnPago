<?php

if (!function_exists('chilePesos')) {
    function chilePesos(int|string $value): string
    {
        return '$' . number_format((int) $value, 0, ',', '.');
    }
}

if (!function_exists('appLogo')) {
    function appLogo(): string
    {
        $custom = storage_path('app/public/logo/logo.png');
        if (file_exists($custom)) {
            return asset('storage/logo/logo.png') . '?v=' . filemtime($custom);
        }
        return asset('imgs/LOGO_3.png');
    }
}
