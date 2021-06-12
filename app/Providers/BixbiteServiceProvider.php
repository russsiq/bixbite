<?php

namespace App\Providers;

use App\Actions\Article\CreateArticleAction;
use App\Actions\Article\DeleteArticleAction;
use App\Actions\Article\FetchArticleAction;
use App\Actions\Article\MassUpdateArticleAction;
use App\Actions\Article\UpdateArticleAction;
use App\Actions\Attachment\CreateAttachmentAction;
use App\Actions\Attachment\DeleteAttachmentAction;
use App\Actions\Attachment\FetchAttachmentAction;
use App\Actions\Attachment\UpdateAttachmentAction;
use App\Actions\Category\AttachCategoryAction;
use App\Actions\Category\CreateCategoryAction;
use App\Actions\Category\DeleteCategoryAction;
use App\Actions\Category\DetachCategoryAction;
use App\Actions\Category\FetchCategoryAction;
use App\Actions\Category\SyncCategoryAction;
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
use App\Actions\Tag\AttachTagAction;
use App\Actions\Tag\CreateTagAction;
use App\Actions\Tag\DeleteTagAction;
use App\Actions\Tag\DetachTagAction;
use App\Actions\Tag\FetchTagAction;
use App\Actions\Tag\UpdateTagAction;
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
use App\Contracts\Actions\Attachment\CreatesAttachment;
use App\Contracts\Actions\Attachment\DeletesAttachment;
use App\Contracts\Actions\Attachment\FetchesAttachment;
use App\Contracts\Actions\Attachment\UpdatesAttachment;
use App\Contracts\Actions\Category\AttachesCategory;
use App\Contracts\Actions\Category\CreatesCategory;
use App\Contracts\Actions\Category\DeletesCategory;
use App\Contracts\Actions\Category\DetachesCategory;
use App\Contracts\Actions\Category\FetchesCategory;
use App\Contracts\Actions\Category\SyncsCategory;
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
use App\Contracts\Actions\Tag\AttachesTag;
use App\Contracts\Actions\Tag\CreatesTag;
use App\Contracts\Actions\Tag\DeletesTag;
use App\Contracts\Actions\Tag\DetachesTag;
use App\Contracts\Actions\Tag\FetchesTag;
use App\Contracts\Actions\Tag\UpdatesTag;
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
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class BixbiteServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        //
    ];

    /**
     * All of the container singletons that should be registered.
     *
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

        CreatesAttachment::class => CreateAttachmentAction::class,
        DeletesAttachment::class => DeleteAttachmentAction::class,
        FetchesAttachment::class => FetchAttachmentAction::class,
        UpdatesAttachment::class => UpdateAttachmentAction::class,

        AttachesCategory::class => AttachCategoryAction::class,
        CreatesCategory::class => CreateCategoryAction::class,
        DeletesCategory::class => DeleteCategoryAction::class,
        DetachesCategory::class => DetachCategoryAction::class,
        FetchesCategory::class => FetchCategoryAction::class,
        SyncsCategory::class => SyncCategoryAction::class,
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

        AttachesTag::class => AttachTagAction::class,
        CreatesTag::class => CreateTagAction::class,
        DeletesTag::class => DeleteTagAction::class,
        DetachesTag::class => DetachTagAction::class,
        FetchesTag::class => FetchTagAction::class,
        UpdatesTag::class => UpdateTagAction::class,

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
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        \Illuminate\Support\Arr::mixin(new \App\Mixins\ArrMixin);
        \Illuminate\Support\Facades\File::mixin(new \App\Mixins\FileMixin);
        \Illuminate\Support\Facades\Lang::mixin(new \App\Mixins\LangMixin);
        \Illuminate\Support\Str::mixin(new \App\Mixins\StrMixin);

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

        $this->app->booted(function (ApplicationContract $app) {
            $app->call([$this, 'registerModelObservers']);
        });
    }

    /**
     * Register observers with the model.
     *
     * Observers use initialized model instances.
     *
     * @return void
     */
    public function registerModelObservers(): void
    {
        \App\Models\Article::observe(\App\Models\Observers\ArticleObserver::class);
        \App\Models\Category::observe(\App\Models\Observers\CategoryObserver::class);
        \App\Models\Comment::observe(\App\Models\Observers\CommentObserver::class);
        \App\Models\Attachment::observe(\App\Models\Observers\AttachmentObserver::class);
        \App\Models\Note::observe(\App\Models\Observers\NoteObserver::class);
        \App\Models\Privilege::observe(\App\Models\Observers\PrivilegeObserver::class);
        \App\Models\Setting::observe(\App\Models\Observers\SettingObserver::class);
        \App\Models\User::observe(\App\Models\Observers\UserObserver::class);
        \App\Models\XField::observe(\App\Models\Observers\XFieldObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
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
