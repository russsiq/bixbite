<?php

namespace App\Providers;

// Зарегистрированные фасады приложения.
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;

// Сторонние зависимости.
use App\Mixins\ArrMixin;
use App\Mixins\FileMixin;
use App\Mixins\LangMixin;
use App\Mixins\StrMixin;
use App\Support\PageInfo;
use App\Support\CacheFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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
        Arr::mixin(new ArrMixin);
        File::mixin(new FileMixin);
        Lang::mixin(new LangMixin);
        Str::mixin(new StrMixin);

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
