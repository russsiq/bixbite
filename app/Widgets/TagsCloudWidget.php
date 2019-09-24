<?php

namespace BBCMS\Widgets;

use BBCMS\Models\Tag;
use BBCMS\Support\WidgetAbstract;

class TagsCloudWidget extends WidgetAbstract
{
    protected $cacheTime = 1440;
    protected $casts = [
        'active' => 'boolean',
        'template' => 'string',
        'cache_time' => 'integer',
        'title' => 'string',

        'limit' => 'integer',
        'order_by' => 'string',
        'direction' => 'string',

        'relation' => 'string',
    ];
    protected $template = 'widgets.tags_cloud';

    protected function default()
    {
        return [
            'active' => true,
            'template' => $this->template,
            'cache_time' => $this->cacheTime,
            'title' => setting('tags.widget_title', trans('tags.widget_title')),

            'limit' => setting('tags.widget_limit', 8),
            'order_by' => 'articles_count',
            'direction' => 'desc',

            'relation' => 'articles',
        ];
    }

    protected function rules()
    {
        return [
            'active' => ['required', 'boolean'],
            'template' => ['required', 'string'],
            'cache_time' => ['required', 'integer'],
            'title' => ['required', 'string', 'regex:/^[\w\s\.-_]+$/u'],

            'order_by' => ['required', 'string', 'in:id,title,created_at,updated_at,articles_count,profiles_count'],
            'direction' => ['required', 'string', 'in:desc,asc'],
            'limit' => ['required', 'integer'],

            'relation' => ['required', 'string', 'in:articles,profiles'],
        ];
    }

    public function execute()
    {
        return [
            'title' => $this->params['title'],
            'items' => Tag::select([
                    'tags.id',
                    'tags.title',
                ])
                ->withCount($this->params['relation'])
                ->whereHas($this->params['relation'])
                ->orderBy($this->params['order_by'], $this->params['direction'])
                ->limit($this->params['limit'])
                ->get(),
        ];
    }
}
