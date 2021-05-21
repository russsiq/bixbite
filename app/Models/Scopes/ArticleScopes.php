<?php

namespace App\Models\Scopes;

// Сторонние зависимости.
use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait ArticleScopes
{


    /**
     * [scopeFullArticle description]
     * @param  Builder  $builder
     * @param  array  $filters
     * @return void
     */
    public function scopeFullArticle(Builder $builder, int $id): void
    {
        $builder->with([
                // 'categories:categories.id,categories.title,categories.slug',
                'user:users.id,users.name,users.email,users.avatar',
                'attachments',
                'tags:id,title,slug'
            ])
            ->withCount([
                'comments'
            ])
            ->where('articles.id', $id)
            ->published();
    }

    /**
     * [scopeCachedFullArticleWithRelation description]
     * @param  Builder  $builder
     * @param  int  $id
     * @return Article
     */
    public function scopeCachedFullArticleWithRelation(Builder $builder, int $id): Article
    {
        /** @var Article */
        $article = cache()->remember('articles-single-'.$id, setting('articles.cache_used', 1440) * 60, function () use ($id) {
            return $this->fullArticle($id)->firstOrFail();
        });

        // Комментарии ни в коем случае не кэшируем.
        $article->comments = $article->comments_count ? $article->getComments(setting('comments.nested', true)) : [];

        // Если в настройках указано вести подсчет количества просмотров записи.
        $article->timestamps = false;
        $article->when(setting('articles.views_used', false), function ($builder) {
            $builder->increment('views');
        });
        $article->timestamps = true;

        return $article;
    }

    /**
     * [scopeSearchByKeyword description]
     * @param  Builder  $builder
     * @param  string|null  $keyword
     * @return void
     */
    public function scopeSearchByKeyword(Builder $builder, ?string $keyword): void // ToDo Add new xfields
    {
        $builder->when($keyword, function(Builder $builder, string $keyword) {
            $builder->where('title', 'like', '%' . $keyword . '%')
                ->orWhere('content', 'like', '%' . $keyword . '%');
        });
    }

    /**
     * [scopeShortArticle description]
     * @param  Builder  $builder
     * @return void
     */
    public function scopeShortArticle(Builder $builder): void
    {
        $builder->addSelect([
                'articles.id',
                'articles.user_id',
                'articles.image_id',
                'articles.slug',
                'articles.title',
                'articles.teaser',
                'articles.created_at',
                'articles.updated_at',
                'articles.views',
                'articles.state',

            ])
            ->includeXFieldsNames()
            ->with([
                // 'categories:categories.id,categories.slug,categories.title',
                'user:users.id,users.name', // users.email,users.avatar',
                'attachments' => function ($query) {
                    $query->addSelect([
                        'attachments.id',
                        'attachments.attachable_type',
                        'attachments.attachable_id',
                        'attachments.title',
                        'attachments.disk',
                        'attachments.folder',
                        'attachments.type',
                        'attachments.name',
                        'attachments.extension',
                    ]);
                },
            ])
            ->withCount([
                'comments',

            ]);
    }
}
