<?php

namespace App\Http\Controllers;

// Сторонние зависимости.
use App\Models\Tag;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Контроллер, управляющий Записями сайта.
 */
class ArticlesController extends SiteController
{
    /**
     * Директория пользовательских шаблонов текущей категории.
     * @const string
     */
    const DIRECTORY_CUSTOM_VIEWS = 'custom_views';

    /**
     * Модель Запись.
     * @var Article
     */
    protected $model;

    /**
     * Настройки модели Запись.
     * @var object
     */
    protected $settings;

    /**
     * Макет шаблонов контроллера.
     * @var string
     */
    protected $template = 'articles';

    /**
     * Создать экземпляр контроллера.
     * @param  Article  $model
     */
    public function __construct(Article $model)
    {
        $this->model = $model;

        $this->settings = (object) setting($model->getTable());
    }

    /**
     * Отобразить список ресурса.
     * @param  Request  $request
     * @return Renderable
     */
    public function index(): Renderable
    {
        $filters = filter_input_array(INPUT_GET, [
            'year' => FILTER_SANITIZE_NUMBER_INT,
            'month' => FILTER_SANITIZE_STRING,
            'user_id' => FILTER_SANITIZE_NUMBER_INT,
        ]);

        $articles = $this->model->shortArticle()
            ->published()
            ->when($filters, function(Builder $query, $filters) {
                $query->filter($filters);
            })
            ->where('on_mainpage', 1)
            ->orderBy('is_pinned', 'desc')
            ->orderBy($this->settings->order_by ?? 'id', $this->settings->direction ?? 'desc')
            ->paginate($this->settings->paginate ?? 8)
            ->appends($filters);

        pageinfo()->unless('onHomePage', [
            'title' => $this->settings->meta_title ?? 'Articles',
            'description' => null,
            'keywords' => null,
            'robots' => 'noindex, follow',
            'url' => route('articles.index'),
            'is_index' => true,
        ]);

        return $this->makeResponse('index', compact('articles'));
    }

    /**
     * Отобразить список ресурса в зависимости от выбранной категории.
     * @param  Category  $category
     * @return Renderable
     */
    public function category(Category $category): Renderable
    {
        $articles = $category->articles()->shortArticle()
            ->published()
            ->orderBy('is_catpinned', 'desc')
            ->orderBy($category->order_by ?? $this->settings->order_by ?? 'id', $category->direction)
            ->paginate($category->paginate ?? $this->settings->paginate ?? 8);

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
            $view = self::DIRECTORY_CUSTOM_VIEWS.'.'.$category->template.'.'.$this->template;

            $this->template = view()->exists($view.'.index') ? $view : $this->template;
        }

        return $this->makeResponse('index', compact('category', 'articles'));
    }

    /**
     * Отобразить список ресурса в зависимости от выбранного тега.
     * @param  Category  $category
     * @return Renderable
     */
    public function tag(Tag $tag): Renderable
    {
        $articles = $tag->articles()->shortArticle()
            ->published()
            ->orderBy($this->settings->order_by ?? 'id', $this->settings->direction ?? 'desc')
            ->paginate($this->settings->paginate ?? 8);

        pageinfo([
            'title' => $tag->title,
            'description' => trans('common.tag').' '.$tag->title,
            'robots' => 'noindex, follow',
            'url' => $tag->url,
            'section' => [
                'title' => trans('common.tag'),
            ],
            'is_tag' => true,
            'tag' => $tag,

        ]);

        return $this->makeResponse('index', compact('articles'));
    }

    /**
     * Отобразить список ресурса в зависимости от поискового запроса.
     * @param  Request  $request
     * @return Renderable
     */
    public function search(Request $request): Renderable
    {
        $query = html_clean($request->input('query'));

        $articles = $query
            ? $this->model->shortArticle()
                ->published()
                ->search($query)
                ->paginate($this->settings->paginate ?? 8)
                ->appends(compact('query'))
            : collect([]);

        pageinfo([
            'title' => trans('common.search'),
            'description' => trans('common.search').' '.$query,
            'robots' => 'noindex, follow',
            'is_search' => true,
            'query' => $query,

        ]);

        return $this->makeResponse('index', compact('articles', 'query'));
    }

    /**
     * Отобразить целевую страницу сайта.
     * @param  string  $category_slug
     * @param  int  $article_id
     * @param  string  $article_slug
     * @return RedirectResponse|Renderable
     */
    public function article(string $category_slug, int $article_id, string $article_slug)
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
            $view = self::DIRECTORY_CUSTOM_VIEWS.'.';
            $view .= $article->category->template.'.';
            $view .= $this->template;

            $this->template = view()->exists($view.'.single') ? $view : $this->template;
        }

        return $this->makeResponse('single', compact('article'));
    }
}
