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
            ->filter(request(['month', 'year', 'user_id']))
            ->where('on_mainpage', 1)
            ->orderBy('is_pinned', 'desc')
            ->orderBy(setting('articles.order_by', 'id'), setting('articles.direction', 'desc'))
            ->paginate(setting('articles.paginate', 8));

        pageinfo()->unless('onHomePage', [
            'title' => setting('articles.meta_title', 'Articles'),
            'description' => null,
            'keywords' => null,
            'robots' => 'noindex, follow',
            'url' => route('articles.index'),
            'is_index' => true,
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
            'description' => $category->description ?? $category->info,
            'keywords' => $category->keywords,
            'url' => $category->url,
            'img' => $category->image,
            'is_category' => true,
            'category' => $category,
        ]);

        if ($article->category->template) {
            $view = 'custom_views.'.$article->category->template.'.'.$this->template;
            
            $this->template = view()->exists($view.'.index') ? $view : $this->template;
        }

        return $this->renderOutput('index', compact('category', 'articles'));
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

        return $this->renderOutput('single', compact('article'));
    }
}
