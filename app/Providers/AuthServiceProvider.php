<?php

namespace BBCMS\Providers;

use BBCMS\Models\Privilege;

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
        \BBCMS\Models\User::class => \BBCMS\Policies\UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot()
    {
        // Check the existence of the cache.
        if (! cache()->has('roles')) {
            (new Privilege)->roles();
        }

        $this->registerPolicies();
        $this->registerGlobalPolicies();

        // Front-end policies.
        $this->registerComments();

        // Back-end policies.
        $this->registerAdminDashboardPolicies();
        $this->registerAdminSettingsPolicies();
        $this->registerAdminThemesPolicies();
        $this->registerAdminXFieldsPolicies();
        
        $this->registerAdminArticles();
        $this->registerAdminCategories();
        $this->registerAdminComments();
        $this->registerAdminFiles();
        $this->registerAdminNotes();
        $this->registerAdminPrivileges();
        $this->registerAdminUsers();
    }

    public function registerGlobalPolicies()
    {
        Gate::define('global.locked', function ($user) {
            return $user->canDo('global.locked');
        });
        Gate::define('global.admin', function ($user) {
            return $user->canDo('global.admin');
        });
    }

    protected function registerComments()
    {
        Gate::resource('comments', \BBCMS\Policies\CommentPolicy::class, [
            'update' => 'update',
            'delete' => 'delete',
        ]);
    }

    public function registerAdminDashboardPolicies()
    {
        Gate::define('admin.dashboard.index', function ($user) {
            return $user->canDo('admin.dashboard.index');
        });
    }

    public function registerAdminSettingsPolicies()
    {
        // Создание, редактирование, удаление настроек модулей
        Gate::define('admin.settings.modify', function ($user) {
            return 'owner' === $user->role and 'production' != env('APP_ENV');
        });

        // Просмотр и сохранение настроек модулей
        Gate::define('admin.settings.details', function ($user) {
            return 'owner' === $user->role;
        });
    }

    public function registerAdminThemesPolicies()
    {
        Gate::define('admin.themes', function ($user) {
            return 'owner' === $user->role;
        });
    }

    public function registerAdminXFieldsPolicies()
    {
        Gate::define('admin.xfields.modify', function ($user) {
            return 'owner' === $user->role;
        });
    }

    protected function registerAdminArticles()
    {
        Gate::resource('admin.articles', \BBCMS\Policies\ArticlePolicy::class, [
            'index' => 'index',
            'view' => 'view',
            'create' => 'create',
            'update' => 'update',
            'other_update' => 'otherUpdate',
            'delete' => 'delete',
        ]);
    }

    protected function registerAdminCategories()
    {
        Gate::resource('admin.categories', \BBCMS\Policies\CategoryPolicy::class, [
            'index' => 'index',
            'view' => 'view',
            'create' => 'create',
            'update' => 'update',
            'other_update' => 'otherUpdate',
            'delete' => 'delete',
        ]);
    }

    protected function registerAdminComments()
    {
        Gate::resource('admin.comments', \BBCMS\Policies\CommentPolicy::class, [
            'index' => 'index',
            'update' => 'update',
            'other_update' => 'otherUpdate',
            'delete' => 'delete',
        ]);
    }

    protected function registerAdminFiles()
    {
        Gate::resource('admin.files', \BBCMS\Policies\FilePolicy::class, [
            'index' => 'index',
            'view' => 'view',
            'create' => 'create',
            'update' => 'update',
            'delete' => 'delete',
        ]);
    }

    protected function registerAdminNotes()
    {
        Gate::resource('admin.notes', \BBCMS\Policies\NotePolicy::class, [
            'index' => 'index',
            'view' => 'view',
            'create' => 'create',
            'update' => 'update',
            'delete' => 'delete',
        ]);
    }

    protected function registerAdminPrivileges()
    {
        Gate::resource('admin.privileges', \BBCMS\Policies\PrivilegePolicy::class, [
            'index' => 'index',
            'view' => 'view',
            'create' => 'create',
            'update' => 'update',
            'other_update' => 'otherUpdate',
            'delete' => 'delete',
        ]);
    }

    protected function registerAdminUsers()
    {
        Gate::resource('admin.users', \BBCMS\Policies\UserPolicy::class, [
            'index' => 'index',
            'view' => 'view',
            'create' => 'create',
            'update' => 'update',
            'other_update' => 'otherUpdate',
            'delete' => 'delete',
        ]);
    }
}
