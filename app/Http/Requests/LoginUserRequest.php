<?php

namespace Vombat\Http\Requests;

class LoginUserRequest extends Request
{
    /**
     * Параметры, ожидаемые в запросе. Они будут очищены (предобработаны) при наличии соответствующего метода в этом
     * классе.
     *
     * @var array
     */
    protected $expectedParams = ['login', 'password'];

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
     * Очищает (выполняет предобработку) параметра login.
     *
     * @param string $value
     * @return string
     */
    public function sanitizeLogin(string $value): string
    {
        $value = trim($value);
        return $value;
    }

    /**
     * Возвращает применяемые к запросу правила валидации.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'login'     => 'required',
            'password'  => 'required',
        ];
    }

    /**
     * Выполняет валидацию параметров запроса.
     *
     * Метод расширен.
     * После успешной валидации на основе параметра login создаётся дополнительный параметр запроса - username,
     * mobile или email.
     *
     * @return void
     */
    public function validate(): void
    {
        parent::validate();
        $login = $this->input('login');
        $this->merge(['username' => null, 'mobile' => null, 'email' => null]);
        $this->mergeIfMobile($login);
        $this->mergeIfEmail($login);

        $mobileOrEmail = $this->has('mobile') || $this->has('email');
        if(!$mobileOrEmail) {
            $this->merge(['username' => $login]);
        }
    }
}
