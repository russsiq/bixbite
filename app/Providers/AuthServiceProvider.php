<?php

namespace BBCMS\Providers;

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
        \BBCMS\Models\Article::class => \BBCMS\Policies\ArticlePolicy::class,
        \BBCMS\Models\Category::class => \BBCMS\Policies\CategoryPolicy::class,
        \BBCMS\Models\Comment::class => \BBCMS\Policies\CommentPolicy::class,
        \BBCMS\Models\File::class => \BBCMS\Policies\FilePolicy::class,
        \BBCMS\Models\Note::class => \BBCMS\Policies\NotePolicy::class,
        \BBCMS\Models\Privilege::class => \BBCMS\Policies\PrivilegePolicy::class,
        \BBCMS\Models\Setting::class => \BBCMS\Policies\SettingPolicy::class,
        \BBCMS\Models\Template::class => \BBCMS\Policies\TemplatePolicy::class,
        \BBCMS\Models\User::class => \BBCMS\Policies\UserPolicy::class,
        \BBCMS\Models\XField::class => \BBCMS\Policies\XFieldPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerGlobalPolicies();
        $this->registerDashboardPolicies();
    }

    public function registerGlobalPolicies()
    {
        Gate::define('global.locked', function ($user) {
            return $user->canDo('global.locked');
        });

        Gate::define('global.panel', function ($user) {
            return $user->canDo('global.panel');
        });
    }

    public function registerDashboardPolicies()
    {
        Gate::define('dashboard', function ($user) {
            return $user->canDo('dashboard');
        });

        // Определить посредника, проверяющего,
        // что текущий пользователь имеет право
        // воспользоваться Ассистентом приложения.
        Gate::define('use-assistant', function ($user) {
            return $user->canDo('dashboard');
        });
    }

    // Gate::define('notes.form', function ($user) {
    //     return $user->canDo('notes.form');
    // });
    //
    // Gate::resource('notes', NotePolicy::class, [
    //     'index' => 'index',
    //     'form' => 'form',
    //     'create' => 'store',
    //     'view' => 'show',
    //     'update' => 'update',
    //     'destroy' => 'destroy',
    // ]);
    //
    // Gate::define('notes.form', 'BBCMS\Policies\NotePolicy@form');
    //
    // dd(auth('api')->user()->can('create', Note::class));
}
