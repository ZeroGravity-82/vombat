<?php

namespace Vombat\Exceptions;

use Exception;
use Vombat\Contact;
use Vombat\UserAccount;
use Vombat\UserProfile;

class UserException extends Exception
{
    /*
    |--------------------------------------------------------------------------
    | UserException
    |--------------------------------------------------------------------------
    |
    | Исключения при работе с пользователями.
    |
    */

    /**
     * Не были предоставлены контактные данные пользователя.
     *
     * @param array $data
     * @return static
     */
    public static function contactsNotProvided(array $data)
    {
        return new static("Не были предоставлены контактные данные пользователя. " .
            "Содержимое запроса: " . http_build_query(array_except($data, ['password', '_token'])) . ".");
    }

    /**
     * Не найдена учётная запись пользователя по заданному профилю.
     *
     * @param UserProfile $profile
     * @return static
     */
    public static function profileWithoutUser(UserProfile $profile)
    {
        return new static("Не найдена учётная запись пользователя по заданному профилю, " .
            get_class($profile) . " id={$profile->id}.");
    }

    /**
     * Не найден профиль пользователя по заданной учётной записи пользователя.
     *
     * @param UserAccount $user
     * @return static
     */
    public static function userWithoutProfile(UserAccount $user)
    {
        return new static("Не найден профиль пользователя по заданной учётной записи пользователя, " .
            get_class($user) . " id={$user->id}.");
    }

    /**
     * Не найден профиль пользователя по заданным контактным данным.
     *
     * @param Contact $contact
     * @return static
     */
    public static function contactWithoutProfile(Contact $contact)
    {
        return new static("Не найден профиль пользователя по заданным контактным данным, " .
            get_class($contact) . " id={$contact->id}.");
    }
}