<?php

namespace Tools4Schools\Settings\Console;

use Illuminate\Console\Command;
use Tools4Schools\Settings\Models\SettingField;

class MakeSettingFieldCommand extends Command
{
    protected $signature = 'settings:make-field {key} {--type=string} {--default=} {--options=} {--description=} {--secure}';
    protected $description = 'Create or update a settings field (namespace.name).';

    public function handle(): int
    {
        $key = $this->argument('key');
        if (! str_contains($key, '.')) {
            $this->error('Key must be in "namespace.name" format.');

            return self::FAILURE;
        }

        [$namespace, $name] = explode('.', $key, 2);
        $type = $this->option('type');
        $default = $this->option('default');
        $optionsRaw = $this->option('options');
        $options = $optionsRaw ? json_decode($optionsRaw, true) : null;

        $field = SettingField::updateOrCreate(
            ['namespace' => $namespace, 'name' => $name],
            [
                'type' => $type,
                'default_value' => $default,
                'options' => $options,
                'description' => $this->option('description'),
                'secure' => $this->option('secure', false),
            ]
        );

        $this->info("Field [{$namespace}.{$name}] saved (type={$type}).");

        return self::SUCCESS;
    }
}
