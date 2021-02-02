<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Articles;

use App\Models\Article;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * Тестирование получения ресурса `Article`.
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Articles\FetchArticleResourceByAPITest.php
 */
class FetchArticleResourceByAPITest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_fetch_articles()
    {
        $response = $this->assertGuest()
            ->getJson(route('api.articles.index'))
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_fetch_articles()
    {
        Sanctum::actingAs(
            $user = User::factory()->create()
        );

        $response = $this->assertAuthenticated()
            ->getJson(route('api.articles.index'))
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    public function test_each_received_article_contains_required_fields()
    {
        Sanctum::actingAs(
            $user = User::factory()->create()
        );

        $articles = Article::factory($countArticles = 5)
            ->for($user)
            ->create();

        $response = $this->assertAuthenticated()
            ->getJson(route('api.articles.index'))
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount($countArticles, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'user_id',
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
                            'created_at',
                            'updated_at',
                        ],
                        'relationships',
                    ],
                ],
                'links' => [
                    'self',
                ],
            ]);
    }

    public function test_guest_cannot_fetch_specific_article()
    {
        $article = Article::factory()
            ->for(User::factory()->create())
            ->create();

        $response = $this->assertGuest()
            ->getJson(route('api.articles.show', $article))
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_fetch_specific_article()
    {
        Sanctum::actingAs(
            $user = User::factory()->create()
        );

        $article = Article::factory()
            ->for($user)
            ->create();

        $response = $this->assertAuthenticated()
            ->getJson(route('api.articles.show', $article))
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    public function test_separately_received_article_contains_required_fields()
    {
        Sanctum::actingAs(
            $user = User::factory()->create()
        );

        $article = Article::factory()
            ->for($user)
            ->create();

        $response = $this->assertAuthenticated()
            ->getJson(route('api.articles.show', $article))
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([
                'links' => [
                    'self',
                ],
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'user_id',
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
                        'created_at',
                        'updated_at',
                    ],
                    'relationships',
                ],
            ]);
    }

    public function test_not_found_when_attempt_to_fetch_single_non_existent_resource()
    {
        Sanctum::actingAs(
            $user = User::factory()->create()
        );

        $this->assertDatabaseCount('articles', 0);

        $response = $this->assertAuthenticated()
            ->getJson(route('api.articles.show', 'not.found'))
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
