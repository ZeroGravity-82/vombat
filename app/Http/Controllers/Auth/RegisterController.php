<?php

namespace Vombat\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Vombat\Http\Controllers\Controller;
use Vombat\Http\Requests\RegisterUserRequest;
use Vombat\UserAccount;
use Vombat\Exceptions\UserException;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Расположение для перенаправления пользователя после регистрации.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Обрабатывает запрос с параметрами регистрации.
     * Метод переопределён.
     * Для валидации запроса используется RegisterUserRequest.
     *
     * @param  RegisterUserRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(RegisterUserRequest $request)
    {
        $this->guard()->login($this->create($request->all()));
        return redirect($this->redirectPath());
    }

    /**
     * Создаёт экземпляр нового пользователя.
     *
     * @param  array $data
     * @return UserAccount
     */
    protected function create(array $data): UserAccount
    {
        DB::beginTransaction();
        try {
            $user = UserAccount::create([
                'username' => $data['username'],
                'password' => bcrypt($data['password']),
            ]);

            // Для пользователя создаётся профиль
            $this->makeProfile($user, $data);
            DB::commit();
        }
        catch (UserException $exception) {
            DB::rollBack();
            session()->flash('error', $exception->getMessage());
        }
        return $user;
    }

    /**
     * Создаёт профиль пользователя и привязывает к нему контактные данные (номер мобильного телефона или адрес
     * электронной почты).
     *
     * @param UserAccount $user
     * @param array $data
     * @return void
     * @throws UserException Если не были предоставлены контактные данные пользователя
     */
    protected function makeProfile(UserAccount $user, array $data): void
    {
        $currentTimestamp = $user->freshTimestamp();
        $profile = $user->profile()->create([
            'last_visit_at' => $currentTimestamp,
        ]);

        if(isset($data['mobile'])) {
            $profile->phones()->create([
                'number' => $data['mobile'],
            ]);
            return;
        }
        if(isset($data['email'])) {
            $profile->emails()->create([
                'address' => $data['email'],
            ]);
            return;
        }
        throw UserException::contactsNotProvided($data);
    }
}