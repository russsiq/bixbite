<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\SiteController;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Register Controller.
 *
 * This controller handles the registration of new users as well as their
 * validation and creation. By default this controller uses a trait to
 * provide this functionality without requiring any additional code.
 */
class RegisterController extends SiteController
{
    use RegistersUsers;

    /**
     * Куда перенаправить пользователя после регистрации.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Пространство имен шаблонов.
     *
     * @var string
     */
    protected $template = 'auth';

    /**
     * Создать новый экземпляр контроллера.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Показать форму регистрации.
     *
     * @return mixed
     */
    public function showRegistrationForm()
    {
        pageinfo([
            'title' => trans('auth.register'),
            'robots' => 'noindex, follow',

        ]);

        return $this->makeResponse('register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data  Request data.
     * @return Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:users',

            ],

            'email' => [
                'required',
                'string',
                'max:255',
                'unique:users',
                'email',

            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',

            ],

            'registration_rules' => [
                'required',
                'boolean',
                'accepted',

            ],

        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'last_ip' => request()->ip(),
            // Only 'user'.
            'role' => 'user',

        ]);

        return $user;
    }

    /**
     * Пользователь зарегистрирован.
     *
     * @param  Request  $request
     * @param  mixed  $user
     * @return void
     */
    protected function registered(Request $request, $user)
    {
        // Только устанавливаем сообщение, ничего не возвращая.
        // Будут отработаны редиректы, заданные по умолчанию.
        session()->flash('status', trans('auth.register_complete'));
    }
}
