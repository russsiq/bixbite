<?php

namespace BBCMS\Http\Controllers\Common;

use BBCMS\Models\Article;
use BBCMS\Models\Category;

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
                    'articles.updated_at',
                ])
                ->where('articles.state', 'published')
                ->orderBy('updated_at', 'desc')
                ->first(),

            'categories' => Category::select([
                    'categories.updated_at',
                ])
                ->orderBy('updated_at', 'desc')
                ->first(),
        ];
    }

    protected static function getHome()
    {
        return [
            //
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
                ])
                ->with([
                    'image',
                    'categories:categories.id,categories.slug',
                ])
                ->where('articles.state', 'published')
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
                    'image',
                ])
                ->get(),
        ];
    }

    protected static function render(string $get, string $sitemap)
    {
        $cache_key = 'sitemap.'.$sitemap;
        $cache_time = static::cacheTime($sitemap);

        if (false === $cache_time) {
            return view($cache_key, static::{$get}())->render();
        }

        if (static::lastmod() > cache_created($cache_key)) {
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
            $index['articles']->updated_at,
            $index['categories']->updated_at
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
                    static::render($get, $method),
                    200, ['Content-Type' => 'text/xml']
                );
        }

        abort(404);
    }
}
