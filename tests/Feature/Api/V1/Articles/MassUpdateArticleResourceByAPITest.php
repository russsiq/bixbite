<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Articles;

use App\Models\Article;
use App\Models\User;
use App\Policies\ArticlePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\Fixtures\ArticleFixtures;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\ArticlesController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Articles\MassUpdateArticleResourceByAPITest.php
 */
class MassUpdateArticleResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = 'articles';

    /**
     * @covers ::massUpdate
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_mass_update_article'
     */
    public function test_guest_cannot_mass_update_article()
    {
        $user = $this->createUser();

        $response = $this->assertGuest()
            ->putJsonApi('massUpdate')
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::massUpdate
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_mass_update_article'
     */
    public function test_user_without_ability_cannot_mass_update_article()
    {
        $this->denyPolicyAbility(ArticlePolicy::class, ['massUpdate']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->putJsonApi('massUpdate', [], [])
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::massUpdate
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_cannot_mass_update_article_without_minimal_provided_data'
     */
    public function test_user_with_ability_cannot_mass_update_article_without_minimal_provided_data()
    {
        $this->allowPolicyAbility(ArticlePolicy::class, ['massUpdate']);

        $user = $this->loginSPA();
        $articlesCount = mt_rand(2, 5);
        $articles = Article::factory($articlesCount)->for($user)->create();

        $this->assertAuthenticated()
            ->putJsonApi('massUpdate', [], [])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'articles', 'mass_action',
            ]);
    }

    /**
     * @covers ::massUpdate
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_cannot_mass_update_article_without_minimal_provided_data'
     */
    public function test_super_admin_cannot_mass_update_article_without_minimal_provided_data()
    {
        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $articlesCount = mt_rand(2, 5);
        $articles = Article::factory($articlesCount)->for($user)
            ->unPublished()->create();

        $this->assertAuthenticated();

        // Супер-админ сайта не указал никаких данных.
        $this->putJsonApi('massUpdate', [], [])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'articles', 'mass_action',
            ]);

        // Супер-админ сайта не указал, что изменять в записях.
        $this->putJsonApi('massUpdate', [], [
                'articles' => $articles->modelKeys()
            ])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('mass_action');

        // Супер-админ сайта не указал какие записи необходимо обновить.
        $this->putJsonApi('massUpdate', [], [
                'mass_action' => 'draft'
            ])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('articles');
    }

    /**
     * @covers ::massUpdate
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_mass_update_article_with_minimal_provided_data'
     */
    public function test_user_with_ability_can_mass_update_article_with_minimal_provided_data()
    {
        $this->allowPolicyAbility(ArticlePolicy::class, ['massUpdate']);

        $user = $this->loginSPA();
        $articlesCount = mt_rand(2, 5);
        $articles = Article::factory($articlesCount)->for($user)
            ->unPublished()->create();

        $response = $this->assertAuthenticated()
            ->putJsonApi('massUpdate', [], [
                'articles' => $articles->modelKeys(),
                'mass_action' => 'draft',
            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonCount($articlesCount, 'data')
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', $articlesCount, fn (AssertableJson $json) =>
                    $json->where('id', $articles->first()->value('id'))
                        ->where('state', 0)
                        ->etc()
                )
            )
            ->assertJsonStructure([
                'data' => [
                    '*' => ArticleFixtures::resource()['data']
                ]
            ]);
    }

    /**
     * @covers ::massUpdate
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_mass_update_article_with_minimal_provided_data'
     */
    public function test_super_admin_can_mass_update_article_with_minimal_provided_data()
    {
        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $articlesCount = mt_rand(2, 5);
        $articles = Article::factory($articlesCount)->for($user)
            ->unPublished()->create();

        $response = $this->assertAuthenticated()
            ->putJsonApi('massUpdate', [], [
                'articles' => $articles->modelKeys(),
                'mass_action' => 'draft',
            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonCount($articlesCount, 'data')
            ->assertJsonPath('data.0.state', 0)
            ->assertJsonStructure([
                'data' => [
                    '*' => ArticleFixtures::resource()['data']
                ]
            ]);
    }
}
