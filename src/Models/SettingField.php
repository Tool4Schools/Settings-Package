<?php

declare(strict_types=1);

namespace Tools4Schools\Settings\Models;


use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;


class SettingField extends Model
{
    use HasUuids;

    public $table = 'settings_fields';

    protected $fillable = [
        'namespace',
        'name',
        'value',
        'type',
        'options',
        'default',
        'description',
        'meta',
        'secure',
    ];

    protected $casts = [
        'options' => 'array',
        'meta' => 'array',
        'secure' => 'boolean',
    ];
}
