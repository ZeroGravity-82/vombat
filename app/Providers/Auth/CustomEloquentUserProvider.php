<?php

namespace Vombat\Providers\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Vombat\Contact;
use Vombat\Exceptions\UserException;

class CustomEloquentUserProvider extends EloquentUserProvider
{
    /*
    |--------------------------------------------------------------------------
    | CustomEloquentUserProvider
    |--------------------------------------------------------------------------
    |
    | Пользовательский провайдер аутентификации.
    |
    | Позволяет получить модель пользователя по имени пользователя или его контактным данным - номеру мобильного
    | телефона или адресу электронной почты.
    | Для получения модели пользователя по его контактным данным используются модели Vombat\Phone и Vombat\Email.
    |
    */

    /**
     * Номер телефона.
     *
     * @var mixed
     */
    protected $phone_model;

    /**
     * Адрес электронной почты.
     *
     * @var mixed
     */
    protected $email_model;

    /**
     * Конструктор.
     *
     * @param HasherContract $hasher
     * @param array $models
     */
    public function __construct(HasherContract $hasher, array $models)
    {
        parent::__construct($hasher, $models['user_model']);
        $this->phone_model = $models['phone_model'];
        $this->email_model = $models['email_model'];
    }

    /**
     * Получает пользователя, используя предоставленные им данные.
     *
     * @param  array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        // Было предоставлено имя пользователя
        if(isset($credentials['username'])) {
            $query = $this->createCustomModel($this->model)->newQuery();
            $query->where('username', $credentials['username']);
            return $query->first();
        }

        // Был предоставлен номер мобильного телефона или адрес электронной почты
        if(isset($credentials['mobile'])) {
            $query = $this->createCustomModel($this->phone_model)->newQuery();
            $query->where('number', $credentials['mobile']);
            $contact = $query->first();
        }
        if(isset($credentials['email'])) {
            $query = $this->createCustomModel($this->email_model)->newQuery();
            $query->where('address', $credentials['email']);
            $contact = $query->first();
        }
        try {
            if($contact instanceof Contact) {
                return $this->ownerOf($contact);
            }
            return null;                            // Пользователь не был найден по предоставленным контактным данным
        } catch (UserException $exception) {
            // TODO: Не уверен, что flash нужно делать здесь, а не в Handler->render()
            session()->flash('error', $exception->getMessage());
            return null;
        }
    }

    /**
     * Создаёт новый экземпляр указанной модели.
     *
     * @param string $model
     * @return mixed
     */
    protected function createCustomModel(string $model)
    {
        $class = '\\'.ltrim($model, '\\');
        return new $class;
    }

    /**
     * Получает учётную запись пользователя по указанным контактным данным через его профиль.
     *
     * @param Contact $contact
     * @return Authenticatable
     * @throws UserException Если не найден профиль пользователя по заданным контактным данным
     * @throws UserException Если не найдена учётная запись пользователя по заданному профилю
     */
    protected function ownerOf(Contact $contact): Authenticatable
    {
        $profile = $contact->owner;

        if (!isset($profile)) {
            throw UserException::contactWithoutProfile($contact);
        }
        $user = $profile->account;
        if (!isset($user)) {
            throw UserException::profileWithoutUser($profile);
        }
        return $user;
    }
}