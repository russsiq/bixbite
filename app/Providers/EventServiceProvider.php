<?php

namespace App\Providers;

// Зарегистрированные фасады приложения.
use Illuminate\Support\Facades\Event;

// Сторонние зависимости.
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
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
        // 'App\Events\Event' => [
        //     'App\Listeners\EventListener',
        // ],

        // From https://laravel.com/docs/5.7/upgrade Email Verification
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // From https://laravel.com/docs/master/authentication#events
        // 'Illuminate\Auth\Events\Registered' => [
        //     'App\Listeners\LogRegisteredUser',
        // ],

        // 'Illuminate\Auth\Events\Attempting' => [
        //     'App\Listeners\LogAuthenticationAttempt',
        // ],
        //
        // 'Illuminate\Auth\Events\Authenticated' => [
        //     'App\Listeners\LogAuthenticated',
        // ],

        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],

        // 'Illuminate\Auth\Events\Failed' => [
        //     'App\Listeners\LogFailedLogin',
        // ],
        //
        // 'Illuminate\Auth\Events\Logout' => [
        //     'App\Listeners\LogSuccessfulLogout',
        // ],
        //
        // 'Illuminate\Auth\Events\Lockout' => [
        //     'App\Listeners\LogLockout',
        // ],
        //
        // 'Illuminate\Auth\Events\PasswordReset' => [
        //     'App\Listeners\LogPasswordReset',
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
