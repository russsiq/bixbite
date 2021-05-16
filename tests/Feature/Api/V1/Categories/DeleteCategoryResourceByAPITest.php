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
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\CategoriesController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Categories\DeleteCategoryResourceByAPITest.php
 */
class DeleteCategoryResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = 'categories';

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_delete_category'
     */
    public function test_guest_cannot_delete_category()
    {
        $category = Category::factory()->createOne();

        $response = $this->assertGuest()
            ->deleteJsonApi('destroy', $category)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_delete_category'
     */
    public function test_user_without_ability_cannot_delete_category()
    {
        $this->denyPolicyAbility(CategoryPolicy::class, ['delete']);

        $user = $this->loginSPA();
        $category = Category::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $category)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_delete_category'
     */
    public function test_user_with_ability_can_delete_category()
    {
        $this->allowPolicyAbility(CategoryPolicy::class, ['delete']);

        $user = $this->loginSPA();
        $category = Category::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $category)
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_delete_category'
     */
    public function test_super_admin_can_delete_category()
    {
        $super_admin = $this->loginSuperAdminSPA();
        $category = Category::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $category)
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_delete_non_existent_category'
     */
    public function test_not_found_when_attempt_to_delete_non_existent_category()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseCount('categories', 0)
            ->assertAuthenticated()
            ->deleteJsonApi('destroy', 'not.found')
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
