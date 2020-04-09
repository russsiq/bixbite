<?php

namespace App\Http\Controllers\Rss;

// Базовые расширения PHP.
use Closure;

// Сторонние зависимости.
use App\Models\Article;
use App\Models\Category;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

/**
 * Контроллер для генерации карт сайта.
 */
class SitemapController extends BaseController
{
    /**
     * Префикс шаблонов. По факту пространство имен.
     * @const string
     */
    const TEMPLATE_PREFIX = 'rss.sitemap.';

    /**
     * Текущая карта сайта.
     * @var string
     */
    protected $sitemap;

    /**
     * Массив данных для шаблона текущей карты.
     * @var array
     */
    protected $data = [];

    /**
     * Дата последнего изменения записей на сайте.
     * @var Carbon
     */
    protected static $articlesLastmod;

    /**
     * Дата последнего изменения категорий на сайте.
     * @var Carbon
     */
    protected static $categoriesLastmod;

    /**
     * Основная карта сайта, содержащая ссылки на остальные карты.
     * @return Response
     */
    public function index(): Response
    {
        $this->sitemap = __FUNCTION__;

        $this->data = [
            'articlesLastmod' => $this->articlesLastmod(),
            'categoriesLastmod' => $this->categoriesLastmod(),

        ];

        return $this();
    }

    /**
     * Карта домашней страницы сайта.
     * @return Response
     */
    public function home(): Response
    {
        $this->sitemap = __FUNCTION__;

        $this->data = [
            'lastmod' => $this->lastmod(),

        ];

        return $this();
    }

    /**
     * Карта записей сайта.
     * @return Response
     */
    public function articles(): Response
    {
        $this->sitemap = __FUNCTION__;

        $this->data = [
            'articles' => $this->resolveArticles(),

        ];

        return $this();
    }

    /**
     * Карта записей сайта.
     * @return Response
     */
    public function categories(): Response
    {
        $this->sitemap = __FUNCTION__;

        $this->data = [
            'categories' => $this->resolveCategories(),

        ];

        return $this();
    }

    /**
     * Получить ключ кэша карты.
     * @return string
     */
    protected function cacheKey(): string
    {
        return $this->template();
    }

    /**
     * Получить время кеширования текущей карты.
     * @param  string  $sitemap
     * @return int|null
     */
    protected function cacheTime(): ?int
    {
        return $this->changefreq[
            setting('system.'.$this->sitemap.'_changefreq', 'daily')
        ];
    }

    /**
     * Получить дату последнего изменения информации,
     * которая будет представлена в текущей ленте.
     * @return Carbon|null
     */
    protected function lastmod(): ?Carbon
    {
        $key = $this->cacheKey();

        if (isset(self::$lastmods[$key])) {
            return self::$lastmods[$key];
        }

        return self::$lastmods[$key] = max([
            $this->articlesLastmod(),
            $this->categoriesLastmod()
        ]);
    }

    /**
     * Получить компилируемое представление текущей карты.
     * @return Renderable
     */
    protected function view(): Renderable
    {
        $data = $this->data();

        foreach ($data as $key => $value) {
            $data[$key] = value($value);
        }

        return view($this->template(), $data);
    }

    /**
     * Получить шаблон текущей карты.
     * @return string
     */
    public function template(): string
    {
        return self::TEMPLATE_PREFIX.$this->sitemap;
    }

    /**
     * Получить Массив данных для шаблона текущей карты.
     * @return array
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * Получить дату последнего изменения записей на сайте.
     * @return Carbon|null
     */
    protected function articlesLastmod(): ?Carbon
    {
        $key = 'articles';

        if (array_key_exists($key, self::$lastmods)) {
            return self::$lastmods[$key];
        }

        $date = Article::without('categories')
            ->selectRaw('GREATEST(created_at, updated_at) as lastmod')
            ->published()
            ->latest('lastmod')
            ->value('lastmod');

        return self::$lastmods[$key] = $date ? Carbon::parse($date) : null;
    }

    /**
     * Получить дату последнего изменения категорий на сайте.
     * @return Carbon|null
     */
    protected function categoriesLastmod(): ?Carbon
    {
        $key = 'categories';

        if (array_key_exists($key, self::$lastmods)) {
            return self::$lastmods[$key];
        }

        $date = Category::query()
            ->selectRaw('GREATEST(created_at, updated_at) as lastmod')
            ->excludeExternal()
            ->latest('lastmod')
            ->value('lastmod');

        return self::$lastmods[$key] = $date ? Carbon::parse($date) : null;
    }

    /**
     * Извлечь все записи из базы данных.
     * @return Closure
     */
    protected function resolveArticles(): Closure
    {
        return function() {
        return Article::select([
                'articles.id',
                'articles.image_id',
                'articles.slug',
                'articles.created_at',
                'articles.updated_at',

            ])
            ->with([
                'files' => function ($query) {
                    $query->select([
                        'files.id',
                        'files.disk',
                        'files.type',
                        'files.category',
                        'files.name',
                        'files.extension',
                        'files.attachment_type',
                        'files.attachment_id',

                    ])
                    ->join('articles', function ($join) {
                        $join->on('files.id', '=', 'articles.image_id');
                    })
                    ->where('type', 'image');
                },
            ])
            ->published()
            ->latest('updated_at')
            ->get();
        };
    }

    /**
     * Извлечь все категории из базы данных.
     * @return Closure
     */
    protected function resolveCategories(): Closure
    {
        return function() {
        return Category::select([
                'categories.id',
                'categories.slug',
                'categories.image_id',
                'categories.created_at',
                'categories.updated_at',

            ])
            ->with([
                'files' => function ($query) {
                    $query->select([
                        'files.id',
                        'files.disk',
                        'files.type',
                        'files.category',
                        'files.name',
                        'files.extension',
                        'files.attachment_type',
                        'files.attachment_id',

                    ])
                    ->join('categories', function ($join) {
                        $join->on('files.id', '=', 'categories.image_id');
                    })
                    ->where('type', 'image');
                },
            ])
            ->excludeExternal()
            ->get();
        };
    }
}
