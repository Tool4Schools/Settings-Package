<?php


namespace Tools4Schools\Settings\Contracts;


interface Repository
{
    /**
     * Determine if the given setting value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function has(string $key);

    /**
     * Get the specified setting value.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Get all of the setting items for the application.
     *
     * @return array
     */
    public function all();

    /**
     * Set a given settings value.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  mixed   $type
     * @return void
     */
    public function set(string $key, $value = null,$type=null);

    /**
     * remove a given settings value.
     *
     * @param  string  $key
     * @return void
     */
    public function remove(string $key);
}