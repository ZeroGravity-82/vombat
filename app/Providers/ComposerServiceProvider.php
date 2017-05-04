<?php

namespace Vombat\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Vombat\UserProfile;
use Vombat\Exceptions\UserException;

class ComposerServiceProvider extends ServiceProvider
{
    /*
    |--------------------------------------------------------------------------
    | ComposerServiceProvider
    |--------------------------------------------------------------------------
    |
    | Данный провайдер предназначен для создание композеров представлений.
    |
    | Его минимальная задача - добавление во все представления имени текущего маршрута (для подсветки активных
    | элементов навигации) и имени файла с аватаром (для отображения аватара на каждой странице).
    |
    */

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Передача данных в представления
        View::composer('*', function($view) {
            $view->with(['current_route_name' => Request::route()->getName(),   // Имя текущего маршрута
                         'avatar_filename'    => $this->avatarFilename(),       // Имя файла с аватаром
            ]);
        });
        View::composer('settings.profile', function($view) {
            $profile = Auth::user()->profile;
            $view->with(['full_name'      => $profile->full_name,               // Полное имя
                         'gender'         => $this->getGender($profile),        // Пол
                         'status_message' => $profile->status_message,          // Статус
                         'birth_day'      => $profile->birth_day,               // Дата рождения
            ]);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Определяет имя файла с аватаром пользователя.
     *
     * Если пользователь ни разу не загружал аватар, в качестве имени будет использоваться значение по умолчанию.
     *
     * @return string
     * @throws UserException Если не найден профиль пользователя по заданной учётной записи пользователя
     */
    private function avatarFilename(): string
    {
        $avatarFilename = '';
        if(Auth::check()) {
            $user = Auth::user();
            $profile = $user->profile;
            if(!isset($profile)) {
                throw UserException::userWithoutProfile($user);
            }
            $avatarFilename = config('settings.default_avatar');
            $avatar = $profile->firstMedia('avatar');
            if(isset($avatar)) {
                $ds = DIRECTORY_SEPARATOR;
                $avatarFilename = $ds . $avatar->disk . $ds . $avatar->basename;
            }
        }
        return $avatarFilename;
    }

    /**
     * Создаёт массив для отображения в представлении информации о поле пользователя.
     *
     * @param UserProfile $profile
     * @return array
     */
    private function getGender(UserProfile $profile): array
    {
        $gender['female'] = null;
        $gender['male']   = null;
        if($profile->gender === 0) $gender['female'] = 'checked';
        if($profile->gender === 1) $gender['male']   = 'checked';
        return $gender;
    }
}
