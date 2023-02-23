<?php

namespace Gogain\LaravelPhoneVerification\Providers;

use Illuminate\Support\ServiceProvider;
use Gogain\LaravelPhoneVerification\Console\Commands\ExampleCommand;
use Gogain\LaravelPhoneVerification\Console\Commands\MigrateCommand;
use Illuminate\Support\Facades\Route;

class SmsVerificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/sms-verification.php' => config_path('sms-verification.php'),
            ]);

            $this->commands([
                ExampleCommand::class,
            ]);
        }

        $this->configureRoutes();
        $this->configureCommands();
        $this->registerMigrations();
    }
    /**
     * Configure the routes offered by the application.
     *
     * @return void
     */
    private function configureRoutes(): void
    {
        Route::namespace('Gogain\LaravelExampleServiceProvider\Http\Controllers')
             ->prefix(config('sms-verification.path'))
             ->group(function () {
                 $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
             });
    }

    /**
     * Register the package's migrations.
     *
     * @return void
     */
    private function registerMigrations(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        }
    }

    /**
     * Configure the commands offered by the application.
     *
     * @return void
     */
    private function configureCommands(): void
    {
        $this->commands([
            MigrateCommand::class,
        ]);
    }



}
