<?php

namespace App\Http\Controllers\Rss;

// Сторонние зависимости.
use App\Models\Article;
use App\Models\Category;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Контроллер для генерации XML карты турбо-страниц для Яндекса.
 *
 * @NB: Need chunk to articles.
 */
class AmpController extends BaseController
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
    protected $template = 'rss.amp-articles';

    /**
     * Получить ключ кэша карты.
     * @return string
     */
    protected function cacheKey(): string
    {
        return self::CACHE_KEY;
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
     * Получить компилируемое представление карты турбо-страниц.
     * @return Renderable
     */
    protected function view(): Renderable
    {
        $articles = $this->resolveArticles();

        return view($this->template, compact('articles'));
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
