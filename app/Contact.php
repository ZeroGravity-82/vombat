<?php

namespace Vombat;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Contact
    |--------------------------------------------------------------------------
    |
    | Базовый класс для всех типов контактных данных пользователя.
    |
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function owner()
    {
        return $this->morphTo();
    }
}
