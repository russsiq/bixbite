<?php

namespace BBCMS\Http\Controllers\Auth;

use BBCMS\Models\User;
use BBCMS\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends SiteController
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

    use RegistersUsers; // !!!!!!!!!!!!!!!!!!!!!!!!!

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

        return $this->renderOutput('register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'      => 'required|string|max:255|unique:users',
            'email'     => 'required|string|max:255|unique:users|email',
            'password'  => 'required|string|min:6|confirmed',
            'registration_rules'  => 'required|boolean',
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
}
