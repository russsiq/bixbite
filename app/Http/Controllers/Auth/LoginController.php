<?php

namespace BBCMS\Http\Controllers\Auth;

use BBCMS\Http\Controllers\SiteController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends SiteController
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
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $redirectToOwner = '/admin';

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
        return setting('users.login_username', 'name');
    }

    public function showLoginForm()
    {
        pageinfo()->make([
            'title' => __('auth.login'),
            'robots' => 'noindex, follow',
        ]);

        return $this->renderOutput('login');
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectTo()
    {
        if ('owner' === $this->guard()->user()->role) {
            return property_exists($this, 'redirectToOwner') ? $this->redirectToOwner : '/admin';
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }
}
