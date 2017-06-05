<?php

namespace Vombat\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Vombat\Http\Controllers\Controller;
use Vombat\Http\Requests\LoginUserRequest;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Расположение для перенаправления пользователя после аутентификации.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Имя поля формы аутентификации, содержащее логин.
     *
     * @var string
     */
    protected $loginFieldName = 'login';

    /**
     * Запрос, для которого был создан этот экземпляр контроллера.
     *
     * @var LoginUserRequest
     */
    protected $request;

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Обрабатывает запрос с параметрами аутентификации.
     * Метод переопределён - для валидации запроса используется LoginUserRequest.
     *
     * @param  LoginUserRequest $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function login(LoginUserRequest $request)
    {
        $this->request = $request;

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        $credentials = $this->credentials($request);
        if ($this->guard()->attempt($credentials, $request->has('remember'))) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if (! $lockedOut) {
            $this->incrementLoginAttempts($request);
        }
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Перенаправляет заблокированного пользователя.
     * Метод переопределён - в сессии сохраняется имя поля формы аутентификации, содержащее логин, а не название
     * параметра запроса, который был использован контроллером для аутентификации пользователя.
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );
        $message = Lang::get('auth.throttle', ['seconds' => $seconds]);
        return redirect()->back()
            ->withInput($request->only($this->loginFieldName, 'remember'))
            ->withErrors([$this->loginFieldName => $message]);
    }

    /**
     * Перенаправляет пользователя после ошибки аутентификации.
     * Метод переопределён:
     * 1. Ссылка на строку с описанием ошибки аутентификации формируется динамически в зависимости от того, что ввёл
     *    пользователь в качестве логина.
     * 2. В сессии сохраняется имя поля формы аутентификации, содержащее логин, а не название параметра запроса,
     *    который был использован контроллером для аутентификации пользователя.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        // Динамическое формирование ссылки на строку с описанием ошибки аутентификации.
        $login = $this->username();
        $authFailedLangLine = 'auth.' . $login . '_failed';
        return redirect()->back()
            ->withInput($request->only($this->loginFieldName, 'remember'))
            ->withErrors([
                $this->loginFieldName => Lang::get($authFailedLangLine),
            ]);
    }

    /**
     * Определяет название параметра запроса, который будет использован контроллером для аутентификации пользователя.
     * Метод переопределён - возвращает название дополнительного параметра запроса - username, mobile или email,
     * который был добавлен валидатором.
     *
     * @return string
     */
    public function username(): string
    {
        $username = 'email';
        if($this->request->has('username')) {
            $username = 'username';
        }
        if($this->request->has('mobile')) {
            $username = 'mobile';
        }
        return $username;
    }
}