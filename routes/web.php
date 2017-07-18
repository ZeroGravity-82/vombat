<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

// ФИАС
Route::get('fias',         'FiasController@show');
Route::get('fias/updates', 'FiasController@editUpdates');

// Настройки
Route::get('settings/profile', 'SettingsController@editProfile')->name('profile.edit');
Route::get('settings/account', 'SettingsController@editAccount')->name('account.edit');
Route::get('settings/emails',  'SettingsController@editEmails')->name('emails.edit');
Route::put('settings/profile', 'SettingsController@updateProfile')->name('profile.update');
Route::put('settings/account', 'SettingsController@updateAccount')->name('account.update');
Route::put('settings/emails',  'SettingsController@updateEmails')->name('emails.update');

// Получение загруженных файлов
Route::get('uploads/{filename}', 'FileController@getUploadedMedia');

// Информация из профиля пользователя
Route::get('{username}', 'ProfileController@showProfile')->name('profile');
