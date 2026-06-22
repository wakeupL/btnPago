<?php

if (!function_exists('chilePesos')) {
    function chilePesos(int|string $value): string
    {
        return '$' . number_format((int) $value, 0, ',', '.');
    }
}

if (!function_exists('appLogoPath')) {
    /**
     * Ruta de archivo (no URL) del logo de la app: el personalizado si existe,
     * si no el por defecto. Útil para DomPDF, que necesita rutas locales.
     */
    function appLogoPath(): string
    {
        $custom = storage_path('app/public/logo/logo.png');
        return is_file($custom) ? $custom : public_path('imgs/LOGO_3.png');
    }
}

if (!function_exists('pdfImage')) {
    /**
     * Devuelve una imagen local como data URI base64 para incrustarla en un PDF
     * (DomPDF no resuelve URLs ni el symlink /storage). '' si el archivo no existe.
     */
    function pdfImage(?string $path): string
    {
        if (!$path || !is_file($path)) {
            return '';
        }
        $data = @file_get_contents($path);
        if ($data === false) {
            return '';
        }
        $mime = function_exists('mime_content_type') ? (mime_content_type($path) ?: 'image/png') : 'image/png';
        return 'data:' . $mime . ';base64,' . base64_encode($data);
    }
}

if (!function_exists('appLogo')) {
    function appLogo(): string
    {
        $custom = storage_path('app/public/logo/logo.png');
        if (file_exists($custom)) {
            // Servido por una ruta de Laravel (no por el symlink /storage) para que
            // funcione aunque el hosting tenga el public en otra ruta o sin symlink.
            return route('logo') . '?v=' . filemtime($custom);
        }
        return asset('imgs/LOGO_3.png');
    }
}
