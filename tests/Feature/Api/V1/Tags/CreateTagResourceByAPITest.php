<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Tags;

use App\Models\Tag;
use App\Policies\TagPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\Fixtures\TagFixtures;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\TagsController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Tags\CreateTagResourceByAPITest.php
 */
class CreateTagResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = Tag::TABLE;

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_create_tag'
     */
    public function test_guest_cannot_create_tag()
    {
        $response = $this->assertGuest()
            ->postJsonApi('store')
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_create_tag'
     */
    public function test_user_without_ability_cannot_create_tag()
    {
        $this->denyPolicyAbility(TagPolicy::class, ['create']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store')
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_cannot_create_tag_without_minimal_provided_data'
     */
    public function test_user_with_ability_cannot_create_tag_without_minimal_provided_data()
    {
        $this->allowPolicyAbility(TagPolicy::class, ['create']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store')
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_create_tag_with_minimal_provided_data'
     */
    public function test_user_with_ability_can_create_tag_with_minimal_provided_data()
    {
        $this->assertDatabaseCount(Tag::TABLE, 0);

        $this->allowPolicyAbility(TagPolicy::class, ['create']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store', [], [
                'title' => 'Laravel'
            ])
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonPath('data.title', 'Laravel')
            ->assertJsonStructure(
                TagFixtures::resource()
            );

        $this->assertDatabaseHas(Tag::TABLE, [
            'title' => 'Laravel',
        ]);
    }

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_create_tag_with_minimal_provided_data'
     */
    public function test_super_admin_can_create_tag_with_minimal_provided_data()
    {
        $this->assertDatabaseCount(Tag::TABLE, 0);

        $super_admin = $this->loginSuperAdminSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store', [], [
                'title' => 'Laravel'
            ])
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonPath('data.title', 'Laravel')
            ->assertJsonStructure(
                TagFixtures::resource()
            );

        $this->assertDatabaseHas(Tag::TABLE, [
            'title' => 'Laravel',
        ]);
    }
}
