<?php

namespace BBCMS\Providers;

// Зарегистрированные фасады приложения.
use Blade;
use Cache;

// Сторонние зависимости.
use BBCMS\Support\PageInfo;
use BBCMS\Support\CacheFile;
use BBCMS\Support\Factories\WidgetFactory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class BixbiteServiceProvider extends ServiceProvider
{
    /**
     * Все синглтоны (одиночки) контейнера,
     * которые должны быть зарегистрированы.
     * @var array
     */
    public $singletons = [
        'pageinfo' => PageInfo::class,

    ];

    /**
     * Все связывания контейнера,
     * которые должны быть зарегистрированы.
     * @var array
     */
    public $bindings = [
        'widget' => WidgetFactory::class,

    ];

    /**
     * Загрузка любых служб приложения.
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'articles' => \BBCMS\Models\Article::class,
            'categories' => \BBCMS\Models\Category::class,
            'comments' => \BBCMS\Models\Comment::class,
            'files' => \BBCMS\Models\File::class,
            // 'images' => \BBCMS\Models\Image::class,
            'notes' => \BBCMS\Models\Note::class,
            'tags' => \BBCMS\Models\Tag::class,
            'users' => \BBCMS\Models\User::class,
        ], true);

        Blade::if('setting', function (string $environment) {
            return setting($environment);
        });

        Blade::if('role', function (string $environment) {
            return $environment === user('role');
        });

        Blade::directive('captcha', function ($expression) {
            return "<?php echo get_captcha($expression); ?>";
        });

        Blade::directive('widget', function ($expression) {
            return "<?php echo app('widget')->make($expression); ?>";
        });
    }

    /**
     * Регистрация любых служб приложения.
     * @return void
     */
    public function register()
    {
        // Only singleton. We only need one copy.
        $this->app->singleton('cachefile', function () {
            return new CacheFile(
                Cache::store('file')->getFilesystem(),
                Cache::store('file')->getDirectory()
            );
        });
    }
}
