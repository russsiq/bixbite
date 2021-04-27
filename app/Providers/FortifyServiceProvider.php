<?php

namespace App\Providers;

use App\Actions\User\CreateUserAction;
use App\Actions\User\ResetUserPasswordAction;
use App\Actions\User\UpdateUserPasswordAction;
use App\Actions\User\UpdateUserProfileInformationAction;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->booting(function () {
            // Configure Fortify to not register its routes.
            Fortify::ignoreRoutes();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register the views for Fortify using conventional names under the given prefix.
        Fortify::viewPrefix('auth.');

        // Register a class / callback that should be used to Fortify Actions.
        Fortify::createUsersUsing(CreateUserAction::class);
        Fortify::resetUserPasswordsUsing(ResetUserPasswordAction::class);

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
