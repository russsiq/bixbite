<?php

namespace BBCMS\Widgets;

use BBCMS\Models\Article;
use BBCMS\Support\WidgetAbstract;

class ArticlesArchivesWidget extends WidgetAbstract
{
    /**
     * Время кэширования виджета. Раз в 30 дней.
     * @var integer
     */
    protected $cacheTime = 30 * 24 * 60;

    protected $casts = [
        'active' => 'boolean',
        'template' => 'string',
        'cache_time' => 'integer',
        'title' => 'string',
        'limit' => 'integer',
    ];
    protected $template = 'widgets.articles_archives';

    protected function rules()
    {
        return [
            // Frequent
            'active' => ['required', 'boolean'],
            'template' => ['required', 'string'],
            'cache_time' => ['required', 'integer'],
            'title' => ['required', 'string', 'regex:/^[\w\s\.-_]+$/u'],
            'limit' => ['required', 'integer'],
        ];
    }

    protected function default()
    {
        return [
            // Frequent
            'active' => true,
            'template' => $this->template,
            'cache_time' => $this->cacheTime,
            'title' => setting('articles.archives.widget_title', trans('articles.archives')),
            'limit' => setting('articles.archives.limit', 6),
        ];
    }

    public function execute()
    {
        return [
            'title' => $this->params['title'],
            'items' => Article::without('categories')
                ->selectRaw('
                    year(created_at) year,
                    monthname(created_at) month,
                    count(*) as count
                ')
                ->published()
                ->groupBy('year', 'month')
                ->orderByRaw('min(created_at) desc')
                ->limit($this->params['limit'])
                ->get()
                ->transform(function ($item, $key) {
                    $item->monthname = __('common.'.$item->month);

                    return $item;
                }),
        ];
    }
}
