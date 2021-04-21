<?php

namespace App\Providers;

// Зарегистрированные фасады приложения.
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

// Сторонние зависимости.
use App\Models\Article;
use App\Models\Category;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;

/**
 * Полнейший бардак с регулярными выражениями, т.е. в секции `boot`.
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    protected $routePatterns = [
        'any' => '(.*)',
        'id' => '^[0-9]*$',
        'slug' => '^[\w\-\_0-9]*$',
        'alpha_dash' => '^[a-z_]+$',
        'encoded' => '^[\w\-0-9\+\%]+$',

    ];

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();
        $this->configureRoutePatterns();

        // Для новостей , статей не надо этого. Для пользователей не знаю
        // Route::model('article', \App\Models\Article::class);
        // Route::model('category', \App\Models\Category::class);
        // Route::model('users', App\Models\User::class);

        $this->routes(function () {
            $this->mapRssRoutes();
            $this->mapApiRoutes();
            $this->mapAdminRoutes();
            $this->mapFrontRoutes();
            $this->mapWebRoutes();
        });
    }

    public function configureRoutePatterns()
    {
        Route::patterns([
            'any' => $this->routePattern('any'),
            'id' => $this->routePattern('id'),
            'slug' => $this->routePattern('slug'),
            'category_slug' => $this->routePattern('slug'),
            'article_id' => $this->routePattern('id'),
            'article_slug' => $this->routePattern('slug'),
            'commentable_id' => $this->routePattern('id'),
            'commentable_type' => $this->routePattern('alpha_dash'),
            'tag' => $this->routePattern('encoded'),

            // Backend
            'module' => $this->routePattern('alpha_dash'),
            'module_id' => $this->routePattern('id'),
            'setting_id' => $this->routePattern('id'),

            // Common
            'model' => '^[a-zA-Z_]+$',
            'attribute' => '^[a-zA-Z_]+$',
        ]);
    }

    public function routePattern(string $type): string
    {
        return $this->routePatterns[$type];
    }

    /**
     * Попробуем отключить посредников для rss лент.
     */
    protected function mapRssRoutes()
    {
        Route::middleware([
                // 'web',

            ])
            ->group(base_path('routes/web/rss.php'));
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api/v1')
            ->middleware([
                'api',

            ])
            ->group(base_path('routes/api.php'));
    }

    /**
     * Определить маршруты «админ» для приложения.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::middleware([
                'web',

            ])
            ->group(base_path('routes/web/admin.php'));
    }

    /**
     * Определить маршруты «фронтенда» для приложения.
     *
     * @return void
     */
    protected function mapFrontRoutes()
    {
        Route::middleware([
                'web',

            ])
            ->group(base_path('routes/web/front.php'));
    }

    /**
     * Define the "web" routes for the application.
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::namespace('Laravel\Fortify\Http\Controllers')
            ->domain(config('fortify.domain', null))
            ->prefix(config('fortify.prefix'))
            ->group(base_path('routes/fortify.php'));

        Route::middleware([
                'web',

            ])
            ->group(base_path('routes/web.php'));
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
