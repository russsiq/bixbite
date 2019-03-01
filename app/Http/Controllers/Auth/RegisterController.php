<?php

namespace BBCMS\Http\Controllers\Auth;

use BBCMS\Models\User;
use BBCMS\Http\Controllers\SiteController;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends SiteController
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        pageinfo([
            'title' => __('auth.register'),
            'robots' => 'noindex, follow',
        ]);

        return $this->makeResponse('register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data Request data.
     * @return \Illuminate\Contracts\Validation\Validator
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
                'min:6',
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
     * @return \App\User
     */
    protected function create(array $data)
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
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        return $this->makeRedirect(true, $this->redirectPath(), __('Register complete!'));
    }


    protected function unfitPreviousUrl()
    {
        return in_array(url()->previous(), [
            action('Auth\RegisterController@register'),
            action('Auth\RegisterController@showRegistrationForm'),
        ]);
    }
}
