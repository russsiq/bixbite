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
        $query = filter_input_array(INPUT_GET, [
            'year' => FILTER_SANITIZE_NUMBER_INT,
            'month' => FILTER_SANITIZE_STRING,
            'user_id' => FILTER_SANITIZE_NUMBER_INT,
        ]);

        $articles = $this->model->shortArticle()
            ->filter($query)
            ->where('on_mainpage', 1)
            ->orderBy('is_pinned', 'desc')
            ->orderBy(setting('articles.order_by', 'id'), setting('articles.direction', 'desc'))
            ->paginate(setting('articles.paginate', 8))
            ->appends($query);

        pageinfo()->unless('onHomePage', [
            'title' => setting('articles.meta_title', 'Articles'),
            'description' => null,
            'keywords' => null,
            'robots' => 'noindex, follow',
            'url' => route('articles.index'),
            'is_index' => true,
        ]);

        return $this->makeResponse('index', compact('articles'));
    }

    public function category(Category $category)
    {
        $articles = $category->articles()->shortArticle()
            ->orderBy('is_catpinned', 'desc')
            ->orderBy($category->order_by ?? setting('articles.order_by', 'id'), $category->direction)
            ->paginate($category->paginate ?? setting('articles.paginate', 8));

        pageinfo([
            'title' => $category->title,
            'description' => $category->description ?? $category->info,
            'keywords' => $category->keywords,
            'url' => $category->url,
            'img' => $category->image,
            'is_category' => true,
            'category' => $category,
        ]);

        if ($category->template) {
            $view = 'custom_views.'.$category->template.'.'.$this->template;

            $this->template = view()->exists($view.'.index') ? $view : $this->template;
        }

        return $this->makeResponse('index', compact('category', 'articles'));
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

        return $this->makeResponse('index', compact('articles'));
    }

    public function search()
    {
        $query = html_secure(request('query'));
        $articles = $query
            ? $this->model->shortArticle()
                ->search($query)
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

        return $this->makeResponse('index', compact('articles', 'query'));
    }

    public function article($category_slug, $article_id, $article_slug = '')
    {
        $article = $this->model->cachedFullArticleWithRelation($article_id);

        if (($article_slug !== $article->slug) or ($category_slug !== $article->categories->pluck('slug')->implode('_'))) {
            cache()->forget('cachedFullArticleWithRelation-'.$article_id);
            return redirect()->to($article->url);
        }

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

        if ($article->category->template) {
            $view = 'custom_views.'.$article->category->template.'.'.$this->template;

            $this->template = view()->exists($view.'.single') ? $view : $this->template;
        }

        return $this->makeResponse('single', compact('article'));
    }
}
