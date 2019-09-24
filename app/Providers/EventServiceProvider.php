<?php

namespace BBCMS\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Example
        // 'BBCMS\Events\Event' => [
        //     'BBCMS\Listeners\EventListener',
        // ],

        // From https://laravel.com/docs/master/authentication#events
        // 'Illuminate\Auth\Events\Registered' => [
        //     'BBCMS\Listeners\LogRegisteredUser',
        // ],
        //
        // 'Illuminate\Auth\Events\Attempting' => [
        //     'BBCMS\Listeners\LogAuthenticationAttempt',
        // ],
        //
        // 'Illuminate\Auth\Events\Authenticated' => [
        //     'BBCMS\Listeners\LogAuthenticated',
        // ],

        'Illuminate\Auth\Events\Login' => [
            'BBCMS\Listeners\LogSuccessfulLogin',
        ],

        // 'Illuminate\Auth\Events\Failed' => [
        //     'BBCMS\Listeners\LogFailedLogin',
        // ],
        //
        // 'Illuminate\Auth\Events\Logout' => [
        //     'BBCMS\Listeners\LogSuccessfulLogout',
        // ],
        //
        // 'Illuminate\Auth\Events\Lockout' => [
        //     'BBCMS\Listeners\LogLockout',
        // ],
        //
        // 'Illuminate\Auth\Events\PasswordReset' => [
        //     'BBCMS\Listeners\LogPasswordReset',
        // ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
