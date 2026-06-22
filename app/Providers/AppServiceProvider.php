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
        $commerceCode = config('transbank.commerce_code');
        $apiKey       = config('transbank.api_key');

        if ($env === 'production' && $commerceCode && $apiKey) {
            WebpayPlus::configureForProduction($commerceCode, $apiKey);
        } elseif ($commerceCode && $apiKey) {
            WebpayPlus::configureForIntegration($commerceCode, $apiKey);
        } else {
            // Sin credenciales → credenciales de integración por defecto de Transbank
            WebpayPlus::configureForTesting();
        }
    }
}
