<?php

namespace BBCMS\Widgets;

use BBCMS\Models\Article;
use BBCMS\Support\WidgetAbstract;

class ArticlesFeaturedWidget extends WidgetAbstract
{
    protected $cacheTime = 1440;
    protected $casts = [
        'active' => 'boolean',
        'template' => 'string',
        'cache_time' => 'integer',
        'title' => 'string',

        'sub_day' => 'integer',

        'order_by' => 'string',
        'direction' => 'string',
        'limit' => 'integer',
        'skip' => 'integer',

        'state' => 'string',
        'on_mainpage' => 'boolean',
        'is_favorite' => 'boolean',
        'is_pinned' => 'boolean',
        'is_catpinned' => 'boolean',
    ];
    protected $template = 'widgets.articles_featured';

    protected function default()
    {
        return [
            // Frequent
            'active' => true,
            'template' => $this->template,
            'cache_time' => $this->cacheTime,
            'title' => setting('articles.widget_title', trans('articles.widget_title')),

            // Filter
            'id' => [],
            'user_id' => [],
            'tags' => [],
            'categories' => [],
            'sub_day' => 0,

            // Order
            'order_by' => 'views',
            'direction' => 'desc',
            'limit' => setting('articles.widget_limit', 8),
            'skip' => 0,

            // Type
            'state' => 'published',
            'on_mainpage' => 1,
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

            // Filter
            'id' => ['sometimes', 'array'],
            'id.*' => ['integer'],
            'user_id' => ['sometimes', 'array'],
            'user_id.*' => ['integer'],
            'tags' => ['sometimes', 'nullable', 'array'],
            'tags.*' => ['string', 'regex:/^[\w\s-_]+$/u', ],
            'categories' => ['sometimes', 'array'],
            'categories.*' => ['integer'],
            'sub_day' => ['sometimes', 'integer'],

            // Order
            'order_by' => ['required', 'string', 'in:id,title,created_at,updated_at,votes,rating,views,comments_count'],
            'direction' => ['required', 'string', 'in:desc,asc'],
            'limit' => ['required', 'integer'],
            'skip' => ['sometimes', 'integer'],

            // Type
            'state' => ['required', 'string', 'in:draft,unpublished,published'],
            'on_mainpage' => ['required', 'boolean'],
            'is_favorite' => ['sometimes', 'boolean'],
            'is_pinned' => ['sometimes', 'boolean'],
            'is_catpinned' => ['sometimes', 'boolean'],
        ];
    }

    public function execute()
    {
        // Поменяешь 0 на false - поменяешь ключ кэша.
        if ($this->params['sub_day']) {
            $this->params['sub_day'] = new \DateTime(
                '-'.$this->params['sub_day'].' day'
            );
        }

        return [
            'title' => $this->params['title'],
            'items' => Article::select([
                    'articles.id','articles.user_id','articles.image_id',
                    'articles.slug','articles.title','articles.teaser',
                    'articles.created_at','articles.updated_at',
                ])
                ->when(setting('articles.views_used', false), function ($query) {
                    $query->select('views');
                })
                ->withCount(['comments'])
                ->with([
                    'categories:categories.id,categories.slug,categories.title',
                    'image'
                ])
                ->when($this->params['categories'], function ($query) {
                    $query->whereHas('categories', function ($query) {
                        $query->whereIn('categories.id', $this->params['categories']);
                    });
                })
                ->when($this->params['tags'], function ($query) {
                    $query->whereHas('tags', function ($query) {
                        $query->whereIn('tags.title', $this->params['tags']);
                    });
                })
                ->when($this->params['sub_day'], function ($query) {
                    $query->where(function ($query) {
                        $query->where('created_at', '>=', $this->params['sub_day'])
                            ->orWhere('updated_at', '>=', $this->params['sub_day']);
                    });
                })
                ->when($this->params['id'], function ($query) {
                    $query->whereIn('id', $this->params['id']);
                })
                ->when($this->params['user_id'], function ($query) {
                    $query->whereIn('user_id', $this->params['user_id']);
                })
                ->where('articles.on_mainpage', $this->params['on_mainpage'])
                ->where('articles.state', $this->params['state'])
                ->orderBy($this->params['order_by'], $this->params['direction'])
                ->skip($this->params['skip'])
                ->limit($this->params['limit'])
                ->get(),
        ];
    }
}
