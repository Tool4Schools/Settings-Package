<?php

namespace Tools4Schools\Settings\Drivers;

use Illuminate\Database\Query\Builder;
use Tools4Schools\Settings\Models\SettingField;
use Tools4Schools\Settings\Drivers\SettingsDriver;

class EloquentDriver extends SettingsDriver
{
    protected function loadSettings()
    {

        if($this->settings === null) {
            $this->settings = $this->cache->remember($this->cacheKey, $this->cacheExpirationTime, function () {
                $settings = [];
                foreach (SettingField::all() as $setting) {
                    $settings[$setting->key] = $setting;
                }

                return $settings;
            });
        }
    }

    public function addScope($col, $value)
    {
        SettingField::addGlobalScope($col, function (Builder $builder) use ($col, $value) {
            $builder->whereNull($col)->orWhere($col, $value);
        });
    }

    public function set(string $name, $value = null, $type = null,bool $secure = false): void
    {
        $setting = new SettingField(['name' => $name, 'value' => null]);

        if($this->has($name)) {
            $setting = $this->getModel($name);
        }
        $setting->value = $value;

        if(isset($type)) {
            $setting->type = $type;
        }

        $setting->save();

        $this->forgetCachedPermissions();
    }

    public function remove(string $name): void
    {
        SettingField::where('name', $name)->delete();

        $this->forgetCachedPermissions();
    }
}
