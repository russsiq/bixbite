<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Articles;

use App\Models\Article;
use App\Models\User;
use App\Policies\ArticlePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Mockery\MockInterface;
use Tests\Feature\Api\V1\Articles\Fixtures\ArticleFixtures;
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

    public function test_user_without_permission_cannot_fetch_articles()
    {
        $this->partialMock(ArticlePolicy::class, function (MockInterface $mock) {
            return $mock->shouldReceive('viewAny')
                ->once()
                ->andReturn(false);
        });

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->getJson(route('api.articles.index'))
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    public function test_guest_cannot_fetch_specific_article()
    {
        $user = $this->createUser();

        $article = Article::factory()
            ->for($user)
            ->create();

        $response = $this->assertGuest()
            ->getJson(route('api.articles.show', $article))
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    public function test_user_without_permission_cannot_fetch_specific_article()
    {
        $this->partialMock(ArticlePolicy::class, function (MockInterface $mock) {
            return $mock->shouldReceive('view')
                ->once()
                ->andReturn(false);
        });

        $user = $this->loginSPA();

        $article = Article::factory()
            ->for($user)
            ->create();

        $response = $this->assertAuthenticated()
            ->getJson(route('api.articles.show', $article))
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    public function test_each_received_article_contains_required_fields()
    {
        $super_admin = $this->loginSuperAdminSPA();

        $articles = Article::factory($countArticles = 5)
            ->for($super_admin)
            ->create();

        $response = $this->assertAuthenticated()
            ->getJson(route('api.articles.index'))
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT)
            ->assertJsonCount($countArticles, 'data')
            ->assertJsonStructure(
                ArticleFixtures::collection()
            );
    }

    public function test_single_received_article_contains_required_fields()
    {
        $super_admin = $this->loginSuperAdminSPA();

        $article = Article::factory()
            ->for($super_admin)
            ->create();

        $response = $this->assertAuthenticated()
            ->getJson(route('api.articles.show', $article))
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure(
                ArticleFixtures::resource()
            );
    }

    public function test_not_found_when_attempt_to_fetch_single_non_existent_resource()
    {
        $user = $this->loginSPA();

        $this->assertDatabaseCount('articles', 0);

        $response = $this->assertAuthenticated()
            ->getJson(route('api.articles.show', 'not.found'))
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
