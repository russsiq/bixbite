<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Articles;

use App\Models\Article;
use App\Models\User;
use App\Policies\ArticlePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\Fixtures\ArticleFixtures;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\ArticlesController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Articles\UpdateArticleResourceByAPITest.php
 */
class UpdateArticleResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = 'articles';

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_update_article'
     */
    public function test_guest_cannot_update_article()
    {
        $user = $this->createUser();
        $article = Article::factory()->for($user)->createOne();

        $response = $this->assertGuest()
            ->putJsonApi('update', $article)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_update_article'
     */
    public function test_user_without_ability_cannot_update_article()
    {
        $this->denyPolicyAbility(ArticlePolicy::class, ['update']);

        $user = $this->loginSPA();
        $article = Article::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $article)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_cannot_update_article_without_minimal_provided_data'
     *
     * @NB  Ничего общего с ситуацией автосохранения – **нет**.
     */
    public function test_user_with_ability_cannot_update_article_without_minimal_provided_data()
    {
        $this->allowPolicyAbility(ArticlePolicy::class, ['update']);

        $user = $this->loginSPA();
        $article = Article::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $article)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_update_article_with_minimal_provided_data'
     */
    public function test_user_with_ability_can_update_article_with_minimal_provided_data()
    {
        $this->allowPolicyAbility(ArticlePolicy::class, ['update']);

        $user = $this->loginSPA();
        $article = Article::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $article, [
                'title' => 'New title for old article',
            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonPath('data.title', 'New title for old article')
            ->assertJsonStructure(
                ArticleFixtures::resource()
            );

        $this->assertDatabaseHas('articles', [
            'title' => 'New title for old article',
            'user_id' => $user->id,
        ]);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_update_article_with_minimal_provided_data'
     */
    public function test_super_admin_can_update_article_with_minimal_provided_data()
    {
        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $article = Article::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $article, [
                'title' => 'New title for old article',
            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonPath('data.title', 'New title for old article')
            ->assertJsonStructure(
                ArticleFixtures::resource()
            );

        $this->assertDatabaseHas('articles', [
            'title' => 'New title for old article',
            'user_id' => $user->id,
        ]);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_update_non_existent_article'
     */
    public function test_not_found_when_attempt_to_update_non_existent_article()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseCount('articles', 0)
            ->assertAuthenticated()
            ->putJsonApi('update', 'not.found')
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
