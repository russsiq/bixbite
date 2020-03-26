<?php

namespace App\Http\Controllers\Rss;

// Сторонние зависимости.
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

// @NB: Need chunk to articles.

class SitemapController
{
    /**
     * Дата последнего изменения информации на сайте.
     * @var Carbon
     */
    protected static $lastmod;

    /**
     * Вероятная частота изменения информации на сайте.
     * Алгоритмов поисков роботов не знаем, поэтому сокращаем месяц и год.
     * @var array
     */
    protected static $changefreq = [
        // Постоянно изменяется. Не использовать кэш.
        'always' => false,

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

    /**
     * Получить массив переменных для основной XML-карты.
     * @return array
     */
    protected static function getIndex(): array
    {
        return [
            'articles' => Article::select([
                    'articles.created_at',
                    'articles.updated_at',

                ])
                ->published()
                ->latest('updated_at')
                ->first(),

            'categories' => Category::select([
                    'categories.created_at',
                    'categories.updated_at',

                ])
                ->excludeExternal()
                ->latest('updated_at')
                ->first(),
        ];
    }

    /**
     * Получить массив переменных для XML-карты домашней страницы.
     * @return array
     */
    protected static function getHome(): array
    {
        return [
            'lastmod' => static::lastmod(),
        ];
    }

    /**
     * Получить массив переменных для XML-карты записей.
     * @return array
     */
    protected static function getArticles()
    {
        return [
            'articles' => Article::select([
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
                ->get(),
        ];
    }

    /**
     * Получить массив переменных для турбо-страниц.
     * @return array
     */
    protected static function getAmpArticles(): array
    {
        return [
            'articles' => Article::select([
                    'articles.id',
                    'articles.image_id',
                    'articles.slug',
                    'articles.created_at',
                    'articles.updated_at',

                    'articles.title',
                    'articles.content',

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
                ->get(),
        ];
    }

    /**
     * Получить массив переменных для XML-карты категорий.
     * @return array
     */
    protected static function getCategories(): array
    {
        return [
            'categories' => Category::select([
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
                ->get(),
        ];
    }

    /**
     * Получить XML-строковое представление указанной карты.
     * @param  string  $get
     * @param  string  $sitemap
     * @return string
     */
    protected static function render(string $get, string $sitemap): string
    {
        $cache_key = 'rss.'.$sitemap;
        $cache_time = static::cacheTime($sitemap) * 60;

        if (false === $cache_time) {
            return view($cache_key, static::{$get}())->render();
        }

        if (static::lastmod() > \CacheFile::created($cache_key)) {
            cache()->forget($cache_key);
        }

        if (0 === $cache_time) {
            return cache()->store('file')->rememberForever($cache_key,
                function () use ($cache_key, $get) {
                    return trim(preg_replace('/(\s|\r|\n)+</', '<',
                        view($cache_key, static::{$get}())->render()
                    ));
                });
        }

        return cache()->store('file')->remember($cache_key, $cache_time,
            function () use ($cache_key, $get) {
                return trim(preg_replace('/(\s|\r|\n)+</', '<',
                    view($cache_key, static::{$get}())->render()
                ));
            });
    }

    /**
     * Получить время кеширования указанной карты.
     * @param  string  $sitemap
     * @return int
     */
    protected static function cacheTime(string $sitemap): int
    {
        return static::$changefreq[
            setting('system.'.$sitemap.'_changefreq', 'daily')
        ];
    }

    /**
     * Получить дату последнего изменения информации на сайте.
     * @return Carbon
     */
    protected static function lastmod(): Carbon
    {
        if (is_null($index = self::$lastmod)) {
            $index = static::getIndex();
        }

        return max([
            $index['articles']->updated_at ?? $index['articles']->created_at,
            $index['categories']->updated_at ?? $index['categories']->created_at
        ]);
    }

    /**
     * Динамическое создание карты сайта.
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (method_exists(static::class, $get = 'get'.ucfirst($method))) {
            return response(
                    static::render($get, Str::snake($method)),
                    200, ['Content-Type' => 'text/xml']
                );
        }

        abort(404);
    }
}
