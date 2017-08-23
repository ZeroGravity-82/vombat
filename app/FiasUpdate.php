<?php

namespace Vombat;

use Illuminate\Database\Eloquent\Model;

class FiasUpdate extends Model
{
    /*
    |--------------------------------------------------------------------------
    | FiasUpdate
    |--------------------------------------------------------------------------
    |
    | Информация о версии обновления ФИАС.
    |
    */

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'version_id',
        'text_version',
        'fias_complete_xml_url',
        'fias_delta_xml_url',
    ];

    /**
     * Имя таблицы БД, хранящей данные обновления ФИАС.
     * @var string
     */
    protected $table = 'fias_update';


}
