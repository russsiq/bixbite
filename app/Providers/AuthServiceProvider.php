<?php

namespace App\Providers;

use App\Models\Sanctum\PersonalAccessToken;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\Sanctum;

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
        $this->registerPolicies();
        $this->registerGlobalPolicies();

        // Хуки `before` и `after` в текущем определении
        // будут применены только к зарегистрированным пользователям.
        Gate::before(function (\App\Models\User $user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });

        Gate::after(function (\App\Models\User $user, $ability, $result, $arguments) {
            return false;
        });

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
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
        Gate::define('global.dashboard', function ($user) {
            return $user->canDo('global.dashboard');
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
