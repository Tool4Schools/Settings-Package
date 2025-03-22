<?php

namespace Tools4Schools\Settings;

use Illuminate\Cache\CacheManager;
use Tools4Schools\Settings\Contracts\Repository;

abstract class SettingsRepository implements Repository
{
    /** @var \Illuminate\Contracts\Cache\Repository */
    protected $cache;

    /** @var \Illuminate\Cache\CacheManager */
    protected $cacheManager;

    protected $settings;

    /** @var \DateInterval|int */
    protected $cacheExpirationTime;

    /** @var string */
    protected $cacheKey;

    /** @var string */
    protected $cacheModelKey;

    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;

        $this->initializeCache();
    }

    protected function initializeCache()
    {
        $this->cacheExpirationTime = config('settings.cache.expiration_time');

        $this->cacheKey = config('settings.cache.key');

        $this->cacheModelKey = config('settings.cache.model_key');

        $this->cache = $this->getCacheStoreFromConfig();
    }

    protected function getCacheStoreFromConfig(): \Illuminate\Contracts\Cache\Repository
    {
        // the 'default' fallback here is from the permission.php config file, where 'default' means to use config(cache.default)
        $cacheDriver = config('settings.cache.store', 'default');

        // when 'default' is specified, no action is required since we already have the default instance
        if ($cacheDriver === 'default') {
            return $this->cacheManager->store();
        }

        // if an undefined cache store is specified, fallback to 'array' which is Laravel's closest equiv to 'none'
        if (! \array_key_exists($cacheDriver, config('cache.stores'))) {
            $cacheDriver = 'array';
        }

        return $this->cacheManager->store($cacheDriver);
    }

    /**
     * Flush the cache.
     */
    public function forgetCachedPermissions()
    {
        $this->settings = null;

        return $this->cache->forget($this->cacheKey);
    }

    /**
     * @param  string  $key
     * @return bool
     */
    public function has(string $key)
    {
        if(is_null($this->settings)) {
            $this->loadSettings();
        }

        return isset($this->settings[$key]);
    }

    public function getModel(string $name, $default = null)
    {
        if(is_null($this->settings)) {
            $this->loadSettings();
        }

        if (! is_null($this->settings[$name])) {
            return $this->settings[$name];
        }
    }

    public function get(string $name, $default = null)
    {
        if(is_null($this->settings)) {
            $this->loadSettings();
        }

        if (! is_null($this->settings[$name])) {
            return $this->settings[$name]->value;
        }
    }

    public function all()
    {
        if(is_null($this->settings)) {
            $this->loadSettings();
        }

        return $this->settings;
    }

    abstract public function set(string $key, $value = null, $type = null);

    abstract public function remove(string $key);

    abstract protected function loadSettings();

    abstract public function addScope($col, $value);
}
