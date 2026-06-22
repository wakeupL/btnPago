<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Entorno de Transbank
    |--------------------------------------------------------------------------
    | "integration" → credenciales de prueba (usa las por defecto si no se especifican)
    | "production"  → credenciales reales del comercio (obligatorio definir las de abajo)
    */
    'environment' => env('TRANSBANK_ENVIRONMENT', 'integration'),

    /*
    |--------------------------------------------------------------------------
    | Credenciales del comercio
    |--------------------------------------------------------------------------
    | En integration: si se dejan vacías se usan las credenciales de prueba
    | que provee Transbank (597055555532 / clave de integración pública).
    | En production: OBLIGATORIO completar con las credenciales reales.
    */
    'commerce_code' => env('TRANSBANK_COMMERCE_CODE'),

    'api_key' => env('TRANSBANK_API_KEY'),

];
