<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class AppSettings
{
    private const FILE = 'settings.json';

    public static function all(): array
    {
        if (! Storage::disk('local')->exists(self::FILE)) {
            return [];
        }

        return json_decode(Storage::disk('local')->get(self::FILE), true) ?? [];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::all()[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        $settings = self::all();
        $settings[$key] = $value;
        Storage::disk('local')->put(self::FILE, json_encode($settings, JSON_PRETTY_PRINT));
    }
}
