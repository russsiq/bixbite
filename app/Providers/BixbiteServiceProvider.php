<?php

namespace BBCMS\Providers;

// use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
// use Illuminate\Support\Facades\Schema;
// use Illuminate\Support\Facades\Validator;

// use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Relations\Relation;

use BBCMS\Support\PageInfo;
use BBCMS\Support\Factories\WidgetFactory;

class BixbiteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'articles' => \BBCMS\Models\Article::class,
            'categories' => \BBCMS\Models\Category::class,
            'comments' => \BBCMS\Models\Comment::class,
            'files' => \BBCMS\Models\File::class,
            'images' => \BBCMS\Models\Image::class,
            'notes' => \BBCMS\Models\Note::class,
            'tags' => \BBCMS\Models\Tag::class,
            'users' => \BBCMS\Models\User::class,
        ], true);

        Blade::if('setting', function (string $environment) {
            return setting($environment);
        });

        Blade::if('role', function (string $environment) {
            return (
                auth()->check() and auth()->user()->hasRole($environment)
            ) ? true : false;
        });

        Blade::directive('captcha', function ($expression) {
            return "<?php echo get_captcha($expression); ?>";
        });

        Blade::directive('widget', function ($expression) {
            return "<?php echo app('widget')->make($expression); ?>";
        });

        // Pagination query string append.
        // NOT Worked, see to $elements in pagination template.
        // View::composer('components.pagination', function($view) {
        //     $view->paginator->appends(request()->except(['page']));
        // });

        // NOT CHECKED, TO DO. Для капчи это не надо.
        // 2018-06-21 А почему не надо?
        // Auth не проверяется. А почему не надо?
        // Validator::extend('captcha', function ($attribute, $value, $parameters, $validator) {
        //     if (!auth()->check() and setting('system.captcha_used', true)) {
        //         if (md5($this->captcha) != session('captcha')) {
        //             $validator->errors()->add('captcha', __('validation.captcha'));
        //         }
        //     }
        //     return $value == 'foo';
        // });

        // Schema::defaultStringLength(191); //Solved by increasing StringLength
        // Blade::component('components.alert', 'alert');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Only singleton. We only need one copy.
        $this->app->singleton('pageinfo', function () {
            return new PageInfo();
        });

        $this->app->bind('widget', function ($app) {
            return new WidgetFactory();
        });
    }
}
