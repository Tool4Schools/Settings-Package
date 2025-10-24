<?php

declare(strict_types=1);

namespace Tools4Schools\Settings;

use Illuminate\Support\Manager;
use Tools4Schools\Settings\Contracts\SettingsDriver;
use Tools4Schools\Settings\Drivers\ApiDriver;
use Tools4Schools\Settings\Drivers\DatabaseDriver;

class SettingsManager extends Manager
{
    public function getDefaultDriver()
    {
        return config('settings.driver');
    }

    public function driver($name = null): SettingsDriver
    {
        return parent::driver($name);
    }

    protected function createDatabaseDriver(): SettingsDriver
    {
        return new DatabaseDriver();
    }

    public function createApiDriver(): SettingsDriver
    {
        return new ApiDriver();
    }
}
