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
    protected function fieldModel(): SettingField
    {
        return new (config('settings.field_model'));
    }

    protected function valueModel() :SettingValue
    {
        return new (config('settings.value_model'));
    }
    protected function cache(): CacheStore
    {
        $store = config('settings.cache_store', null);
        return Cache::store($store);
    }

    protected function useTags(): bool
    {
        return (bool) config('settings.cache_tags', true);
    }

    protected function withTags(?string $tenantId)
    {/*
        $cache = $this->cache();
        return $this->useTags() && method_exists($cache, 'tags')
            ? $cache->tags([CacheKeys::tenantTag($tenantId)])
            : $cache;*/
    }

    public function all(?string $namespace = null): array
    {


    }
}