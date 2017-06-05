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

    /**
     * Возвращает представление с информацией из профиля пользователя.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProfile()
    {
        return view('profile');
    }
}
