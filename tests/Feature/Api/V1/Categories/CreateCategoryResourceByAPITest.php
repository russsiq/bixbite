<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Categories;

use App\Models\Category;
use App\Models\User;
use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\Fixtures\CategoryFixtures;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\CategoriesController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Categories\CreateCategoryResourceByAPITest.php
 */
class CreateCategoryResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = 'categories';

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_create_category'
     */
    public function test_guest_cannot_create_category()
    {
        $response = $this->assertGuest()
            ->postJsonApi('store')
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_create_category'
     */
    public function test_user_without_ability_cannot_create_category()
    {
        $this->denyPolicyAbility(CategoryPolicy::class, ['create']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store')
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_cannot_create_category_without_minimal_provided_data'
     */
    public function test_user_with_ability_cannot_create_category_without_minimal_provided_data()
    {
        $this->allowPolicyAbility(CategoryPolicy::class, ['create']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store')
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_create_category_with_minimal_provided_data'
     */
    public function test_user_with_ability_can_create_category_with_minimal_provided_data()
    {
        $this->allowPolicyAbility(CategoryPolicy::class, ['create']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store', [], [
                'title' => 'Laravel'
            ])
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonPath('data.title', 'Laravel')
            ->assertJsonStructure(
                CategoryFixtures::resource()
            );

        $this->assertDatabaseHas('categories', [
            'title' => 'Laravel',
        ]);
    }

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_create_category_with_minimal_provided_data'
     */
    public function test_super_admin_can_create_category_with_minimal_provided_data()
    {
        $super_admin = $this->loginSuperAdminSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store', [], [
                'title' => 'Laravel'
            ])
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonPath('data.title', 'Laravel')
            ->assertJsonStructure(
                CategoryFixtures::resource()
            );

        $this->assertDatabaseHas('categories', [
            'title' => 'Laravel',
        ]);
    }
}
