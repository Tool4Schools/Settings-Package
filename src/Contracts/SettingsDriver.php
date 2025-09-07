<?php

declare(strict_types=1);

namespace Tools4Schools\Settings\Contracts;

interface SettingsDriver
{
    public function all(): array;
    public function get(string $name, $default = null): mixed;
    public function set(string $name, $value): void;
    public function setMany(array $settings): void;
    public function has(string $name): bool;
    public function remove(string $name): void;
    public function refreshCache(): void;
}