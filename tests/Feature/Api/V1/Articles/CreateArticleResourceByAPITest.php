<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Articles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Тестирование создания ресурса `Article`.
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Articles\CreateArticleResourceByAPITest.php
 */
class CreateArticleResourceByAPITest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_article()
    {
        $response = $this->assertGuest()
            ->postJson(route('api.articles.store'), [

            ])
            ->assertUnauthorized();
    }
}
