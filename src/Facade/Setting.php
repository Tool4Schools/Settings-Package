<?php

namespace Tools4Schools\Settings\Facade;

use Illuminate\Support\Facades\Facade;
use Tools4Schools\Settings\SettingsManager;

class Setting extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SettingsManager::class;
    }
}
