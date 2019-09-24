<?php

namespace BBCMS\Widgets;

use BBCMS\Models\Article;
use BBCMS\Models\Comment;
use BBCMS\Support\WidgetAbstract;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CommentsLatestWidget extends WidgetAbstract
{
    protected $cacheTime = 30;
    protected $casts = [
        'active' => 'boolean',
        'template' => 'string',
        'cache_time' => 'integer',
        'title' => 'string',
        'limit' => 'integer',
        'content_length' => 'integer',
    ];
    protected $template = 'widgets.comments_latest';

    protected function rules()
    {
        return [
            // Frequent
            'active' => ['required', 'boolean'],
            'template' => ['required', 'string'],
            'cache_time' => ['required', 'integer'],
            'title' => ['required', 'string', 'regex:/^[\w\s\.\-\_]+$/u'],
            'limit' => ['required', 'integer'],
            'content_length' => ['required', 'integer'],
            'relation' => ['required', 'string', 'in:articles,profiles'],
        ];
    }

    protected function default()
    {
        return [
            // Frequent
            'active' => setting('comments.widget_used', true),
            'template' => $this->template,
            'cache_time' => $this->cacheTime,
            'title' => setting('comments.widget_title', trans('comments.widget_title')),
            'limit' => setting('comments.widget_count', 4),
            'content_length' => setting('comments.widget_content_length', 150),
            'relation' => 'articles',
        ];
    }

    public function execute()
    {
        return [
            'title' => $this->params['title'],
            'items' => Comment::with([
                    'user:users.id,users.name,users.email,users.avatar',
                    'article:articles.id,articles.title,articles.slug,articles.state',
                ])
                ->where('commentable_type', 'articles')
                ->where('is_approved', true)
                ->latest()
                ->limit($this->params['limit'])
                ->get()
                ->treated(false),
        ];
    }
}
