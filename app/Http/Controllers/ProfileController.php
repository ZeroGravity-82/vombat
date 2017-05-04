<?php

namespace Vombat\Http\Controllers;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ProfileController
    |--------------------------------------------------------------------------
    |
    | Управление профилем пользователя.
    |
    */

    public function showProfile()
    {
        return view('profile');
    }
}
