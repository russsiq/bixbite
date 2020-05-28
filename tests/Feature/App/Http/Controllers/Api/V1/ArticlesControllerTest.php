<?php

namespace Tests\Feature\App\Http\Controllers\Api\V1;

// Тестируемый класс.
use App\Http\Controllers\Api\V1\ArticlesController;

// Сторонние зависимости.
use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\ArticlesController
 */
class ArticlesControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @covers ::index
     *
     * Ошибка аутентификации при просмотре списка записей гостем.
     * @return void
     */
    public function testAuthenticationFailedWhileGuestListingArticles(): void
    {
        $this->getJson(route('api.articles.index'))
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @covers ::index
     *
     * Ошибка аутентификации при просмотре списка записей пользователем.
     * @return void
     */
    public function testAuthenticationFailedWhileUserListingArticles(): void
    {
        $this->actingAs($user = $this->createImprovisedUser())
            ->getJson(route('api.articles.index'))
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @covers ::index
     *
     * Доступ запрещен при просмотре списка записей пользователем.
     * @return void
     */
    public function testForbiddenWhileUserListingArticles(): void
    {
        $this->actingAs($user = $this->createImprovisedUser())
            ->getJson(route('api.articles.index'), [
                'Authorization' => 'Bearer '.$user->generateApiToken(),
            ])
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @covers ::index
     *
     * Собственник сайта получает пустой список записей.
     * @return void
     */
    public function testOwnerRecivesEmptyArticlesList(): void
    {
        $this->actingAs($user = $this->createImprovisedUser('owner'))
            ->withHeaders([
                'Authorization' => 'Bearer '.$user->generateApiToken(),
            ])
            ->getJson(route('api.articles.index'))
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * @test
     * @covers ::index
     *
     * Собственник сайта получает список записей
     * согласно количеству в ответе.
     * @return TestResponse
     */
    public function testOwnerRecivesArticlesByCount(): TestResponse
    {
        $articles = factory(Article::class, $articlesCount = mt_rand(4, 12))
            ->create();

        return $this->actingAs($user = $this->createImprovisedUser('owner'))
            ->withHeaders([
                'Authorization' => 'Bearer '.$user->generateApiToken()
            ])
            ->getJson(route('api.articles.index', [
                'limit' => $articlesCount
            ]))
            ->assertJsonCount($articlesCount, 'data');
    }

    /**
     * @test
     * @covers ::index
     * @depends testOwnerRecivesArticlesByCount
     *
     * Собственник сайта получает список записей
     * согласно статусу ответа.
     * @return void
     */
    public function testOwnerRecivesArticlesByStatus(TestResponse $response): void
    {
        $response->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * @test
     * @covers ::index
     * @depends testOwnerRecivesArticlesByCount
     *
     * Собственник сайта получает список записей
     * согласно JSON-структуре ответа.
     * @return void
     */
    public function testOwnerRecivesArticlesByJsonStructure(TestResponse $response): void
    {
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'title',
                    'content',
                ]
            ]
        ]);
    }

    /**
     * Создать импровизированного пользователя.
     * @param  string  $role
     * @return User
     */
    protected function createImprovisedUser(string $role = 'user', array $attributes = []): User
    {
        return factory(User::class)
            ->states($role)
            ->create($attributes);
    }
}
