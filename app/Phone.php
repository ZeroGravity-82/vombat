<?php

namespace Vombat;

use Illuminate\Database\Eloquent\Model;

class Phone extends Contact
{
    /*
    |--------------------------------------------------------------------------
    | Phone
    |--------------------------------------------------------------------------
    |
    | Контактные данные пользователя типа "номер мобильного телефона".
    |
    */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
    ];

    /**
     * Имя таблицы БД, хранящей контактные данные пользователя "номер мобильного телефона".
     *
     * @var string
     */
    protected $table = 'phone';
}
