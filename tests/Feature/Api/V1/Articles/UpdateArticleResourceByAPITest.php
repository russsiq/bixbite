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
 * Тестирование обновления ресурса `Article`.
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Articles\UpdateArticleResourceByAPITest.php
 */
class UpdateArticleResourceByAPITest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_update_article()
    {
        $article = Article::factory()->create();

        $response = $this->assertGuest()
            ->putJson(route('api.articles.update', $article), [

            ])
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_update_article()
    {
        Sanctum::actingAs(
            $user = User::factory()->create()
        );

        $article = Article::factory()->create();

        $response = $this->assertAuthenticated()
           ->putJson(route('api.articles.update', $article), [

            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED);
    }

    public function test_not_found_when_attempt_to_update_non_existent_resource()
    {
        Sanctum::actingAs(
            $user = User::factory()->create()
        );

        $this->assertDatabaseCount('articles', 0);

        $response = $this->assertAuthenticated()
            ->getJson(route('api.articles.update', 'not.found'))
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
