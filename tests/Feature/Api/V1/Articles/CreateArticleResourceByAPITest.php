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
use Tests\Feature\Api\V1\Articles\Fixtures\ArticleFixtures;
use Tests\TestCase;

/** @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Articles\CreateArticleResourceByAPITest.php */
class CreateArticleResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = 'articles';

    public function test_guest_cannot_create_article()
    {
        $response = $this->assertGuest()
            ->postJsonApi('store', [], [

            ])
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    public function test_user_without_ability_cannot_create_article()
    {
        $this->denyPolicyAbility(ArticlePolicy::class, ['create']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store', [], [

            ])
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    public function test_super_admin_can_create_article()
    {
        $super_admin = $this->loginSuperAdminSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store', [], [
                'title' => 'New article title',
            ])
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure(
                ArticleFixtures::resource()
            );
    }
}
