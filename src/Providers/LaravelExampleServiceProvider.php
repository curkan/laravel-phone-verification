<?php

namespace Gogain\LaravelPhoneVerification\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class LaravelExampleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([
                __DIR__ . '/../../config/sms-verification.php' => config_path('sms-verification.php'),
            ]);

            $this->configureRoutes();
        }

    }
    /**
     * Configure the routes offered by the application.
     *
     * @return void
     */
    private function configureRoutes(): void
    {
        Route::namespace('Gogain\LaravelExampleServiceProvider\Http\Controllers')
             ->group(function () {
                 $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
             });
    }

}
