<?php


namespace Tools4Schools\Settings;


use Illuminate\Database\Query\Builder;
use Tools4Schools\Settings\Models\Setting;

class EloquentRepository extends SettingsRepository
{

    protected function loadSettings()
    {
        foreach (Setting::all() as $setting)
        $this->settings[$setting->key] = $setting;
    }

    public function addScope($col, $value)
    {
        Setting::addGlobalScope($col,function (Builder $builder) use ($col,$value){
            $builder->whereNull($col)->orWhere($col,$value);
        });
    }

    public function set(string $key, $value = null,$type = null)
    {
        $setting = new Setting(['key'=>$key]);

        if($this->has($key))
        {
            $setting = $this->get($key);
        }
        $setting->value = $value;

        if(isset($type))
        {
            $setting->type = $type;
        }

        $setting->save();

        $this->settings[$key] = $setting;
    }

    public function remove(string $key)
    {
        Setting::where('key',$key)->delete();
    }
}