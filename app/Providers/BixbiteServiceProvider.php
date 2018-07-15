<?php

namespace BBCMS\Providers;

use Cache;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

use BBCMS\Support\PageInfo;
use BBCMS\Support\CacheFile;
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
            return $environment == user('role');
        });

        Blade::directive('captcha', function ($expression) {
            return "<?php echo get_captcha($expression); ?>";
        });

        Blade::directive('widget', function ($expression) {
            return "<?php echo app('widget')->make($expression); ?>";
        });
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

        // Only singleton. We only need one copy.
        $this->app->singleton('cachefile', function () {
            return new CacheFile(
                Cache::store('file')->getFilesystem(),
                Cache::store('file')->getDirectory()
            );
        });

        $this->app->bind('widget', function ($app) {
            return new WidgetFactory();
        });
    }
}
