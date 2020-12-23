<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\SiteController;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 * Login Controller.
 *
 * This controller handles authenticating users for the application and
 * redirecting them to your home screen. The controller uses a trait
 * to conveniently provide its functionality to your applications.
 */
class LoginController extends SiteController
{
    use AuthenticatesUsers;

    /**
     * Куда перенаправить пользователя после входа.
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
        $this->middleware('guest')->except('logout');
    }

    /**
     * Показать форму входа на сайт.
     *
     * @return mixed
     */
    public function showLoginForm()
    {
        pageinfo([
            'title' => trans('auth.login'),
            'robots' => 'noindex, follow',

        ]);

        return $this->makeResponse('login');
    }

    /**
     * Получить имя пользователя для входа,
     * которое будет использоваться контроллером.
     *
     * @return string
     */
    public function username()
    {
        return setting('users.auth_username', 'name');
    }
}
