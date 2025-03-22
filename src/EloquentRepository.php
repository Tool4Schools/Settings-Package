<?php

namespace Tools4Schools\Settings;

use Illuminate\Database\Query\Builder;
use Tools4Schools\Settings\Models\Setting;

class EloquentRepository extends SettingsRepository
{
    protected function loadSettings()
    {

        if($this->settings === null) {
            $this->settings = $this->cache->remember($this->cacheKey, $this->cacheExpirationTime, function () {
                $settings = [];
                foreach (Setting::all() as $setting) {
                    $settings[$setting->key] = $setting;
                }

                return $settings;
            });
        }
    }

    public function addScope($col, $value)
    {
        Setting::addGlobalScope($col, function (Builder $builder) use ($col, $value) {
            $builder->whereNull($col)->orWhere($col, $value);
        });
    }

    public function set(string $key, $value = null, $type = null)
    {
        $setting = new Setting(['key' => $key, 'value' => null]);

        if($this->has($key)) {
            $setting = $this->getModel($key);
        }
        $setting->value = $value;

        if(isset($type)) {
            $setting->type = $type;
        }

        $setting->save();

        $this->forgetCachedPermissions();
    }

    public function remove(string $key)
    {
        Setting::where('key', $key)->delete();

        $this->forgetCachedPermissions();
    }
}
