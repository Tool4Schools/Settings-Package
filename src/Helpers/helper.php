<?php

declare(strict_types=1);

use Tools4Schools\Settings\Facades\Settings;

if (! function_exists('settings')) {
    /**
     * Get the settings manager instance or a specific setting value.
     *
     * @param  string|null  $key
     * @param  mixed|null  $default
     * @return \Tools4Schools\Settings\Contracts\SettingsDriver|mixed
     */
    function settings(?string $key = null, mixed $default = null)
    {
        if (is_null($key)) {
            return Settings::all();
        }
        return Settings::get($key, $default);
    }
}