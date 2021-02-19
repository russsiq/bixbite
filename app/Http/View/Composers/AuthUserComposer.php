<?php

namespace App\Http\View\Composers;

use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\View\View as ViewContract;

class AuthUserComposer
{
    /** @var User */
    protected $user;

    /**
     * Create a new auth user composer.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->user = $auth->user();
    }

    /**
     * Bind data to the view.
     *
     * @param  ViewContract  $view
     * @return void
     */
    public function compose(ViewContract $view): void
    {
        $view->with('user', $this->user);
    }
}
