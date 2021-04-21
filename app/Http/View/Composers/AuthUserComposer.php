<?php

namespace App\Http\View\Composers;

use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\View\View as ViewContract;

class AuthUserComposer
{
    /** @var Guard */
    protected $auth;

    /**
     * Create a new auth user composer.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Bind data to the view.
     *
     * @param  ViewContract  $view
     * @return void
     */
    public function compose(ViewContract $view): void
    {
        $view->with('user', $this->authUser());
    }

    /**
     * Get the currently authenticated user.
     *
     * @NB Don't use any cache with all drivers.
     *
     * @return User|null
     */
    protected function authUser(): ?User
    {
        return $this->auth->user();
    }
}
