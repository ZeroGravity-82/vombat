<?php

namespace Vombat\Http\Requests;

class RegisterUserRequest extends Request
{
    /**
     * Параметры, ожидаемые в запросе. Они будут очищены (предобработаны) при наличии соответствующего метода в этом
     * классе.
     *
     * @var array
     */
    protected $expectedParams = ['username', 'mobile_or_email', 'password'];

    /**
     * Проверяет, авторизован ли пользователь для выполнения этого запроса.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Очищает (выполняет предобработку) параметра username.
     *
     * @param string $value
     * @return string
     */
    public function sanitizeUsername(string $value): string
    {
        return trim($value);
    }

    /**
     * Очищает (выполняет предобработку) параметра mobile_or_email.
     *
     * @param string $value
     * @return string
     */
    public function sanitizeMobileOrEmail(string $value): string
    {
        return trim($value);
    }

    /**
     * Возвращает применяемые к запросу правила валидации.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'username'        => 'required|username|reserved_words_free|unique:user_account,username|min:3|max:20',
            'mobile_or_email' => 'required|mobile_or_email|unique:email,address|phone_unique:phone,number|max:254',
            'password'        => 'required|min:6',
        ];
    }

    /**
     * Выполняет валидацию параметров запроса.
     *
     * Метод расширен.
     * После успешной валидации создаются дополнительные параметры запроса - mobile и email. После этого для одного
     * из них устанавливается значение исходя из того, какое значение имеет параметр
     * mobile_or_email.
     *
     * @return void
     */
    public function validate(): void
    {
        parent::validate();
        $mobile_or_email = $this->input('mobile_or_email');
        $this->merge(['mobile' => null, 'email' => null]);
        $this->mergeIfMobile($mobile_or_email);
        $this->mergeIfEmail($mobile_or_email);
    }
}