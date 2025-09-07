<?php

declare(strict_types=1);

namespace Tools4Schools\Settings;

use Illuminate\Foundation\Application;
use Illuminate\Support\Manager;
use InvalidArgumentException;
use Tools4Schools\Settings\Contracts\SettingsDriver;

class SettingsManager extends Manager
{

    public function getDefaultDriver()
    {
        return config('settings.driver');
    }

    public function driver($name = null):SettingsDriver
    {
        return parent::driver($name);
    }

    protected function createDatabaseDriver(): SettingsDriver
    {
        return new DatabaseSettingsDriver();
    }

    public function createApiDriver(): SettingsDriver
    {
        return new ApiSettingsDriver();
    }
}
