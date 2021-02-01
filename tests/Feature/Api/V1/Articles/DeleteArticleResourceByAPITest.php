<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Тестирование удаления ресурса `Article`.
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Articles\DeleteArticleResourceByAPITest.php
 */
class DeleteArticleResourceByAPITest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_delete_article()
    {
        $article = Article::factory()->create();

        $response = $this->assertGuest()
            ->deleteJson(route('api.articles.destroy', $article), [

            ])
            ->assertUnauthorized();
    }
}
