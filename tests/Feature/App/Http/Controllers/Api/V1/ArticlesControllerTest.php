<?php declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ArticlesController;
use App\Models\Article;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
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
     * @covers ::massUpdate
     *
     * Ошибка аутентификации при массовом редактировании записей гостем.
     * @return void
     */
    public function testAuthenticationFailedWhileGuestMassUpdateArticle(): void
    {
        $articles = Article::factory()->count(3)->for($user = $this->createImprovisedUser())->create();

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
                // 'Authorization' => 'Bearer '.$user->generateApiToken(),
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
        $articles = Article::factory()->count(3)->for($user = $this->createImprovisedUser())->create();

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

        $articles = Article::factory()->count($articlesCount)->for($user = $this->createImprovisedUser())->create();

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
     * Set the currently logged in user for the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string|null  $guard
     * @return $this
     */
    public function actingAs(UserContract $user, $abilities = [])
    {
        Sanctum::actingAs($user, $abilities);

        return $this;
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
                // 'Authorization' => 'Bearer '.$owner->generateApiToken(),
            ]);
    }
}
