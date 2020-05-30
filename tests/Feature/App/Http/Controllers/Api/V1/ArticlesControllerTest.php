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
            ->withHeaders([
                'Authorization' => 'Bearer '.$user->generateApiToken(),
            ])
            ->getJson(route('api.articles.index'))
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
        $this->actingAs($owner = $this->createImprovisedUser('owner'))
            ->withHeaders([
                'Authorization' => 'Bearer '.$owner->generateApiToken(),
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

        return $this->actingAs($owner = $this->createImprovisedUser('owner'))
            ->withHeaders([
                'Authorization' => 'Bearer '.$owner->generateApiToken()
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
     * @test
     * @covers ::store
     *
     * Ошибка аутентификации при создании записи гостем.
     * @return void
     */
    public function testAuthenticationFailedWhileGuestCreateArticle(): void
    {
        $this->postJson(route('api.articles.store'))
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @covers ::store
     *
     * Ошибка аутентификации при создании записи пользователем.
     * @return void
     */
    public function testAuthenticationFailedWhileUserCreateArticle(): void
    {
        $this->actingAs($user = $this->createImprovisedUser())
            ->postJson(route('api.articles.store'))
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @covers ::store
     *
     * Доступ запрещен при создании записи пользователем.
     * @return void
     */
    public function testForbiddenWhileUserCreateArticle(): void
    {
        $this->actingAs($user = $this->createImprovisedUser())
            ->withHeaders([
                'Authorization' => 'Bearer '.$user->generateApiToken(),
            ])
            ->postJson(route('api.articles.store'))
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @covers ::store
     *
     * Собственник сайта мог бы создать запись,
     * но он не передал никаких данных по ней.
     * @return void
     */
    public function testOwnerCanNotCreateArticle(): void
    {
        $this->actingAsOwner()
            ->postJson(route('api.articles.store'))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     * @covers ::store
     *
     * Собственник сайта создает запись с минимальным набором данных.
     * @return void
     */
    public function testOwnerCanCreateArticleWithMinimalDataProvided(): void
    {
        $this->actingAsOwner()
            ->postJson(route('api.articles.store'), [
                'title' => 'Draft'
            ])
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonPath('data.title', 'Draft');
    }

    /**
     * @test
     * @covers ::update
     *
     * Ошибка аутентификации при редактировании записи гостем.
     * @return void
     */
    public function testAuthenticationFailedWhileGuestUpdateArticle(): void
    {
        $article = factory(Article::class)->create();

        $this->putJson(route('api.articles.update', $article->id), [
                'title' => 'New title'
            ])
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @covers ::update
     *
     * Доступ запрещен при редактировании записи пользователем.
     * @return void
     */
    public function testForbiddenWhileUserUpdateArticle(): void
    {
        $article = factory(Article::class)->create();

        $this->actingAs($user = $this->createImprovisedUser())
            ->withHeaders([
                'Authorization' => 'Bearer '.$user->generateApiToken(),
            ])
            ->putJson(route('api.articles.update', $article->id), [
                'title' => 'New title'
            ])
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @covers ::update
     *
     * Собственник сайта отредактировал запись, даже если
     * не предоставил данных для обновления.
     * Ситуация: автосохранения записи.
     * @return void
     */
    public function testOwnerCanUpdateArticleWithoutDataProvided(): void
    {
        $article = factory(Article::class)->create();

        $this->actingAsOwner()
            ->putJson(route('api.articles.update', $article->id))
            ->assertStatus(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * @test
     * @covers ::update
     *
     * Собственник сайта редактирует запись с минимальным набором данных.
     * @return void
     */
    public function testOwnerCanUpdateArticleWithMinimalDataProvided(): void
    {
        $article = factory(Article::class)->create();

        $this->actingAsOwner()
            ->putJson(route('api.articles.update', $article->id), [
                'title' => 'New title'
            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonPath('data.title', 'New title');
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

    protected function actingAsOwner()
    {
        return $this->actingAs($owner = $this->createImprovisedUser('owner'))
            ->withHeaders([
                'Authorization' => 'Bearer '.$owner->generateApiToken(),
            ]);
    }
}
