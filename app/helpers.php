<?php

if (!function_exists('chilePesos')) {
    function chilePesos(int|string $value): string
    {
        return '$' . number_format((int) $value, 0, ',', '.');
    }
}
