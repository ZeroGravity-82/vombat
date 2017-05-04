<?php

namespace Vombat\Providers\Auth;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Validator;
use Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'Vombat\Model' => 'Vombat\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Подключение пользовательских правил валидации
        Validator::extend('username',           '\Vombat\Validation\CustomValidator@validateUsername');
        Validator::extend('reserved_words_free','\Vombat\Validation\CustomValidator@validateReservedWordsFree');
        Validator::extend('mobile_or_email',    '\Vombat\Validation\CustomValidator@validateMobileOrEmail');
        Validator::extend('phone_unique',       '\Vombat\Validation\CustomValidator@validatePhoneUnique');

        // Регистрация пользовательского провайдера аутентификации
        Auth::provider('custom_eloquent', function($app, array $config) {
            return new CustomEloquentUserProvider($app['hash'], $config['models']);
        });

    }
}
