<?php

namespace App\Providers;

// Зарегистрированные фасады приложения.
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;

// Сторонние зависимости.
use App\Support\PageInfo;
use App\Support\CacheFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
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
        \App\Models\Article::observe(\App\Models\Observers\ArticleObserver::class);
        \App\Models\Category::observe(\App\Models\Observers\CategoryObserver::class);
        \App\Models\Comment::observe(\App\Models\Observers\CommentObserver::class);
        \App\Models\File::observe(\App\Models\Observers\FileObserver::class);
        \App\Models\Note::observe(\App\Models\Observers\NoteObserver::class);
        \App\Models\Privilege::observe(\App\Models\Observers\PrivilegeObserver::class);
        \App\Models\Setting::observe(\App\Models\Observers\SettingObserver::class);
        \App\Models\User::observe(\App\Models\Observers\UserObserver::class);
        \App\Models\XField::observe(\App\Models\Observers\XFieldObserver::class);

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

        // Удалить пустые значения массива и объединить их разделителем.
        Arr::macro('cluster', function (array $array = null, string $delimiter = ' – '): string {
            if (is_null($array)) {
                return '';
            }

            return join($delimiter, array_values(array_filter($array)));
        });

        // Shows the size of a file in human readable format in bytes to kb, mb, gb, tb.
        Str::macro('humanFilesize', function (int $size, int $precision = 2): string {
            $suffixes = [
                trans('common.bytes'),
                trans('common.KB'),
                trans('common.MB'),
                trans('common.GB'),
                trans('common.TB'),
            ];

            for ($i = 0; $size > 1024; $i++) {
                $size /= 1024;
            }

            return round($size, $precision).' '.$suffixes[$i];
        });

        Filesystem::macro('humanSize', function (string $path, int $precision = 2): string {
            return Str::humanFilesize($this->size($path, $precision));
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
