<?php

namespace BBCMS\Http\Controllers\Rss;

// Сторонние зависимости.
use BBCMS\Models\Article;
use BBCMS\Models\Category;
use Illuminate\Support\Str;

// @NB: Need chunk to articles.

class SitemapController
{
    protected static $lastmod;

    protected static $changefreq = [
         // Not cached.
        'always' => false,
        'hourly' => 60,
        'daily' => 60*24,
        'weekly' => 60*24*7,
        // Accounting month.
        'monthly' => 60*24*7*21,
        // Venusian year.
        'yearly' => 60*24*224,
        // Remember forever.
        'never' => 0,
    ];

    protected static function getIndex()
    {
        return [
            'articles' => Article::select([
                    'articles.state',
                    'articles.created_at',
                    'articles.updated_at',
                ])
                ->published()
                ->orderBy('updated_at', 'desc')
                ->first(),

            'categories' => Category::select([
                    'categories.created_at',
                    'categories.updated_at',
                ])
                ->orderBy('updated_at', 'desc')
                ->first(),
        ];
    }

    protected static function getHome()
    {
        return [
            'lastmod' => static::lastmod(),
        ];
    }

    protected static function getArticles()
    {
        return [
            'articles' => Article::select([
                    'articles.id',
                    'articles.image_id',
                    'articles.slug',
                    'articles.created_at',
                    'articles.updated_at',
                    'articles.state',
                ])
                ->with([
                    'files' => function ($query) {
                        $query->select([
                            'files.id', 'files.disk', 'files.type',
                            'files.category', 'files.name', 'files.extension',
                            'files.attachment_type','files.attachment_id'
                        ])
                        ->join('articles', function ($join) {
                            $join->on('files.id', '=', 'articles.image_id');
                        })
                        ->where('type', 'image');
                    },
                ])
                ->published()
                ->orderBy('updated_at', 'desc')
                ->get(),
        ];
    }

    protected static function getAmpArticles()
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
                    'articles.state',
                ])
                ->with([
                    'files' => function ($query) {
                        $query->select([
                            'files.id', 'files.disk', 'files.type',
                            'files.category', 'files.name', 'files.extension',
                            'files.attachment_type','files.attachment_id'
                        ])
                        ->join('articles', function ($join) {
                            $join->on('files.id', '=', 'articles.image_id');
                        })
                        ->where('type', 'image');
                    },
                ])
                ->published()
                ->orderBy('updated_at', 'desc')
                ->get(),
        ];
    }

    protected static function getCategories()
    {
        return [
            'categories' => Category::select([
                    'categories.id',
                    'categories.image_id',
                    'categories.slug',
                    'categories.created_at',
                    'categories.updated_at',
                ])
                ->with([
                    'files' => function ($query) {
                        $query->select([
                            'files.id', 'files.disk', 'files.type',
                            'files.category', 'files.name', 'files.extension',
                            'files.attachment_type','files.attachment_id'
                        ])
                        ->join('categories', function ($join) {
                            $join->on('files.id', '=', 'categories.image_id');
                        })
                        ->where('type', 'image');
                    },
                ])
                ->get(),
        ];
    }

    protected static function render(string $get, string $sitemap)
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

    protected static function cacheTime(string $sitemap)
    {
        return static::$changefreq[
                setting('system.'.$sitemap.'_changefreq', 'daily')
            ] ?? false;
    }

    protected static function lastmod()
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
     * Dynamically create sitemap.
     *
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
