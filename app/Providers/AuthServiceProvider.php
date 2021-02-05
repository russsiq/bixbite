<?php

namespace App\Providers;

use App\Models\Team;
use App\Models\User;
use App\Policies\TeamPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Для супер-админа предоставляем все полномочия.
        Gate::before(function (User $user, string $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });
    }
}
