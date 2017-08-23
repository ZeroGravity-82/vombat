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
Route::get('fias', 'FiasController@index');

// Настройки
Route::get('user/profile', 'UserController@editProfile')->name('profile.edit');
Route::get('user/account', 'UserController@editAccount')->name('account.edit');
Route::get('user/emails',  'UserController@editEmails')->name('emails.edit');
Route::put('user/profile', 'UserController@updateProfile')->name('profile.update');
Route::put('user/account', 'UserController@updateAccount')->name('account.update');
Route::put('user/emails',  'UserController@updateEmails')->name('emails.update');

// Получение загруженных файлов
Route::get('uploads/{filename}', 'FileController@getUploadedMedia');

// Информация из профиля пользователя
Route::get('{username}', 'ProfileController@showProfile')->name('profile');
