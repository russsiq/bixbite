<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Articles;

use App\Models\Article;
use App\Models\User;
use App\Policies\ArticlePolicy;
use Database\Seeders\TestContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\Fixtures\ArticleFixtures;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\ArticlesController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Articles\FetchArticleResourceByAPITest.php
 */
class FetchArticleResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = 'articles';

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_fetch_articles'
     */
    public function test_guest_cannot_fetch_articles()
    {
        $response = $this->assertGuest()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_fetch_articles'
     */
    public function test_user_without_ability_cannot_fetch_articles()
    {
        $this->denyPolicyAbility(ArticlePolicy::class, ['viewAny']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_fetch_articles'
     */
    public function test_user_with_ability_can_fetch_articles()
    {
        $this->allowPolicyAbility(ArticlePolicy::class, ['viewAny']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_fetch_articles'
     */
    public function test_super_admin_can_fetch_articles()
    {
        $super_admin = $this->loginSuperAdminSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_fetch_specific_article'
     */
    public function test_guest_cannot_fetch_specific_article()
    {
        $user = $this->createUser();
        $article = Article::factory()->for($user)->createOne();

        $response = $this->assertGuest()
            ->getJsonApi('show', $article)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_fetch_specific_article'
     */
    public function test_user_without_ability_cannot_fetch_specific_article()
    {
        $this->denyPolicyAbility(ArticlePolicy::class, ['view']);

        $user = $this->loginSPA();
        $article = Article::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $article)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_fetch_specific_article'
     */
    public function test_user_with_ability_can_fetch_specific_article()
    {
        $this->allowPolicyAbility(ArticlePolicy::class, ['view']);

        $user = $this->loginSPA();
        $article = Article::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $article)
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_fetch_specific_article'
     */
    public function test_super_admin_can_fetch_specific_article()
    {
        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $article = Article::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $article)
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_each_received_article_contains_required_fields'
     */
    public function test_each_received_article_contains_required_fields()
    {
        $this->allowPolicyAbility(ArticlePolicy::class, ['viewAny']);

        $user = $this->loginSPA();
        $articlesCount = mt_rand(4, 12);
        $articles = Article::factory($articlesCount)->for($user)->create();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT)
            ->assertJsonCount($articlesCount, 'data')
            ->assertJsonStructure(
                ArticleFixtures::collection()
            );
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_single_received_article_contains_required_fields'
     */
    public function test_single_received_article_contains_required_fields()
    {
        $this->allowPolicyAbility(ArticlePolicy::class, ['view']);

        $user = $this->loginSPA();
        $article = Article::factory()->for($user)->create();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $article)
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure(
                ArticleFixtures::resource()
            );
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_fetch_single_non_existent_article'
     */
    public function test_not_found_when_attempt_to_fetch_single_non_existent_article()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseCount('articles', 0)
            ->assertAuthenticated()
            ->getJsonApi('show', '888')
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
