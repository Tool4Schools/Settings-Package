<?php

return [
    /*
      |-----------------------------------------------------------------------
      | MultiTenant Defaults
      |-----------------------------------------------------------------------
      */

    'defaults' =>[
        'driver' =>'database',
    ],

    'repositories' =>[
        'database'=>[
            'driver' => 'eloquent',
            'model' => \Tools4Schools\Settings\Models\Setting::class,
        ],

        'api' => [
            'driver' => 'api',
            'endpoint' => 'https://api.tools4schools.ie/v1/settings',
        ],

        'cashe' =>[
            'driver' =>'cashe'
        ]
    ],
];