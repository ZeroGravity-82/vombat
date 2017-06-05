<?php

namespace Vombat;

use Illuminate\Database\Eloquent\Model;

class Email extends Contact
{
    /*
    |--------------------------------------------------------------------------
    | Email
    |--------------------------------------------------------------------------
    |
    | Контактные данные пользователя "адрес электронной почты".
    |
    */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address',
    ];

    /**
     * Имя таблицы БД, хранящей контактные данные пользователя "адрес электронной почты".
     *
     * @var string
     */
    protected $table = 'email';
}
