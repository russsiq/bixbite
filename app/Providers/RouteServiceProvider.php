<?php

namespace BBCMS\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'BBCMS\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::pattern('id', '^[0-9]*$');
        Route::pattern('article', '^[\w-0-9]+$');
        Route::pattern('tag', '^[\w-0-9\+%\s]+$');
        Route::pattern('category', '^[\w-0-9\/]+$');
        Route::pattern('commentable_id', '^[0-9]*$');
        Route::pattern('commentable_type', '^[a-zA-Z_]+$');

        /*Для новостей , статей не надо этого. Для пользователей не знаю
        Route::model('article', \BBCMS\Models\Article::class);
        Route::model('category', \BBCMS\Models\Category::class);
        Route::model('users', BBCMS\Models\User::class);*/

        // Backend
        Route::pattern('module', '^[a-z_]+$');
        Route::pattern('module_id', '^[0-9]*$');
        Route::pattern('setting_id', '^[0-9]*$');

        // Common
        Route::pattern('model', '^[a-zA-Z_]+$');
        Route::pattern('attribute', '^[a-zA-Z_]+$');

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapAdminRoutes();  //for the admin web routes
        $this->mapSetupRoutes();  //for the Installer web routes
        $this->mapWebRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware(['api'])
            ->namespace($this->namespace)
            ->group(app()->basePath('routes/api.php'));
    }

    /**
     * Define the admin specific "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::prefix('admin')
            ->middleware(['web', 'auth', 'can:global.admin'])
            ->namespace($this->namespace . '\Admin')
            ->group(app()->basePath('routes/web/admin.php'));
    }

    /**
     * Define the admin specific "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapSetupRoutes()
    {
        Route::prefix('installer')
            ->middleware(['web'])
            ->namespace($this->namespace . '\Setup')
            ->group(app()->basePath('routes/web/setup.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware(['web'])
            ->namespace($this->namespace)
            ->group(app()->basePath('routes/web.php'));
    }
}
