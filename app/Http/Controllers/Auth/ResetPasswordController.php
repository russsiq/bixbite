<?php

namespace BBCMS\Http\Controllers\Auth;

// Сторонние зависимости.
use BBCMS\Http\Controllers\SiteController;
use BBCMS\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

/**
 * Password Reset Controller
 *
 * This controller is responsible for handling password reset requests
 * and uses a simple trait to include this behavior. You're free to
 * explore this trait and override any methods you wish to tweak.
 */
class ResetPasswordController extends SiteController
{
    use ResetsPasswords;

    /**
     * Куда перенаправить пользователя после сброса пароля.
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Пространство имен шаблонов.
     * @var string
     */
    protected $template = 'auth';

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  Request  $request
     * @param  string|null  $token
     * @return mixed
     */
    public function showResetForm(Request $request, $token = null)
    {
        pageinfo([
            'title' => trans('auth.reset'),
            'robots' => 'noindex, follow',

        ]);

        return $this->makeResponse('passwords.reset', [
            'token' => $token,
            'email' => $request->email,

        ]);
    }
}
