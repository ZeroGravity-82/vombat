<?php

namespace Vombat\Exceptions;

use Exception;

class FiasException extends Exception
{
    /*
    |--------------------------------------------------------------------------
    | FiasException
    |--------------------------------------------------------------------------
    |
    | Исключения при работе со службой получения обновлений ФИАС.
    |
    */

    /**
     * Не удалось подключиться к службе получения обновлений ФИАС.
     *
     * @return static
     */
    public static function FiasConnectionFailed()
    {
        return new static("Не удалось подключиться к службе получения обновлений ФИАС.");
    }

}