<?php

namespace BBCMS\Providers;

// Зарегистрированные фасады приложения.
use Illuminate\Support\Facades\Route;

// Сторонние зависимости.
use BBCMS\Models\Article;
use BBCMS\Models\Category;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Полнейший бардак с регулярными выражениями, т.е. в секции `boot`.
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     * @var string
     */
    public const HOME = '/';

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'BBCMS\Http\Controllers';

    protected $routePatterns = [
        'any' => '(.*)',
        'id' => '^[0-9]*$',

    ];

    /**
     * Define your route model bindings, pattern filters, etc.
     * @return void
     */
    public function boot()
    {
        Route::pattern('any', $this->routePattern('any'));
        Route::pattern('id', $this->routePattern('id'));

        // // Route::pattern('article', '^[a-z_]+$');
        Route::pattern('article_id', $this->routePattern('id'));
        // Route::pattern('article_slug', '^[\w\-0-9]+$');
        //
        // Route::pattern('category', '^[\w\-0-9\/]+$');
        // Route::pattern('category_slug', '^[\w\-0-9\/]+$');

        Route::pattern('commentable_id', $this->routePattern('id'));
        Route::pattern('commentable_type', '^[a-zA-Z_]+$');

        Route::pattern('tag', '^[\w\-0-9\+\%\s]+$');

        // Backend
        Route::pattern('module', '^[a-z_]+$');
        Route::pattern('module_id', $this->routePattern('id'));
        Route::pattern('setting_id', $this->routePattern('id'));

        // Common
        Route::pattern('model', '^[a-zA-Z_]+$');
        Route::pattern('attribute', '^[a-zA-Z_]+$');

        Route::bind('category', function($value) {
            $attribute = intval($value) ? 'id' : 'slug';

            return Category::where($attribute, $value)->first();
        });

        // Для новостей , статей не надо этого. Для пользователей не знаю
        // Route::model('article', \BBCMS\Models\Article::class);
        // Route::model('category', \BBCMS\Models\Category::class);
        // Route::model('users', BBCMS\Models\User::class);

        parent::boot();
    }

    public function routePatterns(): array
    {
        return $this->routePatterns;
    }

    public function routePattern(string $type): string
    {
        return $this->routePatterns[$type];
    }

    /**
     * Define the routes for the application.
     * @return void
     */
    public function map()
    {
        $this->mapRssRoutes();
        $this->mapApiRoutes();
        $this->mapCommonRoutes();
        $this->mapAdminRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Попробуем отключить посредников для rss лент.
     */
    protected function mapRssRoutes()
    {
        Route::middleware([
                // 'web',
            ])
            ->namespace($this->namespace.'\Rss')
            ->group(base_path('routes/web/rss.php'));
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware([
                'api',
            ])
            ->namespace($this->namespace.'\Api')
            ->group(base_path('routes/api.php'));
    }

    protected function mapCommonRoutes()
    {
        Route::prefix('app_common')
            ->middleware([
                'web',
            ])
            ->namespace($this->namespace.'\Common')
            ->group(base_path('routes/web/common.php'));
    }

    protected function mapAdminRoutes()
    {
        Route::middleware([
                'web',
            ])
            ->namespace($this->namespace.'\Admin')
            ->group(base_path('routes/web/admin.php'));
    }

    /**
     * Define the "web" routes for the application.
     * These routes all receive session state, CSRF protection, etc.
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware([
                'web',
            ])
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }
}
