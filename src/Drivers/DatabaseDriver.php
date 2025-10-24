<?php

declare(strict_types=1);

namespace Tools4Schools\Settings\Drivers;

use Illuminate\Contracts\Cache\Repository as CacheStore;
use Illuminate\Support\Facades\Cache;
use Tools4Schools\Settings\Contracts\SettingsDriver;
use Tools4Schools\Settings\Models\SettingField;
use Tools4Schools\Settings\Models\SettingValue;

class DatabaseDriver implements SettingsDriver
{
    protected array $settings = [];

    protected function fieldModel(): SettingField
    {
        return new (config('settings.repositories.database.models.field'));
    }

    protected function valueModel(): SettingValue
    {
        return new (config('settings.repositories.database.models.value'));
    }
    protected function cache(): CacheStore
    {
        $store = config('settings.cache_store', null);

        return Cache::store($store);
    }

    protected function cacheKey(?string $namespace = null): string
    {
        $prefix = config('settings.cache.prefix', 'settings');

        return $namespace ? "{$prefix}:{$namespace}" : "{$prefix}:all";
    }

    protected function useTags(): bool
    {
        return (bool) config('settings.cache_tags', true);
    }

    protected function withTags(): CacheStore
    {
        return $this->cache(); //->tags();
        /*
        $cache = $this->cache();
        return $this->useTags() && method_exists($cache, 'tags')
            ? $cache->tags([CacheKeys::tenantTag($tenantId)])
            : $cache;*/
    }

    public function all(?string $namespace = null): array
    {
        return $this->settings = $this->withTags()->remember($this->cacheKey($namespace), config('settings.expiration_time', null), function () use ($namespace) {
            $query = $this->fieldModel()->with('value');

            if ($namespace !== null) {
                $query->where('namespace', $namespace);
            }
            $result = [];

            foreach ($query->get() as $field) {
                $result[$field->namespace][$field->name] = data_get($field, 'value.value', $field->default);
            }

            return $result;

/*

            return $query->get()->mapWithKeys(fn ($value) => [
                $value->name => ($value->value?->value ?? $value->default)
            ])->toArray();*/
        }

        );

    }

    public function get(string $name, $default = null): mixed
    {
        [$namespace, $name] = explode('.', $name, 2);

        if(! isset($this->settings[$namespace])) {
            $this->all($namespace);
        }

        return $this->settings[$namespace][$name] ?? $default;
    }

    public function set(string $name, $value): void
    {
        [$namespace, $name] = explode('.', $name, 2);

        $field = $this->fieldModel()->with('value')->where(['namespace' => $namespace, 'name' => $name])->first();

        if(! $field) {
            throw new \InvalidArgumentException("Setting field [$namespace.$name] is not defined.");
        }

        $this->validateValue($value, $field->type, $field->options ?? []);

        // if field->value exists, update it, else create new

        // refresh the cache for this namespace
        $this->withTags()->forget($this->cacheKey($namespace));

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
