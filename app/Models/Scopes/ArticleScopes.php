<?php

namespace App\Models\Scopes;

// Сторонние зависимости.
use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait ArticleScopes
{
    /**
     * Фильтрация записей по часто используемым критериям.
     * @param  Builder  $query
     * @param  array  $filters
     * @return void
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['month'], function(Builder $query, string $month) {
            $query->whereMonth('created_at', Carbon::parse($month)->month);
        })
        ->when($filters['year'], function(Builder $query, int $year) {
            $query->whereYear('created_at', $year);
        })
        ->when($filters['user_id'], function(Builder $query, int $user_id) {
            $query->where('user_id', $user_id);
        });
    }

    /**
     * [scopeFullArticle description]
     * @param  Builder  $query
     * @param  array  $filters
     * @return void
     */
    public function scopeFullArticle(Builder $query, int $id): void
    {
        $query->with([
                // 'categories:categories.id,categories.title,categories.slug',
                'user:users.id,users.name,users.email,users.avatar',
                'files',
            ])
            ->withCount([
                'comments',
                'tags',
            ])
            ->where('articles.id', $id)
            ->published();
    }

    /**
     * [scopeCachedFullArticleWithRelation description]
     * @param  Builder  $query
     * @param  integer  $id
     * @return Article
     */
    public function scopeCachedFullArticleWithRelation(Builder $query, int $id): Article
    {
        $article = cache()->remember('articles-single-'.$id, setting('articles.cache_used', 1440) * 60, function () use ($id) {
            $article = $this->fullArticle($id)->firstOrFail();
            $article->tags = $article->tags_count ? $article->getTags() : [];

            return $article;
        });

        // Комментарии ни в коем случае не кэшируем.
        $article->comments = $article->comments_count ? $article->getComments(setting('comments.nested', true)) : [];

        // Если в настройках указано вести подсчет количества просмотров записи.
        if (setting('articles.views_used', false)) {
            $article->increment('views');
        }

        return $article;
    }

    /**
     * [scopePublished description]
     * @param  Builder  $query
     * @return void
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('articles.state', 'published');
    }

    /**
     * [scopeSearchByKeyword description]
     * @param  Builder  $query
     * @param  string|null  $keyword
     * @return void
     */
    public function scopeSearchByKeyword(Builder $query, ?string $keyword): void // ToDo Add new xfields
    {
        $query->when($keyword, function(Builder $query, string $keyword) {
            $query->where('title', 'like', '%' . $keyword . '%')
                ->orWhere('content', 'like', '%' . $keyword . '%');
        });
    }

    /**
     * [scopeShortArticle description]
     * @param  Builder  $query
     * @return void
     */
    public function scopeShortArticle(Builder $query): void
    {
        $query->select([
                'articles.id','articles.user_id','articles.image_id',
                'articles.slug','articles.title','articles.teaser',
                'articles.created_at','articles.updated_at',
                'articles.views', 'articles.state',
            ])
            ->addSelect(
                $this->x_fields->pluck('name')->all()
            )
            ->with([
                // 'categories:categories.id,categories.slug,categories.title',
                'user:users.id,users.name', // users.email,users.avatar',
                'files' => function ($query) {
                    $query->select([
                        'files.id', 'files.disk', 'files.type',
                        'files.category', 'files.name', 'files.extension',
                        'files.title',
                        'files.attachment_type', 'files.attachment_id'
                    ]);
                },
            ])
            ->withCount([
                'comments',
            ])
            ->published();
    }
}
