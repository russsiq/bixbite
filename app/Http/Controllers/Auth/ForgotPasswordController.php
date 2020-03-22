<?php

namespace App\Http\Controllers\Auth;

// Сторонние зависимости.
use App\Http\Controllers\SiteController;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

/**
 * Password Reset Controller
 *
 * This controller is responsible for handling password reset emails and
 * includes a trait which assists in sending these notifications from
 * your application to your users. Feel free to explore this trait.
 */
class ForgotPasswordController extends SiteController
{
    use SendsPasswordResetEmails;

    /**
     * Пространство имен шаблонов.
     * @var string
     */
    protected $template = 'auth';

    /**
     * Показать форму запроса ссылки на сброс пароля.
     * @return mixed
     */
    public function showLinkRequestForm()
    {
        pageinfo([
            'title' => trans('auth.reset'),
            'robots' => 'noindex, follow',

        ]);

        return $this->makeResponse('passwords.email');
    }
}
