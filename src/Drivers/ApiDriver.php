<?php

declare(strict_types=1);

namespace Tools4Schools\Settings\Drivers;

use Tools4Schools\Settings\Contracts\SettingsDriver;

class ApiDriver implements SettingsDriver
{
    public function all(?string $namespace = null): array
    {

    }

    public function get(string $key, $default = null): mixed
    {

    }

    public function set(string $name, $value): void
    {
        // TODO: Implement set() method.
    }

    public function setMany(array $settings): void
    {
        // TODO: Implement setMany() method.
    }

    public function has(string $name): bool
    {
        // TODO: Implement has() method.
    }

    public function remove(string $name): void
    {
        // TODO: Implement remove() method.
    }

    public function refreshCache(): void
    {
        // TODO: Implement refreshCache() method.
    }
}
