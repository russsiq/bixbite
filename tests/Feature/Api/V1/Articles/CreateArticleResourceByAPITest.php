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
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Articles\CreateArticleResourceByAPITest.php
 */
class CreateArticleResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = 'articles';

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_create_article'
     */
    public function test_guest_cannot_create_article()
    {
        $response = $this->assertGuest()
            ->postJsonApi('store')
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_create_article'
     */
    public function test_user_without_ability_cannot_create_article()
    {
        $this->denyPolicyAbility(ArticlePolicy::class, ['create']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store')
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_cannot_create_article_without_minimal_provided_data'
     */
    public function test_user_with_ability_cannot_create_article_without_minimal_provided_data()
    {
        $this->allowPolicyAbility(ArticlePolicy::class, ['create']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store')
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_create_article_with_minimal_provided_data'
     */
    public function test_user_with_ability_can_create_article_with_minimal_provided_data()
    {
        $this->allowPolicyAbility(ArticlePolicy::class, ['create']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store', [], [
                'title' => 'Draft'
            ])
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonPath('data.title', 'Draft')
            ->assertJsonStructure(
                ArticleFixtures::resource()
            );

        $this->assertDatabaseHas('articles', [
            'title' => 'Draft',
            'user_id' => $user->id,
        ]);
    }

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_create_article_with_minimal_provided_data'
     */
    public function test_super_admin_can_create_article_with_minimal_provided_data()
    {
        $super_admin = $this->loginSuperAdminSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store', [], [
                'title' => 'Draft'
            ])
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonPath('data.title', 'Draft')
            ->assertJsonStructure(
                ArticleFixtures::resource()
            );

        $this->assertDatabaseHas('articles', [
            'title' => 'Draft',
            'user_id' => $super_admin->id,
        ]);
    }
}
