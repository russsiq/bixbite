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
 * Тестирование удаления ресурса `Article`.
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Articles\DeleteArticleResourceByAPITest.php
 */
class DeleteArticleResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_RESOURCE = 'articles';

    public function test_guest_cannot_delete_article()
    {
        $user = $this->createUser();

        $article = Article::factory()
            ->for($user)
            ->create();

        $response = $this->assertGuest()
            ->deleteJsonApi('destroy', $article)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    public function test_user_without_ability_cannot_delete_article()
    {
        $this->denyPolicyAbility(ArticlePolicy::class, ['delete']);

        $user = $this->loginSPA();

        $article = Article::factory()
            ->for($user)
            ->create();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $article)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    public function test_super_admin_can_delete_article()
    {
        $super_admin = $this->loginSuperAdminSPA();

        $article = Article::factory()
            ->for($super_admin)
            ->create();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $article)
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);
    }

    public function test_not_found_when_attempt_to_delete_non_existent_resource()
    {
        $user = $this->loginSPA();

        $this->assertDatabaseCount('articles', 0);

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', 'not.found')
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
