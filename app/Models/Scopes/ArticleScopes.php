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

    public function scopeFullArticle($query, $article_id)
    {
        return $query
            ->with([
                'categories:categories.id,categories.title,categories.slug',
                'user:users.id,users.name,users.email,users.avatar',
            ])
            ->withCount(['comments', 'tags'])
            ->where('articles.state', 'published')
            ->where('articles.id', $article_id);
    }

    public function scopePublished($query)
    {
        return $query->where('state', 'published');
    }

    public function scopeSearchByKeyword($query, $keyword) // ToDo Add new xfields
    {
        return $query
            ->where('title', 'like', '%' . $keyword . '%')
            ->orWhere('content', 'like', '%' . $keyword . '%');
    }

    public function scopeShortArticle($query, ...$filters)
    {
        return $query
            ->select([
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
            ->withCount(['comments'])
            ->where('articles.state', 'published');
    }
}
