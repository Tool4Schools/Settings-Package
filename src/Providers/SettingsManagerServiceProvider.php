<?php

namespace Tools4Schools\Settings\Providers;

use Illuminate\Cache\CacheManager;
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

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(SettingsManager $settingsManager, CacheManager $cacheManager)
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->publishes([
            __DIR__.'/../../database/migrations/' => database_path('migrations'),
        ], 't4s-settings');

        $this->app->singleton(SettingsManager::class, function ($app) use ($settingsManager) {
            return $settingsManager;
        });

        Setting::resolved(function ($settingsManager) use ($cacheManager) {
            $settingsManager->registerDriver('eloquent', function ($app) use ($cacheManager) {
                return new EloquentRepository($cacheManager);
            });
        });

        Setting::resolved(function ($settingsManager) {
            $settingsManager->registerDriver('api', function ($app) {
                return new APIRepository();
            });
        });

    }

    public function provides()
    {
        return [SettingsManager::class];
    }
}
