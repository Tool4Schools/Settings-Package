<?php

declare(strict_types=1);

namespace Tools4Schools\Settings\Events;

readonly class SettingsChanged
{
    public function __construct(
        public array $keys
    ) {
    }
}
