<?php

namespace Tests\Feature\App\Http\Controllers\Api\V1;

// Тестируемый класс.
use App\Http\Controllers\Api\V1\ArticlesController;

// Сторонние зависимости.
use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
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
     * Собственник сайта может просмотреть пустой список записей.
     * @return void
     */
    public function testOwnerCanListingArticlesWithoutArticles(): void
    {
        $this->actingAs($user = $this->createImprovisedUser('owner'))
            ->getJson(route('api.articles.index'), [
                'Authorization' => 'Bearer '.$user->generateApiToken(),
            ])
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);
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
