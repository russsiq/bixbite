<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Articles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Тестирование получения ресурса `Article`.
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Articles\FetchArticleResourceTest.php
 */
class FetchArticleResourceTest extends TestCase
{
    public function test_guest_cannot_fetch_articles()
    {
        $response = $this->assertGuest()
            ->getJson(route('api.articles.index'))
            ->assertUnauthorized();
    }
}
