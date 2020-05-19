<?php

namespace Tools4Schools\Settings\Providers;

use Illuminate\Support\ServiceProvider;
use Tools4Schools\Settings\APIRepository;
use Tools4Schools\Settings\EloquentRepository;
use Tools4Schools\Settings\Facade\Setting;
use Tools4Schools\Settings\SettingsManager;

class SettingsManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/settings.php', 'settings'
        );

        $this->app->singleton(SettingsManager::class,function ($app){
            return new SettingsManager($app);
        });


        Setting::resolved(function ($settingsManager){
            $settingsManager->registerDriver('eloquent',function ($app){
                return new EloquentRepository();
            });
        });

        Setting::resolved(function ($settingsManager){
            $settingsManager->registerDriver('api',function ($app){
                return new APIRepository();
            });
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->publishes([
            __DIR__.'/../../database/migrations/' => database_path('migrations')
        ], 'migrations');
    }

    public function provides()
    {
        return [SettingsManager::class];
    }
}
