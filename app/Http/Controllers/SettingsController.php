<?php

namespace Vombat\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Vombat\Exceptions\UserException;

class SettingsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SettingsController
    |--------------------------------------------------------------------------
    |
    | Управление настройками (профиля, учётной записи, электронной почты и т.п.).
    |
    */

    /**
     * Контроллер для управления файлами, загружаемыми пользователем.
     *
     * @var \Vombat\Http\Controllers\FileController
     */
    protected $fileController;

    /**
     * Конструктор.
     *
     * @param \Vombat\Http\Controllers\FileController $fileController
     */
    public function __construct(FileController $fileController)
    {
        $this->fileController = $fileController;
        $this->middleware('auth');
    }

    /**
     * Возвращает представление с настройками профиля пользователя.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editProfile()
    {
        return view('settings.profile');
    }

    /**
     * Сохраняет внесённые в профиль пользователя изменения.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws UserException Если не найден профиль пользователя по заданной учётной записи пользователя
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        // Получение профиля пользователя
        $user = $request->user();
        $profile = $user->profile;
        if(!isset($profile)) {
            throw UserException::userWithoutProfile($user);
        }

        // Аватар
        $avatarFile = $request->file('avatar');
        if($avatarFile instanceof UploadedFile) {
            $media = $this->fileController->uploadMedia($avatarFile);
            $profile->syncMedia($media, 'avatar');
        }

        // Прочие данные профиля пользователя
        $profile->full_name = $request->input('full_name');
        $profile->gender = $request->input('gender');
        $profile->status_message = $request->input('status_message');
        $birth_day = $request->input('birth_day');
        if(empty($birth_day)) {
            $birth_day = null;
        }
        $profile->birth_day = $birth_day;

        // Сохранение внесённых в профиль изменений
        $saved = $profile->save();

        // TODO: переделать на чтение строк из конфиг. файла
        $result = 'success';
        $message = 'Изменения успешно сохранены.';
        if(!$saved) {
            $result = 'error';
            $message = 'Не удалось сохранить изменения.';
        }
        return redirect()->route('profile.edit')->with($result, $message);
    }
}