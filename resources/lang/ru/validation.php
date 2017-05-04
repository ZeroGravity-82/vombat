<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'required' => 'Поле :attribute обязательно для заполнения.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'username'        => [
            'required'        => 'Укажите имя пользователя',
            'username'        => 'Имя пользователя может состоять только из букв английского алфавита, цифр, символа 
                                  подчёркивания и должно содержать хотя бы одну букву или один символ подчёркивания.',
            'reserved_words_free'
                              => 'Имя пользователя не должно содержать зарезервированный слова',
            'unique'          => 'Это имя уже занято другим пользователем.',
            'min'             => 'Минимальная длина :min символов',
            'max'             => 'Максимальная длина :max символов',
        ],
        'mobile_or_email' => [
            'required'        => 'Укажите телефон или email',
            'mobile_or_email' => 'Неверный телефон или email',
            'unique'          => 'Этот email уже занят другим пользователем',
            'phone_unique'    => 'Этот телефон уже занят другим пользователем',
            'max'             => 'Максимальная длина :max символа',
        ],
        'login'           => [
            'required'        => 'Введите имя пользователя, телефон или email'
        ],
        'password'        => [
            'required'        => 'Введите пароль',
            'min'             => 'Минимальная длина :min символов',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
