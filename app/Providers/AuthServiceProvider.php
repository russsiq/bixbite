<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Поставщик аутентификационных / авторизационных служб.
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Карта политик приложения.
     * @var array
     */
    protected $policies = [
        \App\Models\Article::class => \App\Policies\ArticlePolicy::class,
        \App\Models\Category::class => \App\Policies\CategoryPolicy::class,
        \App\Models\Comment::class => \App\Policies\CommentPolicy::class,
        \App\Models\File::class => \App\Policies\FilePolicy::class,
        \App\Models\Note::class => \App\Policies\NotePolicy::class,
        \App\Models\Privilege::class => \App\Policies\PrivilegePolicy::class,
        \App\Models\Setting::class => \App\Policies\SettingPolicy::class,
        \App\Models\Tag::class => \App\Policies\TagPolicy::class,
        \App\Models\Template::class => \App\Policies\TemplatePolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\XField::class => \App\Policies\XFieldPolicy::class,

    ];

    /**
     * Регистрация любых аутентификационных / авторизационных служб.
     * @return void
     */
    public function boot()
    {
        // Регистрация политик, описанных в свойстве `$policies`.
        $this->registerPolicies();

        // Регистрация глобальных политик.
        $this->registerGlobalPolicies();

        RateLimiter::for('api.auth.login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email.$request->ip());
        });
    }

    /**
     * Регистрация глобальных политик,
     * прежде всего, не имеющих связей с моделями БД.
     * @return void
     */
    public function registerGlobalPolicies()
    {
        // Доступ к заблокированному сайту.
        // Не используется в текущей версии.
        Gate::define('global.locked', function ($user) {
            return $user->canDo('global.locked');
        });

        // Доступ в административную панель.
        Gate::define('global.panel', function ($user) {
            return $user->canDo('global.panel');
        });

        // Просмотр главной страницы админ. панели.
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
}
