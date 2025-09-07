<?php

declare(strict_types=1);

return [
    /*
      |-----------------------------------------------------------------------
      | MultiTenant Defaults
      |-----------------------------------------------------------------------
      */

    'defaults' => [
        'driver' => env('SETTINGS_DRIVER', 'database'), // database|api
    ],

    'repositories' => [
        'database' => [
            'driver' => 'eloquent',
            'model' => [
               'field' => \Tools4Schools\Settings\Models\SettingField::class,
                'value' => \Tools4Schools\Settings\Models\SettingValue::class,
                ],
        ],

        'api' => [
            'driver' => 'api',
            'endpoint' => 'https://api.tools4schools.ie/v1/settings',
        ],

        'cache' => [
            /*
             * You may optionally indicate a specific cache driver to use for permission and
             * role caching using any of the `store` drivers listed in the cache.php config
             * file. Using 'default' here means to use the `default` set in cache.php.
             */

            'store' => env('SETTINGS_CACHE_STORE', 'default'),

            /*
             * By default all permissions are cached for 24 hours to speed up performance.
             * When permissions or roles are updated the cache is flushed automatically.
             */

            'expiration_time' => \DateInterval::createFromDateString('24 hours'),

            // The cache key used to store all permissions.

            'prefix'   => env('SETTINGS_CACHE_PREFIX', 'settings'),

            'use_tags' => env('SETTINGS_CACHE_USE_TAGS', true),

            'api' => [
                'base_uri' => env('SETTINGS_API_BASE_URI'),
                'timeout'  => env('SETTINGS_API_TIMEOUT', 5.0),
                'auth'     => [
                    'type'  => env('SETTINGS_API_AUTH', 'bearer'), // bearer|none
                    'token' => env('SETTINGS_API_TOKEN'),
                ],
            ],
        ],
    ],
];
