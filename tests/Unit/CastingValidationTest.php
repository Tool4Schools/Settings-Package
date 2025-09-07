<?php

declare(strict_types=1);

use Tools4Schools\Settings\Drivers\DatabaseSettingsDriver;
use Tools4Schools\Settings\Support\Tenant\DefaultTenantResolver;

it('prepare/cast round-trip for core types', function () {
    $drv = new DatabaseSettingsDriver(new DefaultTenantResolver());

    $reflect = new ReflectionClass($drv);
    $prep = $reflect->getMethod('prepareForStorage');
    $cast = $reflect->getMethod('castValue');

    expect($cast->invoke($drv, $prep->invoke($drv, 10,'int'), 'int'))->toBe(10);
    expect($cast->invoke($drv, $prep->invoke($drv, true,'bool'), 'bool'))->toBeTrue();
    expect($cast->invoke($drv, $prep->invoke($drv, ['a'=>1],'array'), 'array'))->toBe(['a'=>1]);
});
