<?php

declare(strict_types=1);

namespace Tools4Schools\Settings\Facades;

use Illuminate\Support\Facades\Facade;

class Settings extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'settings';
    }
}
