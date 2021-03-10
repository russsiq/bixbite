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
use Tests\Feature\Api\V1\Articles\Fixtures\ArticleFixtures;
use Tests\TestCase;

/**
 * Тестирование обновления ресурса `Article`.
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Articles\UpdateArticleResourceByAPITest.php
 */
class UpdateArticleResourceByAPITest extends TestCase
{
    use InteractsWithPolicy;
    use RefreshDatabase;

    public function test_guest_cannot_update_article()
    {
        $user = $this->createUser();

        $article = Article::factory()
            ->for($user)
            ->create();

        $response = $this->assertGuest()
            ->putJson(route('api.articles.update', $article), [

            ])
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    public function test_user_without_ability_cannot_update_article()
    {
        $this->denyPolicyAbility(ArticlePolicy::class, ['update']);

        $user = $this->loginSPA();

        $article = Article::factory()
            ->for($user)
            ->create();

        $response = $this->assertAuthenticated()
           ->putJson(route('api.articles.update', $article), [

            ])
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    public function test_super_admin_can_update_article()
    {
        $super_admin = $this->loginSuperAdminSPA();

        $article = Article::factory()
            ->for($super_admin)
            ->create();

        $response = $this->assertAuthenticated()
            ->putJson(route('api.articles.update', $article), [
                'title' => 'New title for old article',
                'relationships' => [],
            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonStructure(
                ArticleFixtures::resource()
            );
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
