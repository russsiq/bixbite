<?php

namespace BBCMS\Models\Scopes;

use Carbon\Carbon;

use BBCMS\Models\XField;

trait ArticleScopes
{
    // Filter
    public function scopeFilter($query, $filters)
    {
        $query->when($filters['month'] ?? false, function($query) use ($filters) {
            $query->whereMonth('created_at', Carbon::parse($filters['month'])->month);
        })
        ->when($filters['year'] ?? false, function($query) use ($filters) {
            $query->whereYear('created_at', $filters['year']);
        })
        ->when($filters['user_id'] ?? false, function($query) use ($filters) {
            $query->where('user_id', (int) $filters['user_id']);
        });
    }

    public function scopeFullArticle($query, $id)
    {
        return $query->with([
                'categories:categories.id,categories.title,categories.slug',
                'user:users.id,users.name,users.email,users.avatar',
            ])
            ->withCount([
                'comments',
                'tags',
            ])
            ->where('articles.id', $id)
            ->published()
            ->firstOrFail();
    }

    public function scopeCachedFullArticleWithRelation($query, $id)
    {
        $article = cache()->remember('cachedFullArticleWithRelation-'.$id, setting('articles.cache_used', 1440), function () use ($id) {
            $article = $this->fullArticle($id);
            $article->tags = $article->tags_count ? $article->getTags() : [];
            $article->image = $article->image_id ? $article->image()->first() : null;

            return $article;
        });

        $article->comments = $article->comments_count ? $article->getComments(setting('comments.nested', true)) : [];

        if (setting('articles.views_used', false)) {
            $article->increment('views');
        };

        return $article;
    }

    public function scopePublished($query)
    {
        return $query->where('articles.state', 'published');
    }

    public function scopeSearchByKeyword($query, $keyword) // ToDo Add new xfields
    {
        return $query->where('title', 'like', '%' . $keyword . '%')
            ->orWhere('content', 'like', '%' . $keyword . '%');
    }

    public function scopeShortArticle($query, ...$filters)
    {
        return $query->select([
                'articles.id','articles.user_id','articles.image_id',
                'articles.slug','articles.title','articles.teaser',
                'articles.created_at','articles.updated_at',
                'articles.views',
            ])
            ->addSelect(
                XField::fields()->where('extensible', $this->getTable())->pluck('name')->all()
            )
            ->with([
                'categories:categories.id,categories.slug,categories.title',
                'user:users.id,users.name', // users.email,users.avatar',
                'image',
            ])
            ->withCount([
                'comments',
            ])
            ->published();
    }
}
