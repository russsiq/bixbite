<?php

namespace App\Http\Controllers\Rss;

// Сторонние зависимости.
use App\Models\Article;
use App\Models\Category;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Carbon;

/**
 * Контроллер для генерации XML карты турбо-страниц для Яндекса.
 */
class AmpController extends BaseController
{
    /**
     * Ключ кэша турбо страниц.
     *
     * @const string
     */
    const CACHE_KEY = 'amp-articles.xml';

    /**
     * Шаблон представления.
     *
     * @var string
     */
    protected $template = 'rss.amp-articles';

    /**
     * Получить ключ кэша карты.
     *
     * @return string
     */
    protected function cacheKey(): string
    {
        return self::CACHE_KEY;
    }

    /**
     * Получить время кеширования карты турбо-страниц.
     *
     * @return int|null
     */
    protected function cacheTime(): ?int
    {
        return $this->changefreq[
            setting('system.amp_articles_changefreq', 'daily')
        ];
    }

    /**
     * Получить дату последнего изменения информации на сайте.
     *
     * @return Carbon|null
     */
    protected function lastmod(): ?Carbon
    {
        $key = $this->cacheKey();

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
     * Получить компилируемое представление карты турбо-страниц.
     *
     * @return Renderable
     */
    protected function view(): Renderable
    {
        $articles = $this->resolveArticles();

        return view($this->template, compact('articles'));
    }

    /**
     * Извлечь коллекцию записей из хранилища.
     *
     * @return EloquentCollection
     */
    protected function resolveArticles(): EloquentCollection
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
            ->includeExtensibleAttributes()
            ->with([
                'attachments' => function ($query) {
                    $query->select([
                        'attachments.id',
                        'attachments.attachable_type',
                        'attachments.attachable_id',
                        'attachments.title',
                        'attachments.disk',
                        'attachments.folder',
                        'attachments.type',
                        'attachments.name',
                        'attachments.extension',
                    ])
                    ->join('articles', function ($join) {
                        $join->on('attachments.id', '=', 'articles.image_id');
                    })
                    ->where('type', 'image');
                },
            ])
            ->published()
            ->latest('articles.updated_at')
            ->get();
    }
}
