<?php


namespace Tools4Schools\Settings;

use ArrayAccess;
use Tools4Schools\Settings\Contracts\Repository;

abstract class SettingsRepository implements Repository
{
    protected $settings;

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key){
        if(is_null($this->settings))
        {
            $this->loadSettings();
        }
        return isset($this->settings[$key]);
    }

    public function getModel(string $name,$default = null)
    {
        if(is_null($this->settings))
        {
            $this->loadSettings();
        }

        if (! is_null($this->settings[$name])) {
            return $this->settings[$name];
        }
    }

    public function get(string $name,$default = null)
    {
        if(is_null($this->settings))
        {
            $this->loadSettings();
        }

        if (! is_null($this->settings[$name])) {
            return $this->settings[$name]->value;
        }
    }

    public function all()
    {
        if(is_null($this->settings))
        {
            $this->loadSettings();
        }

        return $this->settings;
    }


    abstract public function set(string $key, $value = null,$type = null);

    abstract public function remove(string $key);

    abstract protected function loadSettings();

    abstract public function addScope($col,$value);
}