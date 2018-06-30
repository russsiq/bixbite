<?php

namespace BBCMS\Widgets;

use BBCMS\Models\Article;
use BBCMS\Support\WidgetAbstract;

class ArticlesRelatedWidget extends WidgetAbstract
{
    protected $cacheTime = 1440;
    protected $casts = [
        'active' => 'boolean',
        'template' => 'string',
        'cache_time' => 'integer',
        'title' => 'string',
        'limit' => 'integer',
        'current_id' => 'integer',
    ];
    protected $template = 'widgets.articles_related';

    public function __construct(array $params = [])
    {
        // Check current article.
        if (! pageinfo('is_article') or ! pageinfo('article')->id) {
            throw new \Exception('Widget ' . self::class . ' not available.');
        }

        parent::__construct($params);
    }

    protected function rules()
    {
        return [
            // Frequent
            'active' => ['required', 'boolean'],
            'template' => ['required', 'string'],
            'cache_time' => ['required', 'integer'],
            'title' => ['required', 'string', 'regex:/^[\w\s\.-_]+$/u'],
            'limit' => ['required', 'integer'],

            'current_id' => ['required', 'integer'],
        ];
    }

    protected function default()
    {
        return [
            // Frequent
            'active' => true,
            'template' => $this->template,
            'cache_time' => $this->cacheTime,
            'title' => setting('articles.related.widget_title', trans('articles.related')),
            'limit' => setting('articles.related.limit', 6),

            'current_id' => pageinfo('article')->id,
        ];
    }

    public function execute()
    {
        $article = pageinfo('article');

        $query = Article::select([
                'articles.id','articles.image_id','articles.title','articles.content','articles.slug', 'articles.created_at', 'articles.updated_at', 'articles.views'
            ])
            ->with([
                'categories:categories.id,categories.slug',
                'image'
            ])
            ->withCount('comments')
            ->where('articles.state', 'published')
            ->where('articles.id', '<>', $article->id);

        if (empty($article->tags) or 1) {
            $related_content = $article->title . ' ' . teaser($article->content, 500);
            $query = $query
                ->selectRaw('MATCH (title, content) AGAINST (? in boolean mode) as REL', [$related_content])
                ->whereRaw('MATCH (title, content) AGAINST (? in boolean mode)' , [$related_content])
                ->orderBy('REL', 'desc');
        } else {
            $this->tags_id = $article->tags->pluck('id');
            $query = $query
                ->when(count($this->tags_id), function ($query) {
                    $query->whereHas('tags', function ($query) {
                        $query->whereIn('tags.id', $this->tags_id);
                    });
                })
                ->groupBy('articles.id','articles.image_id','articles.title','articles.content','articles.slug', 'articles.created_at', 'articles.updated_at', 'articles.views');
        }

        return [
            'title' => $this->params['title'],
            'items' => $query->limit(3)->get()
        ];
    }
}
