<?php

namespace Tools4Schools\Settings\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Tools4Schools\Settings\Providers\SettingsServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [SettingsServiceProvider::class];
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('settings.driver', 'database');
        $app['config']->set('settings.cache.use_tags', false); // sqlite cache
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->artisan('migrate')->run();
    }
}
