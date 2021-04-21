<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Articles;

use App\Exceptions\JsonApiException;
use App\Models\Article;
use App\Models\User;
use App\Policies\ArticlePolicy;
use Database\Seeders\TestContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Feature\Api\V1\Articles\Fixtures\ArticleFixtures;
use Tests\Feature\Api\V1\JsonApiTrait;
use Tests\TestCase;

/**
 * Тестирование получения ресурса `Article`.
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Articles\FetchArticleResourceByAPITest.php
 */
class FetchArticleResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_RESOURCE = 'articles';

    // public function test_guest_cannot_fetch_articles()
    // {
    //     $response = $this->assertGuest()
    //         ->getJsonApi('index')
    //         ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    // }

    // public function test_user_without_ability_cannot_fetch_articles()
    // {
    //     $this->denyPolicyAbility(ArticlePolicy::class, ['viewAny']);

    //     $user = $this->loginSPA();

    //     $response = $this->assertAuthenticated()
    //         ->getJsonApi('index')
    //         ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    // }

    // public function test_guest_cannot_fetch_specific_article()
    // {
    //     $user = $this->createUser();

    //     $article = Article::factory()
    //         ->for($user)
    //         ->create();

    //     $response = $this->assertGuest()
    //         ->getJsonApi('show', $article)
    //         ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    // }

    // public function test_user_without_ability_cannot_fetch_specific_article()
    // {
    //     $this->denyPolicyAbility(ArticlePolicy::class, ['view']);

    //     $user = $this->loginSPA();

    //     $article = Article::factory()
    //         ->for($user)
    //         ->create();

    //     $response = $this->assertAuthenticated()
    //         ->getJsonApi('show', $article)
    //         ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    // }

    // public function test_each_received_article_contains_required_fields()
    // {
    //     $super_admin = $this->loginSuperAdminSPA();

    //     $articles = Article::factory($countArticles = 5)
    //         ->for($super_admin)
    //         ->create();

    //     $response = $this->assertAuthenticated()
    //         ->getJsonApi('index')
    //         ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT)
    //         ->assertJsonCount($countArticles, 'data')
    //         ->assertJsonStructure(
    //             ArticleFixtures::collection()
    //         );
    // }

    // public function test_single_received_article_contains_required_fields()
    // {
    //     $super_admin = $this->loginSuperAdminSPA();

    //     $article = Article::factory()
    //         ->for($super_admin)
    //         ->create();

    //     $response = $this->assertAuthenticated()
    //         ->getJsonApi('show', $article)
    //         ->assertStatus(JsonResponse::HTTP_OK)
    //         ->assertJsonStructure(
    //             ArticleFixtures::resource()
    //         );
    // }

    // public function test_not_found_when_attempt_to_fetch_single_non_existent_resource()
    // {
    //     $user = $this->loginSPA();

    //     $this->assertDatabaseCount('articles', 0);

    //     $response = $this->assertAuthenticated()
    //         ->getJsonApi('show', 'not.found')
    //         ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    // }

    public function test_fetching_data_by_json_api_specification_v1_1()
    {
        // $this->expectException(ValidationException::class);
        // $this->withoutExceptionHandling([ValidationException::class,JsonApiException::class]);

        $this->seed(TestContentSeeder::class);

        $this->loginSuperAdminSPA();

        $title = 'Quo unde sint praesentium.';

        // Article::whereId($id = 8)->update([
        //     'title' => $title,
        // ]);

        // dd(Article::with(['user' => fn($query) => $query->addSelect(['id','name','email'])])->whereId($id = 8)->get());

        $response = $this->assertAuthenticated()
            ->getJsonApi('index', [
                'include=atachments, categories, comments.user, tags, user'.
                '&fields[articles]=title,user_id'.
                // '&fields[user]=email'.
                '&filter[0][field]=content&filter[0][operator]=contains&filter[0][query_1]='.substr($title, 4, 8).
                '&filter_match=or'.
                '&sort=-created_at,user.name'.
                '&page[number]=1&page[size]=8'
            ]);

        ! empty($response->original['errors']) && dd($response->original['errors']);

        $response->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);
        $response->assertJson([
                'data' => [
                    [
                        'id' => 8,
                        'type' => 'articles',
                        'attributes' => [
                            'title' => $title,
                        ],
                        'relationships' => [
                            //
                        ],
                        'links' => [
                            //
                        ],
                    ],
                ],
                'links' => [
                    //
                ],
            ])
            ;
    }
}
