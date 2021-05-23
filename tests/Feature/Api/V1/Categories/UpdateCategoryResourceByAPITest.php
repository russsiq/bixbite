<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Categories;

use App\Models\Category;
use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\Fixtures\CategoryFixtures;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\CategoriesController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Categories\UpdateCategoryResourceByAPITest.php
 */
class UpdateCategoryResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = Category::TABLE;

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_update_category'
     */
    public function test_guest_cannot_update_category()
    {
        $user = $this->createUser();
        $category = Category::factory()->createOne();

        $response = $this->assertGuest()
            ->putJsonApi('update', $category)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_update_category'
     */
    public function test_user_without_ability_cannot_update_category()
    {
        $this->denyPolicyAbility(CategoryPolicy::class, ['update']);

        $user = $this->loginSPA();
        $category = Category::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $category)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_cannot_update_category_without_minimal_provided_data'
     *
     * @NB  Ничего общего с ситуацией автосохранения – **нет**.
     */
    public function test_user_with_ability_cannot_update_category_without_minimal_provided_data()
    {
        $this->allowPolicyAbility(CategoryPolicy::class, ['update']);

        $user = $this->loginSPA();
        $category = Category::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $category)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_update_category_with_minimal_provided_data'
     */
    public function test_user_with_ability_can_update_category_with_minimal_provided_data()
    {
        $this->allowPolicyAbility(CategoryPolicy::class, ['update']);

        $user = $this->loginSPA();
        $category = Category::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $category, [
                'title' => 'New title for old category',
            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonPath('data.title', 'New title for old category')
            ->assertJsonStructure(
                CategoryFixtures::resource()
            );

        $this->assertDatabaseHas(Category::TABLE, [
            'title' => 'New title for old category',
        ]);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_update_category_with_minimal_provided_data'
     */
    public function test_super_admin_can_update_category_with_minimal_provided_data()
    {
        $this->assertDatabaseCount(Category::TABLE, 0);

        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $category = Category::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $category, [
                'title' => 'New title for old category',
            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonPath('data.title', 'New title for old category')
            ->assertJsonStructure(
                CategoryFixtures::resource()
            );

        $this->assertDatabaseHas(Category::TABLE, [
            'title' => 'New title for old category',
        ]);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_update_non_existent_category'
     */
    public function test_not_found_when_attempt_to_update_non_existent_category()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseMissing(Category::TABLE, [
                'id' => 888,
            ])
            ->assertAuthenticated()
            ->putJsonApi('update', 888)
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
