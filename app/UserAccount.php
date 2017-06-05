<?php

namespace Vombat;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserAccount extends Authenticatable
{
    /*
    |--------------------------------------------------------------------------
    | UserAccount
    |--------------------------------------------------------------------------
    |
    | Учётная запись пользователя.
    |
    */

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Имя таблицы БД, хранящей учётную запись пользователя.
     *
     * @var string
     */
    protected $table = 'user_account';

    /**
     * Профиль пользователя, принадлежащий данной учётной записи.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }
}
