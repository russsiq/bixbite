<?php

declare(strict_types=1);

namespace Tests\Feature\Rss;

use App\Models\Article;
use App\Models\Category;
use Database\Seeders\ArticleCategoriesSeeder;
use Database\Seeders\ArticlesTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Rss\SitemapController
 *
 * @cmd vendor\bin\phpunit tests\Feature\Rss\SitemapControllerTest.php
 */
class SitemapControllerTest extends TestCase
{
    use RefreshDatabase;

    public const XML_FIRST_STRING = '<?xml version="1.0" encoding="UTF-8"?>';
    public const XML_CONTENT_TYPE = 'text/xml; charset=UTF-8';

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_guest_can_view_sitemap_index'
     */
    public function test_guest_can_view_sitemap_index(): void
    {
        Config::set('settings.system.index_changefreq', 'always');

        $response = $this->assertGuest()->get(route('sitemap.xml'));

        $response->assertStatus(200);

        $response->assertHeader('Content-Type', self::XML_CONTENT_TYPE);

        $response->assertSeeInOrder([
            self::XML_FIRST_STRING,
            route('sitemap-home.xml'),
            route('sitemap-categories.xml'),
            route('sitemap-articles.xml'),
        ], false);
    }

    /**
     * @covers ::home
     * @cmd vendor\bin\phpunit --filter '::test_guest_can_view_sitemap_home'
     */
    public function test_guest_can_view_sitemap_home(): void
    {
        Config::set('settings.system.home_changefreq', 'always');

        $response = $this->assertGuest()->get(route('sitemap-home.xml'));

        $response->assertStatus(200);

        $response->assertHeader('Content-Type', self::XML_CONTENT_TYPE);

        $response->assertSeeInOrder([
            self::XML_FIRST_STRING,
            url('/',)
        ], false);
    }

    /**
     * @covers ::categories
     * @cmd vendor\bin\phpunit --filter '::test_guest_can_view_sitemap_categories'
     */
    public function test_guest_can_view_sitemap_categories(): void
    {
        Config::set('settings.system.categories_changefreq', 'always');

        $categories = Category::factory()->count(4)->create();

        $response = $this->assertGuest()->get(route('sitemap-categories.xml'));

        $response->assertStatus(200);

        $response->assertHeader('Content-Type', self::XML_CONTENT_TYPE);

        $response->assertSeeInOrder(array_merge([
            self::XML_FIRST_STRING,
        ], $categories->pluck('url')->toArray()), false);
    }

    /**
     * @covers ::articles
     * @cmd vendor\bin\phpunit --filter '::test_guest_can_view_sitemap_articles'
     */
    public function test_guest_can_view_sitemap_articles(): void
    {
        Config::set('settings.system.articles_changefreq', 'always');

        (new UsersTableSeeder)->run(4);
        (new ArticlesTableSeeder)->run(8);
        (new CategoriesTableSeeder)->run(4);
        (new ArticleCategoriesSeeder)->run();

        $articles = Article::published()->get();

        $response = $this->assertGuest()->get(route('sitemap-articles.xml'));

        $response->assertStatus(200);

        $response->assertHeader('Content-Type', self::XML_CONTENT_TYPE);

        $response->assertSee(self::XML_FIRST_STRING, false);

        $articles->pluck('url')->map(
            fn ($url) => $response->assertSee($url)
        );
    }
}
