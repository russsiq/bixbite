<?php

namespace App\Http\Controllers\Rss;

// Сторонние зависимости.
use App\Models\Article;
use App\Models\Category;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

/**
 * Контроллер для генерации карт сайта.
 *
 * @NB: Need chunk to articles.
 */
class SitemapController extends BaseController
{
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
     * Последняя добавленная / измененная запись сайта.
     * @var Article|null
     */
    protected $latestArticle;

    /**
     * Последняя добавленная / измененная категория сайта.
     * @var Category|null
     */
    protected $latestCategory;

    /**
     * Основная карта сайта, содержащая ссылки на остальные карты.
     * @return Response
     */
    public function index(): Response
    {
        $this->sitemap = 'index';

        $this->data = [
            'article' => $this->latestArticle(),
            'category' => $this->latestCategory(),

        ];

        return $this();
    }

    /**
     * Карта домашней страницы сайта.
     * @return Response
     */
    public function home(): Response
    {
        $this->sitemap = 'home';

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
        $this->sitemap = 'articles';

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
        $this->sitemap = 'categories';

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
     * @return Carbon
     */
    protected function lastmod(): Carbon
    {
        return max([
            $this->latestArticle()->updated_at,
            $this->latestCategory()->updated_at
        ]);
    }

    /**
     * Получить компилируемое представление текущей карты.
     * @return Renderable
     */
    protected function view(): Renderable
    {
        return view($this->template(), $this->data());
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
     * Получить последнюю запись сайта или создать новую.
     * @return Article
     */
    protected function latestArticle(): Article
    {
        return $this->latestArticle
            ?? $this->latestArticle = Article::without('categories')
                ->select([
                    'articles.id',
                    'articles.created_at',
                    'articles.updated_at',

                ])
                ->published()
                ->latest('updated_at')
                ->firstOrNew([], [
                    'updated_at' => now(),

                ]);
    }

    /**
     * Получить последнюю категорию сайта или создать новую.
     * @return Category
     */
    protected function latestCategory(): Category
    {
        return $this->latestCategory
            ?? $this->latestCategory = Category::select([
                'categories.id',
                'categories.created_at',
                'categories.updated_at',

            ])
            ->excludeExternal()
            ->latest('updated_at')
            ->first()
            ->firstOrNew([], [
                'updated_at' => now(),

            ]);
    }

    /**
     * Извлечь все записи из базы данных.
     * @return Collection
     */
    protected function resolveArticles(): Collection
    {
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
    }

    /**
     * Извлечь все категории из базы данных.
     * @return Collection
     */
    protected function resolveCategories(): Collection
    {
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
    }
}
