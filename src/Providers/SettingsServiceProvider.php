<?php

namespace Tools4Schools\Settings\Providers;

use Illuminate\Support\ServiceProvider;
use Tools4Schools\Settings\SettingsManager;

class SettingsServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/settings.php', 'settings'
        );

        $this->app->singleton('settings',fn($app)=>new SettingsManager($app));

    }


    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/settings.php' => config_path('settings.php'),
        ], 't4s-settings-config');

        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->publishes([
            __DIR__.'/../../database/migrations/' => database_path('migrations'),
        ], 't4s-settings');

        if ($this->app->runningInConsole()) {
            $this->commands([MakeSettingFieldCommand::class]);
        }
    }
}
