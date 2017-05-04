<?php

namespace Vombat;

use Illuminate\Database\Eloquent\Model;

class Email extends Contact
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address',
    ];

    /**
     * @var string
     */
    protected $table = 'email';
}
