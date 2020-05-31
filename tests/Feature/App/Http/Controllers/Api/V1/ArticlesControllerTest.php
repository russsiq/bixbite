<?php

namespace Tests\Feature\App\Http\Controllers\Api\V1;

// Тестируемый класс.
use App\Http\Controllers\Api\V1\ArticlesController;

// Сторонние зависимости.
use App\Models\Article;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
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
     * Функциональное покрытие метода `index` api-контроллера Записей.
     * @return void
     */
    public function testArticlesIndex(): void
    {
        $routeName = 'api.articles.index';

        // Ошибка аутентификации при просмотре списка записей гостем.
        $this->assertGuest()
            ->getJson(route($routeName))
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);

        // Ошибка аутентификации при просмотре списка записей пользователем.
        $this->actingAs($user = $this->createImprovisedUser())
            ->assertAuthenticated('api')
            ->getJson(route($routeName))
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);

        // Доступ запрещен при просмотре списка записей пользователем.
        $this->withHeaders([
                'Authorization' => 'Bearer '.$user->generateApiToken(),
            ])
            ->getJson(route($routeName))
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);

        /**
         * Прорабатываем варианты доступа собственника сайта к списку записей.
         */
        $articles = factory(Article::class, $articlesCount = mt_rand(4, 12))
            ->create();

        // Собственник сайта получает пустой список записей.
        $this->actingAs($owner = $this->createImprovisedUser('owner'))
            ->assertAuthenticated('api')
            ->getJson(route($routeName))
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);

        // Собственник сайта получает пустой список записей.
        $this->withHeaders([
                'Authorization' => 'Bearer '.$owner->generateApiToken(),
            ])
            ->getJson(route($routeName))
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);

        $this->getJson(route($routeName, [
                'limit' => $articlesCount
            ]))
            ->assertJsonCount($articlesCount, 'data')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT)
            ->assertJsonStructure([
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
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
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
     * @test
     * @covers ::massUpdate
     *
     * Ошибка аутентификации при массовом редактировании записей гостем.
     * @return void
     */
    public function testAuthenticationFailedWhileGuestMassUpdateArticle(): void
    {
        $articles = factory(Article::class, 3)->create();

        $this->putJson(route('api.articles.massUpdate'), [
                'articles' => $articles->modelKeys()
            ])
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @covers ::massUpdate
     *
     * Доступ запрещен при массовом редактировании записей пользователем.
     * @return void
     */
    public function testForbiddenWhileUserMassUpdateArticle(): void
    {
        $articles = factory(Article::class, 3)->create();

        $this->actingAs($user = $this->createImprovisedUser())
            ->withHeaders([
                'Authorization' => 'Bearer '.$user->generateApiToken(),
            ])
            ->putJson(route('api.articles.massUpdate'), [
                'articles' => $articles->modelKeys()
            ])
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @covers ::massUpdate
     *
     * Собственник сайта мог бы массово отредактировать записи,
     * но он не передал никаких данных по ним.
     * @return void
     */
    public function testOwnerCanNotMassUpdateArticleWithoutDataProvided(): void
    {
        $articles = factory(Article::class, 3)->create();

        // Собственник сайта не указал никаких данных.
        $this->actingAsOwner()
            ->putJson(route('api.articles.massUpdate'))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

        // Собственник сайта не указал, что изменять в записях.
        $this->actingAsOwner()
            ->putJson(route('api.articles.massUpdate'), [
                'articles' => $articles->modelKeys()
            ])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('mass_action');

        // Собственник сайта не указал какие записи необходимо обновить.
        $this->actingAsOwner()
            ->putJson(route('api.articles.massUpdate'), [
                'mass_action' => 'published'
            ])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('articles');
    }

    /**
     * @test
     * @covers ::massUpdate
     *
     * Собственник сайта массово отредактировал записи.
     * @return void
     */
    public function testOwnerMassCanUpdateArticleWithMinimalDataProvided(): void
    {
        $articles = factory(Article::class, $articlesCount = mt_rand(4, 8))->create();

        $this->actingAsOwner()
            ->putJson(route('api.articles.massUpdate'), [
                'articles' => $articles->modelKeys(),
                'mass_action' => 'published'
            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonCount($articlesCount, 'data')
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'title',
                        'content',
                    ]
                ]
            ]);;
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

    /**
     * Выполнять дальнейшие действия от имени зарегистрированного пользователя.
     * @param  UserContract  $user
     * @param  string|null  $driver
     * @return self
     *
     * @NB Перепишем родительский метод, так как
     * тестируем только api-драйвер аутентификации.
     */
    public function actingAs(UserContract $user, $driver = null): self
    {
        return parent::actingAs($user, 'api');
    }

    protected function actingAsOwner()
    {
        return $this->actingAs($owner = $this->createImprovisedUser('owner'))
            ->withHeaders([
                'Authorization' => 'Bearer '.$owner->generateApiToken(),
            ]);
    }
}
