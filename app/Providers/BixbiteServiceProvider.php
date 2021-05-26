<?php

namespace App\Providers;

use App\Actions\Article\CreateArticleAction;
use App\Actions\Article\DeleteArticleAction;
use App\Actions\Article\FetchArticleAction;
use App\Actions\Article\MassUpdateArticleAction;
use App\Actions\Article\UpdateArticleAction;
use App\Actions\Category\CreateCategoryAction;
use App\Actions\Category\DeleteCategoryAction;
use App\Actions\Category\FetchCategoryAction;
use App\Actions\Category\UpdateCategoryAction;
use App\Actions\Comment\CreateCommentAction;
use App\Actions\Comment\DeleteCommentAction;
use App\Actions\Comment\FetchCommentAction;
use App\Actions\Comment\MassUpdateCommentAction;
use App\Actions\Comment\UpdateCommentAction;
use App\Actions\Note\CreateNoteAction;
use App\Actions\Note\DeleteNoteAction;
use App\Actions\Note\FetchNoteAction;
use App\Actions\Note\UpdateNoteAction;
use App\Actions\User\DeleteUserAction;
use App\Actions\User\UpdateUserPasswordAction;
use App\Actions\User\UpdateUserProfileInformationAction;
use App\Actions\XField\CreateXFieldAction;
use App\Actions\XField\DeleteXFieldAction;
use App\Actions\XField\FetchXFieldAction;
use App\Actions\XField\UpdateXFieldAction;
use App\Contracts\Actions\Article\CreatesArticle;
use App\Contracts\Actions\Article\DeletesArticle;
use App\Contracts\Actions\Article\FetchesArticle;
use App\Contracts\Actions\Article\MassUpdatesArticle;
use App\Contracts\Actions\Article\UpdatesArticle;
use App\Contracts\Actions\Category\CreatesCategory;
use App\Contracts\Actions\Category\DeletesCategory;
use App\Contracts\Actions\Category\FetchesCategory;
use App\Contracts\Actions\Category\UpdatesCategory;
use App\Contracts\Actions\Comment\CreatesComment;
use App\Contracts\Actions\Comment\DeletesComment;
use App\Contracts\Actions\Comment\FetchesComment;
use App\Contracts\Actions\Comment\MassUpdatesComment;
use App\Contracts\Actions\Comment\UpdatesComment;
use App\Contracts\Actions\Note\CreatesNote;
use App\Contracts\Actions\Note\DeletesNote;
use App\Contracts\Actions\Note\FetchesNote;
use App\Contracts\Actions\Note\UpdatesNote;
use App\Contracts\Actions\User\DeletesUsers;
use App\Contracts\Actions\User\UpdatesUserPasswords;
use App\Contracts\Actions\User\UpdatesUserProfileInformation;
use App\Contracts\Actions\XField\CreatesXField;
use App\Contracts\Actions\XField\DeletesXField;
use App\Contracts\Actions\XField\FetchesXField;
use App\Contracts\Actions\XField\UpdatesXField;
use App\Contracts\BixBiteContract;
use App\Contracts\Responses\SuccessfulCommentCreateResponseContract;
use App\Http\Responses\SuccessfulCommentCreateResponse;
use App\Support\BixBite;
use App\Support\CacheFile;
use App\Support\PageInfo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

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

        CreatesCategory::class => CreateCategoryAction::class,
        DeletesCategory::class => DeleteCategoryAction::class,
        FetchesCategory::class => FetchCategoryAction::class,
        UpdatesCategory::class => UpdateCategoryAction::class,

        CreatesComment::class => CreateCommentAction::class,
        DeletesComment::class => DeleteCommentAction::class,
        FetchesComment::class => FetchCommentAction::class,
        UpdatesComment::class => UpdateCommentAction::class,
        MassUpdatesComment::class => MassUpdateCommentAction::class,

        CreatesNote::class => CreateNoteAction::class,
        DeletesNote::class => DeleteNoteAction::class,
        FetchesNote::class => FetchNoteAction::class,
        UpdatesNote::class => UpdateNoteAction::class,

        UpdatesUserProfileInformation::class => UpdateUserProfileInformationAction::class,
        UpdatesUserPasswords::class => UpdateUserPasswordAction::class,
        DeletesUsers::class => DeleteUserAction::class,

        CreatesXField::class => CreateXFieldAction::class,
        DeletesXField::class => DeleteXFieldAction::class,
        FetchesXField::class => FetchXFieldAction::class,
        UpdatesXField::class => UpdateXFieldAction::class,

        // BixBite Responses ...
        SuccessfulCommentCreateResponseContract::class => SuccessfulCommentCreateResponse::class,
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
        \Illuminate\Support\Arr::mixin(new \App\Mixins\ArrMixin);
        \Illuminate\Support\Facades\File::mixin(new \App\Mixins\FileMixin);
        \Illuminate\Support\Facades\Lang::mixin(new \App\Mixins\LangMixin);
        \Illuminate\Support\Str::mixin(new \App\Mixins\StrMixin);

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
            \App\Models\Article::TABLE => \App\Models\Article::class,
            \App\Models\Category::TABLE => \App\Models\Category::class,
            \App\Models\Comment::TABLE => \App\Models\Comment::class,
            \App\Models\Attachment::TABLE => \App\Models\Attachment::class,
            \App\Models\Note::TABLE => \App\Models\Note::class,
            \App\Models\Tag::TABLE => \App\Models\Tag::class,
            \App\Models\User::TABLE => \App\Models\User::class,
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
