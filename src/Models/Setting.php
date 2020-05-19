<?php


namespace Tools4Schools\Settings\Models;


use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key','value'];
}