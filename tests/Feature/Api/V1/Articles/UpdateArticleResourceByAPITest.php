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
 * Тестирование обновления ресурса `Article`.
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Articles\UpdateArticleResourceByAPITest.php
 */
class UpdateArticleResourceByAPITest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_update_article()
    {
        $user = $this->createUser();

        $article = Article::factory()
            ->for($user)
            ->for($user->currentTeam)
            ->create();

        $response = $this->assertGuest()
            ->putJson(route('api.articles.update', $article), [

            ])
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_update_article()
    {
        $user = $this->loginSPA();

        $article = Article::factory()
            ->for($user)
            ->for($user->currentTeam)
            ->create();

        $response = $this->assertAuthenticated()
           ->putJson(route('api.articles.update', $article), [
                'title' => 'New title for old article',
            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonStructure([
                'links' => [
                    'self',
                ],
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'user_id',
                        'team_id',
                        'title',
                        'slug',
                        'teaser',
                        'content',
                        'meta_description',
                        'meta_keywords',
                        'meta_robots',
                        'on_mainpage',
                        'is_favorite',
                        'is_pinned',
                        'views',
                        'created_at','updated_at',
                    ],
                    'relationships',
                ],
            ]);
    }

    public function test_not_found_when_attempt_to_update_non_existent_resource()
    {
        $user = $this->loginSPA();

        $this->assertDatabaseCount('articles', 0);

        $response = $this->assertAuthenticated()
            ->getJson(route('api.articles.update', 'not.found'))
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
