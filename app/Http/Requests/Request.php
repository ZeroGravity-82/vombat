<?php

namespace Vombat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\Validator;
use Vombat\Validation\CustomValidator;

abstract class Request extends FormRequest
{
    /*
    |--------------------------------------------------------------------------
    | Request
    |--------------------------------------------------------------------------
    |
    | Абстрактный класс для всех FormRequest.
    | Реализует механизм очистки (предобработки) параметров запроса.
    |
    */

    /**
     * Параметры, ожидаемые в запросе.
     *
     * @var array
     */
    protected $expectedParams = [];

    /**
     * Валидатор.
     *
     * @var CustomValidator
     */
    protected $validator;

    /**
     * Конструктор.
     *
     * @param \Vombat\Validation\CustomValidator $validator
     */
    public function __construct(CustomValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Возвращает экземпляр валидатора, передавая ему очищенные (предобработанные) параметры запроса.
     *
     * @param  \Illuminate\Validation\Factory $factory
     * @return \Illuminate\Validation\Validator
     */
    public function validator(ValidatorFactory $factory): Validator
    {
        return $factory->make(
            $this->sanitizeInput(), $this->container->call([$this, 'rules']), $this->messages(), $this->attributes()
        );
    }

    /**
     * Очищает (выполняет предобработку) параметров запроса.
     *
     * @return array|null
     */
    protected function sanitizeInput(): ?array
    {
        array_filter($this->expectedParams, function ($param) {
            $this->sanitizeParam($param);
        }, ARRAY_FILTER_USE_BOTH);
        return $this->all();
    }

    /**
     * Вызывает метод очистки для параметра, если этот метод определён.
     *
     * Сам метод очистки должен быть определён в производном классе. Имя метода должно начинаться со слова "sanitize",
     * за которым следует название параметра с заглавной буквы.
     *
     * @param string $field
     * @return void
     */
    protected function sanitizeParam(string $field): void
    {
        $input = $this->all();
        if (method_exists($this, 'sanitize' . studly_case($field))) {
            $input[$field] = $this->container->call([$this, 'sanitize' . studly_case($field)], [$input[$field]]);
            $this->replace($input);
        }
    }

    /**
     * Создаёт в запросе дополнительный параметр mobile, если передаваемая строка содержит номер мобильного телефона.
     *
     * @param string $input
     * @return void
     */
    protected function mergeIfMobile(string $input): void
    {
        if ($this->validator->isMobile($input)) {
            $mobile = $this->validator->formatMobile($input);
            $this->merge(['mobile' => $mobile]);
        }
    }

    /**
     * Создаёт в запросе дополнительный параметр email, если передаваемая строка содержит адрес электронной почты.
     *
     * @param string $input
     * @return void
     */
    protected function mergeIfEmail(string $input): void
    {
        if ($this->validator->isEmail($input)) {
            $this->merge(['email' => $input]);
        }
    }
}