<?php

namespace Vombat;

use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;

class UserProfile extends Model
{
    use Mediable;

    /**
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
    public function account()
    {
        return $this->belongsTo(UserAccount::class, 'user_account_id');
    }

    /**
     * Адреса электронной почты, которые связаны с данным профилем пользователя.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function emails()
    {
        return $this->morphMany(Email::class, 'owner');
    }

    /**
     * Номера телефонов, которые связаны с данным профилем пользователя.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function phones()
    {
        return $this->morphMany(Phone::class, 'owner');
    }
}