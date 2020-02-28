<?php

namespace BBCMS\Http\Controllers\Auth;

// Сторонние зависимости.
use BBCMS\Http\Controllers\SiteController;
use BBCMS\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Email Verification Controller
 *
 * This controller is responsible for handling email verification for any
 * user that recently registered with the application. Emails may also
 * be re-sent if the user didn't receive the original email message.
 */
class VerificationController extends SiteController
{
    use VerifiesEmails;

    /**
     * Пространство имен шаблонов.
     * @var string
     */
    protected $template = 'auth';

    /**
     * Куда перенаправить пользователя после верификации.
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Создать новый экземпляр контроллера.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Показать форму верификации адреса электронной почты.
     * @param  Request  $request
     * @return mixed
     */
    public function show(Request $request)
    {
        pageinfo([
            'title' => trans('auth.verify'),
            'robots' => 'noindex, follow',

        ]);

        return $request->user()->hasVerifiedEmail()
            ? redirect($this->redirectPath())
            : $this->makeResponse('verify');
    }
}
