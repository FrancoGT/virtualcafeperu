<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // En producción el sitio solo se sirve por HTTPS (detrás del proxy de
        // Render); en local se respeta el esquema de la petición (HTTP).
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
