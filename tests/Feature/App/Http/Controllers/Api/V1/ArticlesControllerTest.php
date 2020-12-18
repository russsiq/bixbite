<?php declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers\Api\V1;

// Тестируемый класс.
use App\Http\Controllers\Api\V1\ArticlesController;

// Сторонние зависимости.
use App\Models\Article;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\ArticlesController
 *
 * @cmd vendor\bin\phpunit tests\Feature\App\Http\Controllers\Api\V1\ArticlesControllerTest.php
 *
 * @NB Приложение использует двойную аутентификацию
 * для доступа к API-ресурсам.
 * Тестируя только api-драйвер аутентификации,
 * мы не сможем сымитировать действительность.
 *
 * @NB Метод `actingAs` ведёт себя по-разному с `null` и `web` драйверами,
 * хотя последний должен использоваться как драйвер по-умолчанию.
 *
 * @NB При указании драйвера `api`
 * заголовок `Authorization` со значением `Bearer`
 * непредсказуемо влияет на ответ.
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
     * Ошибка аутентификации при просмотре списка записей собственником сайта.
     * @return void
     */
    public function testAuthenticationFailedWhileOwnerListingArticles(): void
    {
        $this->actingAs($owner = $this->createImprovisedUser('owner'))
            ->postJson(route('api.articles.index'))
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
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
        $this->actingAsOwner()
            ->getJson(route('api.articles.index'))
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * @test
     * @covers ::index
     *
     * Собственник сайта получает список записей.
     * @return void
     */
    public function testOwnerRecivesArticles(): void
    {
        $articlesCount = mt_rand(4, 12);

        $articles = Article::factory()->count($articlesCount)->create();

        $this->actingAsOwner()
            ->getJson(route('api.articles.index', [
                'limit' => $articlesCount
            ]))
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT)
            ->assertJsonCount($articlesCount, 'data')
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
     * Ошибка аутентификации при создании записи собственником сайта.
     * @return void
     */
    public function testAuthenticationFailedWhileOwnerCreateArticle(): void
    {
        $this->actingAs($owner = $this->createImprovisedUser('owner'))
            ->postJson(route('api.articles.store'))
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
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
        $this->actingAs($owner = $this->createImprovisedUser('owner'))
            ->withHeaders([
                'Authorization' => 'Bearer '.$owner->generateApiToken(),
            ])
            ->postJson(route('api.articles.store'), [
                'title' => 'Draft'
            ])
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonPath('data.title', 'Draft');

        $this->assertDatabaseHas('articles', [
                'title' => 'Draft',
                'user_id' => $owner->id,

            ]);
    }

    /**
     * @test
     * @covers ::show
     *
     * Ошибка аутентификации при просмотре Записи гостем.
     * @return void
     */
    public function testAuthenticationFailedWhileGuestShowArticle(): void
    {
        $article = Article::factory()->createOne();

        $this->getJson(route('api.articles.show', $article))
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @covers ::show
     *
     * Ошибка аутентификации при просмотре Записи пользователем.
     * @return void
     */
    public function testAuthenticationFailedWhileUserShowArticle(): void
    {
        $article = Article::factory()->createOne();

        $this->actingAs($user = $this->createImprovisedUser())
            ->getJson(route('api.articles.show', $article))
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @covers ::show
     *
     * Доступ запрещен при просмотре Записи пользователем.
     * @return void
     */
    public function testForbiddenWhileUserShowArticle(): void
    {
        $article = Article::factory()->createOne();

        $this->actingAs($user = $this->createImprovisedUser())
            ->withHeaders([
                'Authorization' => 'Bearer '.$user->generateApiToken(),
            ])
            ->getJson(route('api.articles.show', $article))
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @covers ::show
     *
     * Ошибка аутентификации при просмотре Записи собственником сайта.
     * @return void
     */
    public function testForbiddenWhileOwnerShowArticle(): void
    {
        $article = Article::factory()->createOne();

        $this->actingAs($owner = $this->createImprovisedUser('owner'))
            ->getJson(route('api.articles.show', $article))
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @covers ::show
     *
     * Собственник сайта получает Запись.
     * @return void
     */
    public function testOwnerRecivesArticle(): void
    {
        $article = Article::factory()->createOne();

        $this->actingAsOwner()
            ->getJson(route('api.articles.show', $article))
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'content',
                    'categories',
                    'files',
                    'tags',
                    'user',

                ],

            ]);
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
        $article = Article::factory()->createOne();

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
        $article = Article::factory()->createOne();

        $this->actingAs($user = $this->createImprovisedUser())
            ->putJson(route('api.articles.update', $article->id), [
                'title' => 'New title'
            ])
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);

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
     * Собственник сайта не отредактировал запись, так как
     * не предоставил данных для обновления.
     * Ничего общего с ситуацией автосохранения – **нет**.
     * @return void
     */
    public function testOwnerCanNotUpdateArticleWithoutDataProvided(): void
    {
        $article = Article::factory()->createOne();

        $this->actingAsOwner()
            ->putJson(route('api.articles.update', $article->id))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
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
        $article = Article::factory()->createOne();

        $this->actingAsOwner()
            ->putJson(route('api.articles.update', $article->id), [
                'title' => 'New title',
                'created_at' => date('Y-m-d H:i:s'),

            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonPath('data.title', 'New title');

        $this->assertDatabaseHas('articles', [
                'title' => 'New title',

            ]);
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
        $articles = Article::factory()->count(3)->create();

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
        $this->actingAs($user = $this->createImprovisedUser())
            ->withHeaders([
                'Authorization' => 'Bearer '.$user->generateApiToken(),
            ])
            ->putJson(route('api.articles.massUpdate'), [
                'articles' => [1,2,3]
            ])
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @covers ::massUpdate
     *
     * Собственник сайта мог бы массово отредактировать записи,
     * но он не передал некоторые данные по ним.
     * @return void
     */
    public function testOwnerCanNotMassUpdateArticleWithoutDataProvided(): void
    {
        $articles = Article::factory()->count(3)->create();

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
        $articlesCount = mt_rand(4, 8);

        $articles = Article::factory()->count($articlesCount)->create();

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
            ]);
    }

    /**
     * @test
     * @covers ::destroy
     *
     * Ошибка аутентификации при удалении Записи гостем.
     * @return void
     */
    public function testAuthenticationFailedWhileGuestDestroyArticle(): void
    {
        $article = Article::factory()->createOne();

        $this->deleteJson(route('api.articles.destroy', $article))
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @covers ::destroy
     *
     * Ошибка аутентификации при удалении Записи пользователем.
     * @return void
     */
    public function testAuthenticationFailedWhileUserDestroyArticle(): void
    {
        $article = Article::factory()->createOne();

        $this->actingAs($user = $this->createImprovisedUser())
            ->deleteJson(route('api.articles.destroy', $article))
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @covers ::destroy
     *
     * Доступ запрещен при удалении Записи пользователем.
     * @return void
     */
    public function testForbiddenWhileUserDestroyArticle(): void
    {
        $article = Article::factory()->createOne();

        $this->actingAs($user = $this->createImprovisedUser())
            ->withHeaders([
                'Authorization' => 'Bearer '.$user->generateApiToken(),
            ])
            ->deleteJson(route('api.articles.destroy', $article))
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @covers ::destroy
     *
     * Ошибка аутентификации при удалении Записи собственником сайта.
     * @return void
     */
    public function testForbiddenWhileOwnerDestroyArticle(): void
    {
        $article = Article::factory()->createOne();

        $this->actingAs($owner = $this->createImprovisedUser('owner'))
            ->deleteJson(route('api.articles.destroy', $article))
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @covers ::destroy
     *
     * Собственник сайта удаляет Запись.
     * @return void
     */
    public function testOwnerDestroyArticle(): void
    {
        $article = Article::factory()->createOne();

        $this->actingAsOwner()
            ->deleteJson(route('api.articles.destroy', $article))
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT)
            ->assertNoContent();

        $this->assertDatabaseMissing('articles', [
                'id' => $article->id,

            ]);
    }

    /**
     * Создать импровизированного пользователя.
     * @param  string  $role
     * @param  array  $attributes
     * @return User
     */
    protected function createImprovisedUser(string $role = 'user', array $attributes = []): User
    {
        return User::factory()
            ->createOne(array_merge($attributes, [
                'role' => $role,

            ]));
    }

    /**
     * Выполнять дальнейшие действия
     * от имени собственника сайта
     * с передачей заголовка аутентификации.
     * @return self
     */
    protected function actingAsOwner(): self
    {
        return $this->actingAs($owner = $this->createImprovisedUser('owner'))
            ->withHeaders([
                'Authorization' => 'Bearer '.$owner->generateApiToken(),
            ]);
    }
}
