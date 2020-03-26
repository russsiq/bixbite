<?php

namespace App\Http\Controllers\Rss;

// Сторонние зависимости.
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Контроллер для генерации XML карты турбо-страниц для Яндекса.
 *
 * @NB: Need chunk to articles.
 */
class AmpController
{
    /**
     * Ключ кэша турбо страниц.
     * @const string
     */
    const CACHE_KEY = 'amp.articles.xml';

    /**
     * Шаблон представления.
     * @var string
     */
    protected $template = 'rss.amp_articles';

    /**
     * Вероятная частота изменения информации на сайте.
     * Алгоритмов поисков роботов не знаем, поэтому сокращаем месяц и год.
     * @var array
     */
    protected $changefreq = [
        // Постоянно изменяется. Не использовать кэш.
        'always' => null,

        // Каждый час.
        'hourly' => 60,

        // Ежедневно.
        'daily' => 60 * 24,

        // Еженедельно.
        'weekly' => 60 * 24 * 7,

        // Бухгалтерский месяц.
        'monthly' => 60 * 24 * 7 * 21,

        // Венерианский год.
        'yearly' => 60 * 24 * 224,

        // Никогда не изменяется. Кэшировать навсегда.
        'never' => 0,

    ];

    public function __invoke()
    {
        return response($this->content(), 200)
            ->withHeaders([
                'Content-Type' => 'text/xml',

            ]);
    }

    /**
     * Получить скомпилированное содержимое карты турбо-страниц.
     * @param  string  $get
     * @param  string  $sitemap
     * @return string
     */
    protected function content()
    {
        $cacheTime = $this->cacheTime();

        if (is_null($cacheTime)) {
            return $this->view();
        }

        if ($this->isExpired()) {
            cache()->forget(self::CACHE_KEY);
        }

        $cacheTime = $cacheTime * 60;

        if (0 === $cacheTime) {
            return cache()->store('file')
                ->rememberForever(self::CACHE_KEY, function () {
                    return $this->prepareForCache($this->view());
                });
        }

        return cache()->store('file')
            ->remember(self::CACHE_KEY, $cacheTime, function () {
                return $this->prepareForCache($this->view());
            });
    }

    /**
     * Получить время кеширования карты турбо-страниц.
     * @return int|null
     */
    protected function cacheTime(): ?int
    {
        return $this->changefreq[
            setting('system.amp_articles_changefreq', 'daily')
        ];
    }

    /**
     * Проверить просроченность карты турбо-страниц.
     * @return bool
     */
    protected function isExpired(): bool
    {
        return $this->lastmod() > \CacheFile::created(self::CACHE_KEY);
    }

    /**
     * Получить дату последнего изменения информации на сайте.
     * @return Carbon
     */
    protected function lastmod(): Carbon
    {
        return Article::without('categories')
            ->select('articles.updated_at')
            ->published()
            ->latest('articles.updated_at')
            ->value('updated_at');
    }

    /**
     * Получить XML-строковое представление карты турбо-страниц.
     * @return string
     */
    protected function view(): string
    {
        $articles = $this->resolveArticles();

        return view($this->template, compact('articles'))
            ->render();
    }

    /**
     * Подготовить XML-строковое представление к кэшированию.
     * @return string
     */
    protected function prepareForCache(string $view): string
    {
        return trim(preg_replace('/(\s|\r|\n)+</', '<', $view));
    }

    /**
     * Извлечь коллекцию записей из хранилища.
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

                'articles.title',
                'articles.content',

            ])
            ->includeXFieldsNames()
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
            ->latest('articles.updated_at')
            ->get();
    }
}
