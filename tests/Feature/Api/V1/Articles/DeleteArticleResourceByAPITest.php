<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Articles;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\Sanctum;
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
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_delete_article()
    {
        Sanctum::actingAs(
            $user = User::factory()->create()
        );

        $article = Article::factory()->create();

        $response = $this->assertAuthenticated()
           ->deleteJson(route('api.articles.destroy', $article), [

            ])
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);
    }
}
