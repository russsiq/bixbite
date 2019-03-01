<?php

namespace BBCMS\Http\Controllers;

use BBCMS\Models\Tag;

class TagsController extends SiteController
{
    protected $model;
    protected $template = 'tags';

    public function __construct(Tag $model)
    {
        parent::__construct();
        $this->model = $model;
    }

    public function index()
    {
        $tags = $this->model->select([
                'tags.id','tags.title','tags.created_at','tags.updated_at',
            ])
            ->withCount('articles')
            ->whereHas('articles')
            ->orderBy(setting('tags.order_by', 'articles_count'), setting('tags.direction', 'desc'))
            ->paginate(setting('tags.paginate', 8));

        pageinfo([
            'title' => setting('tags.meta_title', 'Tags'),
            'description' => setting('tags.meta_description', 'Tags'),
            'keywords' => setting('tags.meta_keywords', 'tags'),
            'robots' => 'noindex, follow',
            'url' => route('tags.index'),
            'is_index' => true,
        ]);

        return $this->makeResponse('index', compact('tags'));
    }
}
