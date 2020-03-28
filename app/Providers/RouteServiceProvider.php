<?php

namespace App\Providers;

// Зарегистрированные фасады приложения.
use Illuminate\Support\Facades\Route;

// Сторонние зависимости.
use App\Models\Article;
use App\Models\Category;
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
    protected $namespace = 'App\Http\Controllers';

    protected $routePatterns = [
        'any' => '(.*)',
        'id' => '^[0-9]*$',
        'slug' => '^[\w\-\_0-9]*$',
        'alpha_dash' => '^[a-z_]+$',
        'encoded' => '^[\w\-0-9\+\%\s]+$',

    ];

    /**
     * Define your route model bindings, pattern filters, etc.
     * @return void
     */
    public function boot()
    {
        Route::pattern('any', $this->routePattern('any'));
        Route::pattern('id', $this->routePattern('id'));
        Route::pattern('slug', $this->routePattern('slug'));

        Route::pattern('category_slug', $this->routePattern('slug'));
        Route::pattern('article_id', $this->routePattern('id'));
        Route::pattern('article_slug', $this->routePattern('slug'));

        Route::pattern('commentable_id', $this->routePattern('id'));
        Route::pattern('commentable_type', $this->routePattern('alpha_dash'));

        Route::pattern('tag', $this->routePattern('encoded'));

        // Backend
        Route::pattern('module', $this->routePattern('alpha_dash'));
        Route::pattern('module_id', $this->routePattern('id'));
        Route::pattern('setting_id', $this->routePattern('id'));

        // Common
        Route::pattern('model', '^[a-zA-Z_]+$');
        Route::pattern('attribute', '^[a-zA-Z_]+$');

        // Для новостей , статей не надо этого. Для пользователей не знаю
        // Route::model('article', \App\Models\Article::class);
        // Route::model('category', \App\Models\Category::class);
        // Route::model('users', App\Models\User::class);

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
