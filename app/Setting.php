<?php

namespace Vombat;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Setting
    |--------------------------------------------------------------------------
    |
    | Настроечный параметр приложения.
    |
    */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'value',
    ];

    /**
     * Имя таблицы БД, хранящей настроечный параметр приложения.
     *
     * @var string
     */
    protected $table = 'setting';
}
