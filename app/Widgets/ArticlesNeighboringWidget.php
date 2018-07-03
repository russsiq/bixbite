<?php

namespace BBCMS\Widgets;

use BBCMS\Models\Article;
use BBCMS\Support\WidgetAbstract;

class ArticlesNeighboringWidget extends WidgetAbstract
{
    protected $cacheTime = 1440;
    protected $casts = [
        'active' => 'boolean',
        'template' => 'string',
        'cache_time' => 'integer',
        'title' => 'string',
        'current_id' => 'integer',
    ];
    protected $template = 'widgets.articles_neighboring';

    public function __construct(array $params = [])
    {
        // Check current article.
        if (! pageinfo('is_article') or ! pageinfo('article')->id) {
            throw new \Exception('Widget ' . self::class . ' not available.');
        }

        parent::__construct($params);
    }

    protected function default()
    {
        return [
            // Frequent
            'active' => true,
            'template' => $this->template,
            'cache_time' => $this->cacheTime,
            'title' => setting('articles.widget_title', trans('articles.widget_title')),

            'current_id' => pageinfo('article')->id,
        ];
    }

    protected function rules()
    {
        return [
            // Frequent
            'active' => ['required', 'boolean'],
            'template' => ['required', 'string'],
            'cache_time' => ['required', 'integer'],
            'title' => ['required', 'string', 'regex:/^[\w\s\.-_]+$/u'],

            'current_id' => ['required', 'integer'],
        ];
    }

    public function execute()
    {
        $id = $this->params['current_id'];

        $output = Article::select(['articles.id', 'articles.title', 'articles.slug'])
            ->where('articles.state', 'published')
            ->where('articles.id', '<', $id)
            ->orderBy('articles.id', 'desc')
            ->limit(1)
            ->unionAll(
                Article::select(['articles.id', 'articles.title', 'articles.slug'])
                    ->where('articles.state', 'published')
                    ->where('articles.id', '>', $id)
                    ->orderBy('articles.id', 'asc')
                    ->limit(1)
                )
            ->with(['categories:categories.id,categories.title,categories.slug'])
            ->get();

        return $output->count() ? [
                'title' => $this->params['title'],
                'prev' => $output->first()->id < $id ? $output->first() : null,
                'next' => $output->last()->id > $id ? $output->last() : null,
            ] : '';
    }
}
