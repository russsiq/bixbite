<?php

namespace App\Http\Controllers\Auth;

// Сторонние зависимости.
use App\Http\Controllers\SiteController;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

/**
 * Confirm Password Controller
 *
 * This controller is responsible for handling password confirmations and
 * uses a simple trait to include the behavior. You're free to explore
 * this trait and override any functions that require customization.
 */
class ConfirmPasswordController extends SiteController
{
    use ConfirmsPasswords;

    /**
     * Where to redirect users when the intended url fails.
     * Куда перенаправлять пользователей при сбое целевого url-адреса.
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Пространство имен шаблонов.
     * @var string
     */
    protected $template = 'auth';

    /**
     * Создать новый экземпляр контроллера.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Показать форму подтверждения пароля.
     * @return mixed
     */
    public function showConfirmForm()
    {
        pageinfo([
            'title' => trans('Confirm Password'),
            'robots' => 'noindex, follow',

        ]);

        return $this->makeResponse('passwords.confirm');
    }
}
