<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Articles;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
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
        $user = $this->createUser();

        $article = Article::factory()
            ->for($user)
            ->for($user->currentTeam)
            ->create();

        $response = $this->assertGuest()
            ->deleteJson(route('api.articles.destroy', $article), [

            ])
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_delete_article()
    {
        $user = $this->loginSPA();

        $article = Article::factory()
            ->for($user)
            ->for($user->currentTeam)
            ->create();

        $response = $this->assertAuthenticated()
           ->deleteJson(route('api.articles.destroy', $article), [

            ])
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);
    }

    public function test_not_found_when_attempt_to_delete_non_existent_resource()
    {
        $user = $this->loginSPA();

        $this->assertDatabaseCount('articles', 0);

        $response = $this->assertAuthenticated()
            ->getJson(route('api.articles.destroy', 'not.found'))
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
