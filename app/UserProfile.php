<?php

namespace Vombat;

use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class UserProfile extends Model
{
    /*
    |--------------------------------------------------------------------------
    | UserProfile
    |--------------------------------------------------------------------------
    |
    | Профиль пользователя.
    |
    */

    use Mediable;

    /**
     * Имя таблицы БД, хранящей профиль пользователя.
     *
     * @var string
     */
    protected $table = 'user_profile';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'last_visit_at',
    ];

    /**
     * Учётная запись, которой принадлежит данный профиль пользователя.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'user_account_id');
    }

    /**
     * Адреса электронной почты, которые связаны с данным профилем пользователя.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function emails(): MorphMany
    {
        return $this->morphMany(Email::class, 'owner');
    }

    /**
     * Номера телефонов, которые связаны с данным профилем пользователя.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function phones(): MorphMany
    {
        return $this->morphMany(Phone::class, 'owner');
    }
}