<?php

namespace BBCMS\Http\Controllers;

use BBCMS\Models\Tag;
use BBCMS\Models\Article;
use BBCMS\Models\Category;

class ArticlesController extends SiteController
{
    protected $model;
    protected $template = 'articles';

    public function __construct(Article $model)
    {
        parent::__construct();
        $this->model = $model;
    }

    public function index()
    {
        $articles = $this->model->shortArticle()
            ->filter(request(['month', 'year', 'user']))
            ->where('on_mainpage', 1)
            ->orderBy('is_pinned', 'desc')
            // ->orderBy('updated_at', 'desc')->orderBy('created_at', 'desc')
            ->orderBy(setting('articles.order_by', 'id'), setting('articles.direction', 'desc'))
            ->paginate(setting('articles.paginate', 8));

        pageinfo([
            'title' => setting('articles.meta_title', 'Articles'),
            'description' => setting('articles.meta_description', 'Articles'),
            'keywords' => setting('articles.meta_keywords', 'articles'),
            'url' => route('articles.index'),
            'is_index' => true,
            // 'onMainPage' => $articles->onFirstPage() and empty(request()->query()),
        ]);

        return $this->renderOutput('index', compact('articles'));
    }

    public function category(Category $category)
    {
        $articles = $category->articles()->shortArticle()
            ->orderBy('is_catpinned', 'desc')
            ->orderBy($category->order_by ?? setting('articles.order_by', 'id'), $category->direction)
            ->paginate($category->paginate ?? setting('articles.paginate', 8));

        pageinfo([
            'title' => $category->title,
            'description' => $category->description,
            'keywords' => $category->keywords,
            'url' => $category->url,
            'img' => $category->img,
            'is_category' => true,
            'category' => $category,
        ]);

        if ($category->template and view()->exists('custom_views.'.$category->template.'.'.$this->template.'.index')) {
            $this->template = 'custom_views.'.$category->template.'.'.$this->template;
        }

        return $this->renderOutput('index', compact('articles'));
    }

    public function tag(Tag $tag)
    {
        $articles = $tag->articles()->shortArticle()
            ->orderBy(setting('articles.order_by', 'id'), setting('articles.direction', 'desc'))
            ->paginate(setting('articles.paginate', 8));

        pageinfo([
            'title' => $tag->title,
            'description' => __('common.tag').' '.$tag->title,
            'robots' => 'noindex, follow',
            'url' => $tag->url,
            'section' => [
                'title' => __('common.tag'),
            ],
            'is_tag' => true,
            'tag' => $tag,
        ]);

        return $this->renderOutput('index', compact('articles'));
    }

    public function search()
    {
        $query = html_secure(request('query'));
        $articles = $query
            ? $this->model->shortArticle()
                ->selectRaw('MATCH (title, content) AGAINST (? IN BOOLEAN MODE) as REL', [$query])
                ->whereRaw("MATCH (title, content) AGAINST(? IN BOOLEAN MODE)", [$query])
                ->orderBy('REL', 'desc')
                ->paginate(setting('articles.paginate', 8))
                ->appends(['query' => $query])
            : collect([]);

        pageinfo([
            'title' => __('common.search'),
            'description' => __('common.search').' '.$query,
            'robots' => 'noindex, follow',
            'is_search' => true,
            'query' => $query,
        ]);

        return $this->renderOutput('index', compact('articles', 'query'));
    }

    public function article($category_slug, $article_id, $article_slug = '')
    {
        $article = $this->model->fullArticle($article_id)->firstOrFail();

        if (($article_slug !== $article->slug) or ($category_slug !== $article->categories->pluck('slug')->implode('/'))) {
            return redirect()->to($article->url);
        }

        $article->increment('views');
        $article->tags = $article->tags_count ? $article->getTags() : [];
        $article->comments = $article->comments_count ? $article->getComments(setting('comments.nested', true)) : [];

        pageinfo([
            'title' => $article->title,
            'description' => $article->description ?? $article->teaser,
            'keywords' => $article->keywords,
            'robots' => $article->robots ?? 'all',
            'url' => $article->url,
            'section' => [
                'title' => $article->categories->pluck('title')->implode('/'),
            ],
            'is_article' => true,
            'article' => $article,
        ]);

        if ($article->category->template and view()->exists('custom_views.'.$article->category->template.'.'.$this->template.'.single')) {
            $this->template = 'custom_views.'.$article->category->template.'.'.$this->template;
        }

        return $this->renderOutput('single', compact('article'));
    }
}
