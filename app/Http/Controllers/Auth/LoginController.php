<?php

namespace BBCMS\Http\Controllers\Auth;

use BBCMS\Http\Controllers\SiteController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 * This controller handles authenticating users for the application and
 * redirecting them to your home screen. The controller uses a trait
 * to conveniently provide its functionality to your applications.
 */
class LoginController extends SiteController
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $template = 'auth';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return setting('users.auth_username', 'name');
    }

    public function showLoginForm()
    {
        pageinfo([
            'title' => __('auth.login'),
            'robots' => 'noindex, follow',
        ]);

        return $this->makeResponse('login');
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectTo()
    {
        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }
}
