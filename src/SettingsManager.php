<?php


namespace Tools4Schools\Settings;

use InvalidArgumentException;

class SettingsManager
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;


    /**
     * The array of created drivers
     *
     * @var array
     */
    protected $drivers = [];

    /**
     * Create a new Tenant manager instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function driver($name = null)
    {
        $name = $name?: $this->getDefaultDriver();

        return $this->drivers[$name] ?? $this->drivers[$name] = $this->resolve($name);
    }

    public function registerDriver($driver,\Closure $callback)
    {
        $this->customRepositoryCreators[$driver] = $callback;

        return $this;
    }

    public function resolve(string $name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Settings Repository [{$name}] is not defined.");
        }

        if (isset($this->customRepositoryCreators[$config['driver']])) {
            return $this->callCustomRepositoryCreator($name, $config);
        }

        throw new InvalidArgumentException("Settings Repository [{$name}] is not defined.");
    }

    /**
     * Call a custom driver creator.
     *
     * @param  string  $name
     * @param  array  $config
     * @return mixed
     */
    protected function callCustomRepositoryCreator($name, array $config)
    {
        return $this->customRepositoryCreators[$config['driver']]($this->app, $name, $config);
    }

    protected function getConfig($name)
    {
        return $this->app['config']["settings.repositories.{$name}"];
    }

    /**
     * Get the default tenant driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['settings.defaults.driver'];
    }

    public function setDefaultDriver($name)
    {
        $this->app['config']['settings.defaults.driver'] = $name;
    }

    public function __call($method, $arguments)
    {
        return $this->driver()->{$method}(...$arguments);
    }
}