<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

/**
 * Полнейший бардак с регулярными выражениями, т.е. в секции `boot`.
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
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
     *Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();
        $this->configureRoutes();
    }

    protected function configureRoutes()
    {
        // Для новостей , статей не надо этого. Для пользователей не знаю
        // Route::model('article', \App\Models\Article::class);
        // Route::model('category', \App\Models\Category::class);
        // Route::model('users', App\Models\User::class);

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

        $this->routes(function () {
            Route::middleware([])
                ->group(base_path('routes/web/rss.php'));

            Route::prefix('api/v1')
                ->middleware(['api'])
                ->group(base_path('routes/api.php'));

            Route::middleware(['web'])
                ->group(base_path('routes/web/front.php'));

            Route::namespace('Laravel\Fortify\Http\Controllers')
                ->domain(config('fortify.domain', null))
                ->prefix(config('fortify.prefix'))
                ->group(base_path('routes/fortify.php'));

            // Always last.
            Route::middleware(['web'])
                ->group(base_path('routes/web.php'));
        });
    }

    protected function routePattern(string $type): string
    {
        return $this->routePatterns[$type];
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
