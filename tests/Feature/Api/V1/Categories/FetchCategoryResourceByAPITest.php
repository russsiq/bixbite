<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Categories;

use App\Models\Category;
use App\Models\User;
use App\Policies\CategoryPolicy;
use Database\Seeders\TestContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\Fixtures\CategoryFixtures;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\CategoriesController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Categories\FetchCategoryResourceByAPITest.php
 */
class FetchCategoryResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = 'categories';

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_fetch_categories'
     */
    public function test_guest_cannot_fetch_categories()
    {
        $response = $this->assertGuest()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_fetch_categories'
     */
    public function test_user_without_ability_cannot_fetch_categories()
    {
        $this->denyPolicyAbility(CategoryPolicy::class, ['viewAny']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_fetch_categories'
     */
    public function test_user_with_ability_can_fetch_categories()
    {
        $this->allowPolicyAbility(CategoryPolicy::class, ['viewAny']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_fetch_categories'
     */
    public function test_super_admin_can_fetch_categories()
    {
        $super_admin = $this->loginSuperAdminSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_fetch_specific_category'
     */
    public function test_guest_cannot_fetch_specific_category()
    {
        $user = $this->createUser();
        $category = Category::factory()->createOne();

        $response = $this->assertGuest()
            ->getJsonApi('show', $category)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_fetch_specific_category'
     */
    public function test_user_without_ability_cannot_fetch_specific_category()
    {
        $this->denyPolicyAbility(CategoryPolicy::class, ['view']);

        $user = $this->loginSPA();
        $category = Category::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $category)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_fetch_specific_category'
     */
    public function test_user_with_ability_can_fetch_specific_category()
    {
        $this->allowPolicyAbility(CategoryPolicy::class, ['view']);

        $user = $this->loginSPA();
        $category = Category::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $category)
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_fetch_specific_category'
     */
    public function test_super_admin_can_fetch_specific_category()
    {
        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $category = Category::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $category)
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_each_received_category_contains_required_fields'
     */
    public function test_each_received_category_contains_required_fields()
    {
        $this->allowPolicyAbility(CategoryPolicy::class, ['viewAny']);

        $user = $this->loginSPA();
        $categoriesCount = mt_rand(4, 12);
        $categories = Category::factory($categoriesCount)->create();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT)
            ->assertJsonCount($categoriesCount, 'data')
            ->assertJsonStructure(
                CategoryFixtures::collection()
            );
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_single_received_category_contains_required_fields'
     */
    public function test_single_received_category_contains_required_fields()
    {
        $this->allowPolicyAbility(CategoryPolicy::class, ['view']);

        $user = $this->loginSPA();
        $category = Category::factory()->create();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $category)
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure(
                CategoryFixtures::resource()
            );
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_fetch_single_non_existent_category'
     */
    public function test_not_found_when_attempt_to_fetch_single_non_existent_category()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseCount('categories', 0)
            ->assertAuthenticated()
            ->getJsonApi('show', 888)
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
