<?php

namespace BBCMS\Providers;

// Зарегистрированные фасады приложения.
use Illuminate\Support\Facades\Gate;

// Сторонние зависимости.
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
     * Регистрация любых аутентификационных / авторизационных служб.
     * @return void
     */
    public function boot()
    {
        // Регистрация политик, описанных в свойстве `$policies`.
        $this->registerPolicies();

        // Регистрация глобальных политик.
        $this->registerGlobalPolicies();
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
