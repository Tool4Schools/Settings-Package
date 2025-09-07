<?php

declare(strict_types=1);

namespace Tools4Schools\Settings\Models;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SettingValue extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'setting_values';

    protected $fillable = [
        'setting_id',
        'value',
    ];

    protected $with = [
        'setting',
    ];

    public function setting()
    {
        return $this->belongsTo(SettingField::class, 'setting_id');
    }

    protected function value(): Attribute
    {
        return Attribute::make(
            get: function ($value, array $attributes): mixed {
                if (($this->setting->secure ?? false)) {
                    try {
                        $value = Crypt::decryptString($value);
                    }catch (\Exception $exception){
                        // fallback to raw value
                    }
                }
                return match ($this->setting->type ?? null) {
                    'bool' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
                    'int' => (int)$value,
                    'json' => json_decode($value, true),
                    default => $value,
                };
            },
            set: function ($value, array $attributes): string {
                $encoded = is_array($value) ? json_encode($value) : (string)$value;

                return ($this->setting->secure ?? false)
                    ? Crypt::encryptString($encoded)
                    : $encoded;
            }
        );
    }

}