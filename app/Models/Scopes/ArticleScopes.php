<?php

namespace App\Models\Scopes;

use App\Models\Article;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * @method static static|Article cachedFullArticleWithRelation(int $id) Scope a query to retrive cached single article with relation.
 * @method static static|EloquentBuilder|QueryBuilder drafts() Scope a query to include only drafts articles.
 * @method static static|EloquentBuilder|QueryBuilder favorites() Scope a query to include only favorite articles.
 * @method static static|EloquentBuilder|QueryBuilder filter(array $filters = []) Filter articles by frequently used criteria.
 * @method static static|EloquentBuilder|QueryBuilder fullArticle(int $id) Scope a query to include only single full article by ID.
 * @method static static|EloquentBuilder|QueryBuilder published() Scope a query to include only published articles.
 * @method static static|EloquentBuilder|QueryBuilder searchByKeyword(?string $keyword) Scope a query to include only relevant by keywords articles.
 * @method static static|EloquentBuilder|QueryBuilder shortArticle() Scope a query to include only short articles.
 * @method static static|EloquentBuilder|QueryBuilder unPublished() Scope a query to include only unpublished articles.
 * @method static static|EloquentBuilder|QueryBuilder visibleOnMainpage(bool $isVisible = true) Scope a query to include only visible / invisible on the main page articles.
 */
trait ArticleScopes
{
    /**
     * Scope a query to retrive cached single article with relation.
     *
     * @param  EloquentBuilder  $builder
     * @param  int  $id
     * @return Article
     */
    public function scopeCachedFullArticleWithRelation(EloquentBuilder $builder, int $id): Article
    {
        /** @var Article */
        $article = cache()->remember('articles-single-'.$id, $this->setting->cache_used(1440) * 60, function () use ($id) {
            return $this->fullArticle($id)->firstOrFail();
        });

        // Комментарии ни в коем случае не кэшируем.
        $article->comments = $article->comments_count ? $article->getComments(setting('comments.nested', true)) : [];

        // Если в настройках указано вести подсчет количества просмотров записи.
        if ($this->setting->views_used) {
            $article->timestamps = false;
            $article->increment('views');
            $article->timestamps = true;
        }

        return $article;
    }

    /**
     * Scope a query to include only drafts articles.
     *
     * @param  EloquentBuilder  $builder
     * @return EloquentBuilder
     */
    public function scopeDrafts(EloquentBuilder $builder): EloquentBuilder
    {
        return $builder->where('articles.state', '=', Article::STATE['draft']);
    }

    /**
     * Scope a query to include only favorite articles.
     *
     * @param  EloquentBuilder  $builder
     * @return EloquentBuilder
     */
    public function scopeFavorites(EloquentBuilder $builder): EloquentBuilder
    {
        return $builder->where('is_favorite', '=', true);
    }

    /**
     * Filter articles by frequently used criteria.
     *
     * @param  EloquentBuilder  $builder
     * @param  array  $filters
     * @return EloquentBuilder
     */
    public function scopeFilter(EloquentBuilder $builder, array $filters = []): EloquentBuilder
    {
        return $builder->when($filters['month'], function (EloquentBuilder $builder, string $month) {
            return $builder->addSelect('articles.created_at')
                ->whereMonth('created_at', Carbon::parse($month)->month);
        })
            ->when($filters['year'], function (EloquentBuilder $builder, int $year) {
                return $builder->addSelect('articles.created_at')
                    ->whereYear('created_at', $year);
            })
            ->when($filters['user_id'], function (EloquentBuilder $builder, int $user_id) {
                return $builder->addSelect('articles.user_id')
                    ->where('user_id', '=', $user_id);
            });
    }

    /**
     * Scope a query to include only single full article by ID.
     *
     * @param  EloquentBuilder  $builder
     * @param  int  $id
     * @return EloquentBuilder
     */
    public function scopeFullArticle(EloquentBuilder $builder, int $id): EloquentBuilder
    {
        return $builder->with([
                // 'categories:categories.id,categories.title,categories.slug',
                'user:users.id,users.name,users.email,users.avatar',
                'attachments',
                'tags:tags.id,tags.title,tags.slug'
            ])
            ->withCount([
                'comments'
            ])
            ->where('articles.id', $id)
            ->published();
    }

    /**
     * Scope a query to include only published articles.
     *
     * @param  EloquentBuilder  $builder
     * @return EloquentBuilder
     */
    public function scopePublished(EloquentBuilder $builder): EloquentBuilder
    {
        // if (! $builder->getQuery()->distinct) {
        //     $builder->addSelect('articles.state');
        // }

        return $builder->where('articles.state', '=', Article::STATE['published']);
    }

    /**
     * Scope a query to include only relevant by keywords articles.
     *
     * @param  EloquentBuilder  $builder
     * @param  string|null  $keyword
     * @return EloquentBuilder
     *
     * @todo Add search for additional fields.
     */
    public function scopeSearchByKeyword(EloquentBuilder $builder, ?string $keyword): EloquentBuilder
    {
        return $builder->when($keyword, function(EloquentBuilder $builder, string $keyword) {
            return $builder->where('title', 'like', '%' . $keyword . '%')
                ->orWhere('content', 'like', '%' . $keyword . '%');
        });
    }

    /**
     * Scope a query to include only short articles.
     *
     * @param  EloquentBuilder  $builder
     * @return EloquentBuilder
     */
    public function scopeShortArticle(EloquentBuilder $builder): EloquentBuilder
    {
        return $builder->addSelect([
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
            ->includeExtensibleAttributes()
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

    /**
     * Scope a query to include only unpublished articles.
     *
     * @param  EloquentBuilder  $builder
     * @return EloquentBuilder
     */
    public function scopeUnPublished(EloquentBuilder $builder): EloquentBuilder
    {
        return $builder->where('articles.state', '=', Article::STATE['unpublished']);
    }

    /**
     * Scope a query to include only visible / invisible on the main page articles.
     *
     * @param  EloquentBuilder  $builder
     * @param  bool  $isVisible
     * @return EloquentBuilder
     */
    public function scopeVisibleOnMainpage(EloquentBuilder $builder, bool $isVisible = true): EloquentBuilder
    {
        return $builder->where('articles.on_mainpage', '=', $isVisible);
    }
}
