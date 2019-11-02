<?php

namespace BBCMS\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;

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

        // From https://laravel.com/docs/5.7/upgrade Email Verification
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // From https://laravel.com/docs/master/authentication#events
        // 'Illuminate\Auth\Events\Registered' => [
        //     'BBCMS\Listeners\LogRegisteredUser',
        // ],

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

        \Russsiq\Updater\Events\UpdateAvailable::class => [
            \Russsiq\Updater\Listeners\SendUpdateAvailableNotification::class
        ],

        \Russsiq\Updater\Events\UpdateSucceeded::class => [
            \Russsiq\Updater\Listeners\SendUpdateSucceededNotification::class
        ],

        \Russsiq\Updater\Events\UpdateFailed::class => [
            \Russsiq\Updater\Listeners\SendUpdateFailedNotification::class
        ],
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

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }

    /**
     * Get the listener directories that should be used to discover events.
     *
     * @return array
     */
    protected function discoverEventsWithin()
    {
        return [
            $this->app->path('Listeners'),
        ];
    }
}
