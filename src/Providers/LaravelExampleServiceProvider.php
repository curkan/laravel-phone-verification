<?php

namespace Gogain\LaravelPhoneVerification\Providers;

use Illuminate\Support\ServiceProvider;
use Gogain\LaravelPhoneVerification\Console\Commands\ExampleCommand;

class LaravelExampleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([
                __DIR__ . '/../../config/sms-verification.php' => config_path('sms-verification.php'),
            ]);

            $this->commands([
                ExampleCommand::class,
            ]);
        }

        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
    }
}
