<?php

namespace App\Infra\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap qualquer serviço de aplicação.
     */
    public function boot()
    {
        //
    }

    public function register()
    {
        require __DIR__ .'/../Helpers/PassportHelper.php';
    }

}
