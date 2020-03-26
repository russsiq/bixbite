<?php

namespace App\Providers;

// Зарегистрированные фасады приложения.
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;

// Сторонние зависимости.
use App\Support\PageInfo;
use App\Support\CacheFile;
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

    ];

    /**
     * Загрузка любых служб приложения.
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'articles' => \App\Models\Article::class,
            'categories' => \App\Models\Category::class,
            'comments' => \App\Models\Comment::class,
            'files' => \App\Models\File::class,
            'notes' => \App\Models\Note::class,
            'tags' => \App\Models\Tag::class,
            'users' => \App\Models\User::class,
        ], true);

        Blade::if('setting', function (string $environment) {
            return setting($environment);
        });

        Blade::if('role', function (string $environment) {
            return $environment === user('role');
        });

        // Создаем макрос перезагрузки `json` файлов переводов.
        // Когда стронние пакеты используют помощник `trans` в своих поставщиках,
        // то метод `addJsonPath` не отрабатывает ожидаемым образом,
        // т.к. метод `load` считает, что все уже загружено:
        // https://github.com/laravel/framework/blob/6.x/src/Illuminate/Translation/Translator.php#L271
        // Пакет на котором был отслежен данный факт:
        // https://github.com/russsiq/laravel-grecaptcha/blob/master/src/app/GRecaptchaServiceProvider.php#L49
        Lang::macro('reloadJsonPaths', function ($namespace, $group, $locale) {
            $this->loaded[$namespace][$group][$locale] = array_merge(
                $this->loaded[$namespace][$group][$locale] ?? [],
                $this->loader->load($locale, $group, $namespace)
            );
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
