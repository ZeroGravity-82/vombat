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
    | Контактные данные пользователя "номер мобильного телефона".
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
     * @var string
     */
    protected $table = 'phone';
}
