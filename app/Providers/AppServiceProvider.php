<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Transbank\Webpay\WebpayPlus;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureTransbank();
    }

    private function configureTransbank(): void
    {
        $env          = config('transbank.environment', 'integration');
        $commerceCode = trim((string) config('transbank.commerce_code'));
        $apiKey       = trim((string) config('transbank.api_key'));

        if ($env === 'production') {
            // En producción exigimos credenciales reales. Nunca caer en silencio a las de
            // prueba: eso crea el token en integración y termina en página en blanco al pagar.
            if ($commerceCode === '' || $apiKey === '') {
                throw new \RuntimeException(
                    'TRANSBANK_ENVIRONMENT=production requiere TRANSBANK_COMMERCE_CODE y ' .
                    'TRANSBANK_API_KEY. Revisa el .env y, si cacheaste la configuración, ' .
                    'ejecuta "php artisan config:clear".'
                );
            }

            WebpayPlus::configureForProduction($commerceCode, $apiKey);
            return;
        }

        if ($commerceCode !== '' && $apiKey !== '') {
            WebpayPlus::configureForIntegration($commerceCode, $apiKey);
            return;
        }

        // Sin credenciales → credenciales de integración por defecto de Transbank
        WebpayPlus::configureForTesting();
    }
}
