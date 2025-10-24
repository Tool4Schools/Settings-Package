<?php

declare(strict_types=1);
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tools4Schools\Settings\Facades\Settings;
use Tools4Schools\Settings\Models\SettingField;

uses(RefreshDatabase::class);

it('reads defaults and overlays tenant overrides', function (): void {
    SettingField::create([
        'namespace' => 'mail',
        'name' => 'from',
        'type' => 'string',
        'default' => 'noreply@example.com',
    ]);

    // default read
    expect(setting('mail.from'))->toBe('noreply@example.com');

   setting('mail.from', 'noreply@school.com');

    // other tenant still sees default
    $value2 = Settings::driver()->get('mail.from');
    expect($value2)->toBe('noreply@example.com');
});

it('casts types correctly', function (): void {
    SettingField::create([
        'namespace' => 'features', 'name' => 'enabled', 'type' => 'bool', 'default' => '0',
    ]);
    SettingField::create([
        'namespace' => 'limits', 'name' => 'maxUsers', 'type' => 'int', 'default' => '100',
    ]);
    SettingField::create([
        'namespace' => 'meta', 'name' => 'flags', 'type' => 'array', 'default' => json_encode(['a' => 1]),
    ]);

    expect(setting('features.enabled'))->toBeFalse();
    expect(setting('limits.maxUsers'))->toBe(100);
    expect(setting('meta.flags'))->toBe(['a' => 1]);

    $tenant = 't-1';
    Settings::driver()->set($tenant, 'features.enabled', true);
    Settings::driver()->set($tenant, 'limits.maxUsers', 250);
    Settings::driver()->set($tenant, 'meta.flags', ['x' => 2]);

    expect(setting('features.enabled'))->toBeFalse(); // global
    [$v] = Settings::driver()->get($tenant, 'features.enabled');
    expect($v)->toBeTrue();

    [$n] = Settings::driver()->get($tenant, 'limits.maxUsers');
    expect($n)->toBe(250);

    [$a] = Settings::driver()->get($tenant, 'meta.flags');
    expect($a)->toBe(['x' => 2]);
});

it('validates enums and rejects bad values', function (): void {
    SettingField::create([
        'namespace' => 'mail',
        'name' => 'driver',
        'type' => 'string',
        'options' => ['enum' => ['smtp', 'log']],
        'default_value' => 'smtp',
    ]);

    $tenant = 't-2';
    Settings::driver()->set($tenant, 'mail.driver', 'log'); // ok

    Settings::driver()->set($tenant, 'mail.driver', 'ses'); // throws
})->throws(InvalidArgumentException::class);

it('caches per namespace and invalidates on update', function (): void {
    Cache::shouldReceive('store')->andReturn(Cache::driver());
    SettingField::create(['namespace' => 'mail', 'name' => 'from', 'type' => 'string', 'default' => 'a@x.com']);

    // warm cache
    expect(settings('mail.from'))->toBe('a@x.com');

    // update override
    $tenant = 't-c';
    Settings::driver()->set($tenant, 'mail.from', 'b@x.com');

    // should return new value after cache refresh
    [$v] = Settings::driver()->get($tenant, 'mail.from');
    expect($v)->toBe('b@x.com');
});

it('supports bulk set and forget', function (): void {
    SettingField::create([
        'namespace' => 'mail', 'name' => 'host', 'type' => 'string', 'default' => 'smtp.example.com',
    ]);
    SettingField::create([
        'namespace' => 'mail', 'name' => 'port', 'type' => 'int', 'default' => '25',
    ]);

    $tenant = 't-bulk';
    $updated = Settings::driver()->setMany($tenant, [
        'mail.host' => 'smtp.acme.com',
        'mail.port' => 587,
    ]);

    expect($updated)->toHaveKeys(['mail.host', 'mail.port']);
    [$host] = Settings::driver()->get($tenant, 'mail.host');
    [$port] = Settings::driver()->get($tenant, 'mail.port');
    expect($host)->toBe('smtp.acme.com')->and($port)->toBe(587);

    Settings::driver()->forget($tenant, 'mail.port');
    [$port2] = Settings::driver()->get($tenant, 'mail.port');
    expect($port2)->toBe(25); // back to default
});
