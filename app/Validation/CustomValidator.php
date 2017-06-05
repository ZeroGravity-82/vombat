<?php

namespace Vombat\Validation;

use Illuminate\Validation\Validator as Validator;

class CustomValidator extends Validator
{
    /*
    |--------------------------------------------------------------------------
    | CustomValidator
    |--------------------------------------------------------------------------
    |
    | Реализует пользовательские правила валидации.
    |
    | Этот класс пришлось унаследовать от Validator для упрощения реализации метода validatePhoneUnique, чтобы не
    | писать заново проверку существования записи в базе данных. Однако пришлось переписать конструктор, - родительский
    | был бесполезен. Установка Presence Verifier в конструкторе позаимствована из метода registerValidationFactory
    | класса ValidationServiceProvider.
    |
    */

    /**
     * Конструктор.
     *
     */
    public function __construct()
    {
        $app = app();
        if (isset($app['validation.presence'])) {
            $this->setPresenceVerifier($app['validation.presence']);
        }
    }

    /**
     * Проверяет значение на соответствие имени пользователя.
     *
     * Имя пользователя может состоять только из букв английского алфавита, цифр, символа подчёркивания
     * и должно содержать хотя бы одну букву или один символ подчёркивания.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    public function validateUsername(string $attribute, $value, array $parameters): bool
    {
        $pattern = '/^(?=.*[a-zA-Z_])[a-zA-Z0-9_]+$/';
        return (bool)preg_match($pattern, $value);
    }

    /**
     * Проверяет значение на отсутствие зарезервированных слов.
     *
     * Имя пользователя не должно содержать зарезервированные слова.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return bool
     */
    public function validateReservedWordsFree(string $attribute, $value, array $parameters): bool
    {
        $reservedWords = array_map("strtolower", config('auth.reserved_words'));
        $containsReservedWords = str_contains(strtolower($value), $reservedWords);
        if ($containsReservedWords) return false;
        return true;
    }

    /**
     * Проверяет значение на соответствие формату номера мобильного телефона или адреса электронной почты.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateMobileOrEmail(string $attribute, $value, array $parameters): bool
    {
        return $this->isMobile($value) || $this->isEmail($value);
    }

    /**
     * Проверяет уникальность номера мобильного телефона в заданной таблице.
     *
     * Данный метод основан на методе validateUnique класса \Illuminate\Validation\Validator.
     * Перед подсчётом количества строк в заданной таблице происходит форматирование номера мобильного телефона,
     * поскольку исходное значение может содержать лишние символы (для сохранения пользовательского форматирования
     * их нельзя было очистить (предобработать) ранее в запросе).
     * Если ни один столбец таблицы не задан, используется имя самого атрибута.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validatePhoneUnique(string $attribute, $value, array $parameters): bool
    {
        $this->requireParameterCount(1, $parameters, 'phone_unique');

        list($connection, $table) = $this->parseTable($parameters[0]);
        // The second parameter position holds the name of the column that needs to
        // be verified as unique. If this parameter isn't specified we will just
        // assume that this column to be verified shares the attribute's name.
        $column = $parameters[1] ?? $this->guessColumnForQuery($attribute);

        list($idColumn, $id) = [null, null];

        if(isset($parameters[2])) {
            list($idColumn, $id) = $this->getUniqueIds($parameters);
            if(preg_match('/\[(.*)\]/', $id, $matches)) {
                $id = $this->getValue($matches[1]);
            }
            if(strtolower($id) == 'null') {
                $id = null;
            }
            if(filter_var($id, FILTER_VALIDATE_INT) !== false) {
                $id = intval($id);
            }
        }

        // The presence verifier is responsible for counting rows within this store
        // mechanism which might be a relational database or any other permanent
        // data store like Redis, etc. We will use it to determine uniqueness.
        $verifier = $this->getPresenceVerifier();

        $verifier->setConnection($connection);

        $extra = $this->getUniqueExtra($parameters);

        // Чтобы корректно выполнить поиск совпадающих значений в таблице, номер
        // мобильного телефона необходимо предварительно отформатировать.
        if($this->isMobile($value)) {
            $value = $this->formatMobile($value);
        }
        return $verifier->getCount(
            $table, $column, $value, $id, $idColumn, $extra

        ) == 0;
    }

    /**
     * Проверяет значение на соответствие формату номера мобильного телефона.
     *
     * В номере мобильного телефона допускаются пробелы.
     *
     * @param  string  $value
     * @return bool
     */
    public function isMobile(string $value): bool
    {
        $value = str_replace(' ', '', $value);
        $pattern = '/^((8|(\+)?7)-?)?\(?9\d{2}\)?(-?\d{1}){7}$/';
        return (bool)preg_match($pattern, $value);
    }

    /**
     * Проверяет значение на соответствие формату адреса электронной почты.
     *
     * В адресе электронной почты допускаются любые символы, обязательно наличие символа @ и точки.
     *
     * @param  string  $value
     * @return bool
     */
    public function isEmail(string $value): bool
    {
        $pattern  = '/^[^\s@]+@[^\s@]+\.[^\s@.]{2,}$/';
        return (bool)preg_match($pattern, $value);
    }

    /**
     * Форматирует номер мобильного телефона.
     *
     * Из номера телефона удаляются все символы кроме цифр, сохраняются последние 10 цифр.
     *
     * @param string $value
     * @return string
     */
    public function formatMobile(string $value): string
    {
        $value = preg_replace('/\D/', '', $value);
        return substr($value, strlen($value) - 10);
    }
}