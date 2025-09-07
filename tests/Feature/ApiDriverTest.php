<?php
use Illuminate\Support\Facades\Http;
use Tools4Schools\Settings\Facades\Settings;

beforeEach(function () {
    config()->set('settings.driver', 'api');
    config()->set('settings.api.base_uri', 'https://settings.example');
    config()->set('settings.cache.use_tags', false);
});

it('reads via API and hydrates namespace cache', function () {
    Http::fake([
        'https://settings.example/settings*' => Http::response([
            'mail' => ['from' => 'noreply@example.com', 'driver'=>'smtp'],
        ], 200),
    ]);

    $all = Settings::driver()->all(null);
    expect($all['mail']['from'])->toBe('noreply@example.com');

    // get should hit cache now
    [$v] = Settings::driver()->get(null, 'mail.from');
    expect($v)->toBe('noreply@example.com');
});

it('sets via API and refreshes cache', function () {
    Http::fake([
        'https://settings.example/settings'      => Http::response(['value'=>'admin@school.edu'], 200),
        'https://settings.example/settings*'     => Http::response([
            'mail' => ['from' => 'admin@school.edu'],
        ], 200),
        'https://settings.example/settings/show*'=> Http::response([
            'found'=>true,'value'=>'admin@school.edu','namespace'=>'mail','bucket'=>['from'=>'admin@school.edu']
        ],200),
    ]);

    $v = Settings::driver()->set(null, 'mail.from', 'admin@school.edu');
    expect($v)->toBe('admin@school.edu');

    [$v2] = Settings::driver()->get(null, 'mail.from');
    expect($v2)->toBe('admin@school.edu');
});
