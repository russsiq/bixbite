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
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Tags\UpdateTagResourceByAPITest.php
 */
class UpdateTagResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = Tag::TABLE;

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_update_tag'
     */
    public function test_guest_cannot_update_tag()
    {
        $tag = Tag::factory()->createOne();

        $response = $this->assertGuest()
            ->putJsonApi('update', $tag)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_update_tag'
     */
    public function test_user_without_ability_cannot_update_tag()
    {
        $this->denyPolicyAbility(TagPolicy::class, ['update']);

        $user = $this->loginSPA();
        $tag = Tag::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $tag)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_cannot_update_tag_without_minimal_provided_data'
     */
    public function test_user_with_ability_cannot_update_tag_without_minimal_provided_data()
    {
        $this->allowPolicyAbility(TagPolicy::class, ['update']);

        $user = $this->loginSPA();
        $tag = Tag::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $tag)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_update_tag_with_minimal_provided_data'
     */
    public function test_user_with_ability_can_update_tag_with_minimal_provided_data()
    {
        $this->allowPolicyAbility(TagPolicy::class, ['update']);

        $user = $this->loginSPA();
        $tag = Tag::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $tag, [
                'title' => 'New title for old tag',
            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonPath('data.title', 'New title for old tag')
            ->assertJsonStructure(
                TagFixtures::resource()
            );

        $this->assertDatabaseHas(Tag::TABLE, [
            'title' => 'New title for old tag',
        ]);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_update_tag_with_minimal_provided_data'
     */
    public function test_super_admin_can_update_tag_with_minimal_provided_data()
    {
        $this->assertDatabaseCount(Tag::TABLE, 0);

        $super_admin = $this->loginSuperAdminSPA();
        $tag = Tag::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $tag, [
                'title' => 'New title for old tag',
            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonPath('data.title', 'New title for old tag')
            ->assertJsonStructure(
                TagFixtures::resource()
            );

        $this->assertDatabaseHas(Tag::TABLE, [
            'title' => 'New title for old tag',
        ]);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_update_non_existent_tag'
     */
    public function test_not_found_when_attempt_to_update_non_existent_tag()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseMissing(Tag::TABLE, [
                'id' => 888,
            ])
            ->assertAuthenticated()
            ->putJsonApi('update', 888)
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
