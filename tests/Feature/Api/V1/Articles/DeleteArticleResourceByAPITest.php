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
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\ArticlesController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Articles\DeleteArticleResourceByAPITest.php
 */
class DeleteArticleResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = 'articles';

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_delete_article'
     */
    public function test_guest_cannot_delete_article()
    {
        $user = $this->createUser();
        $article = Article::factory()->for($user)->createOne();

        $response = $this->assertGuest()
            ->deleteJsonApi('destroy', $article)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_delete_article'
     */
    public function test_user_without_ability_cannot_delete_article()
    {
        $this->denyPolicyAbility(ArticlePolicy::class, ['delete']);

        $user = $this->loginSPA();
        $article = Article::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $article)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_delete_article'
     */
    public function test_user_with_ability_can_delete_article()
    {
        $this->allowPolicyAbility(ArticlePolicy::class, ['delete']);

        $user = $this->loginSPA();
        $article = Article::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $article)
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
        ]);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_delete_article'
     */
    public function test_super_admin_can_delete_article()
    {
        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $article = Article::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $article)
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
        ]);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_delete_non_existent_article'
     */
    public function test_not_found_when_attempt_to_delete_non_existent_article()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseCount('articles', 0)
            ->assertAuthenticated()
            ->deleteJsonApi('destroy', 'not.found')
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
