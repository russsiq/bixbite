<?php

namespace App\Providers;

use App\Actions\Article\CreateArticleAction;
use App\Actions\Article\DeleteArticleAction;
use App\Actions\Article\FetchArticleAction;
use App\Actions\Article\MassUpdateArticleAction;
use App\Actions\Article\UpdateArticleAction;
use App\Actions\User\DeleteUserAction;
use App\Actions\User\UpdateUserPasswordAction;
use App\Actions\User\UpdateUserProfileInformationAction;
use App\Contracts\Actions\Article\CreatesArticle;
use App\Contracts\Actions\Article\DeletesArticle;
use App\Contracts\Actions\Article\FetchesArticle;
use App\Contracts\Actions\Article\MassUpdatesArticle;
use App\Contracts\Actions\Article\UpdatesArticle;
use App\Contracts\Actions\User\DeletesUsers;
use App\Contracts\Actions\User\UpdatesUserPasswords;
use App\Contracts\Actions\User\UpdatesUserProfileInformation;
use App\Contracts\BixBiteContract;
use App\Mixins\ArrMixin;
use App\Mixins\FileMixin;
use App\Mixins\LangMixin;
use App\Mixins\StrMixin;
use App\Support\BixBite;
use App\Support\CacheFile;
use App\Support\PageInfo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class BixbiteServiceProvider extends ServiceProvider
{
    /**
     * Все синглтоны (одиночки) контейнера,
     * которые должны быть зарегистрированы.
     * @var array
     */
    public $singletons = [
        'pageinfo' => PageInfo::class,
        BixBiteContract::class => BixBite::class,

        // BixBite Actions ...
        CreatesArticle::class => CreateArticleAction::class,
        DeletesArticle::class => DeleteArticleAction::class,
        FetchesArticle::class => FetchArticleAction::class,
        UpdatesArticle::class => UpdateArticleAction::class,
        MassUpdatesArticle::class => MassUpdateArticleAction::class,

        UpdatesUserProfileInformation::class => UpdateUserProfileInformationAction::class,
        UpdatesUserPasswords::class => UpdateUserPasswordAction::class,
        DeletesUsers::class => DeleteUserAction::class,
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
        \App\Models\Attachment::observe(\App\Models\Observers\AttachmentObserver::class);
        \App\Models\Note::observe(\App\Models\Observers\NoteObserver::class);
        \App\Models\Privilege::observe(\App\Models\Observers\PrivilegeObserver::class);
        \App\Models\Setting::observe(\App\Models\Observers\SettingObserver::class);
        \App\Models\User::observe(\App\Models\Observers\UserObserver::class);
        \App\Models\XField::observe(\App\Models\Observers\XFieldObserver::class);

        Relation::morphMap([
            'articles' => \App\Models\Article::class,
            'categories' => \App\Models\Category::class,
            'comments' => \App\Models\Comment::class,
            'attachments' => \App\Models\Attachment::class,
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
