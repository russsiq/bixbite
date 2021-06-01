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
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Tags\FetchTagResourceByAPITest.php
 */
class FetchTagResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = Tag::TABLE;

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_fetch_tags'
     */
    public function test_guest_cannot_fetch_tags()
    {
        $response = $this->assertGuest()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_fetch_tags'
     */
    public function test_user_without_ability_cannot_fetch_tags()
    {
        $this->denyPolicyAbility(TagPolicy::class, ['viewAny']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_fetch_tags'
     */
    public function test_user_with_ability_can_fetch_tags()
    {
        $this->allowPolicyAbility(TagPolicy::class, ['viewAny']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_fetch_tags'
     */
    public function test_super_admin_can_fetch_tags()
    {
        $super_admin = $this->loginSuperAdminSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_fetch_specific_tag'
     */
    public function test_guest_cannot_fetch_specific_tag()
    {
        $user = $this->createUser();
        $tag = Tag::factory()->createOne();

        $response = $this->assertGuest()
            ->getJsonApi('show', $tag)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_fetch_specific_tag'
     */
    public function test_user_without_ability_cannot_fetch_specific_tag()
    {
        $this->denyPolicyAbility(TagPolicy::class, ['view']);

        $user = $this->loginSPA();
        $tag = Tag::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $tag)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_fetch_specific_tag'
     */
    public function test_user_with_ability_can_fetch_specific_tag()
    {
        $this->allowPolicyAbility(TagPolicy::class, ['view']);

        $user = $this->loginSPA();
        $tag = Tag::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $tag)
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_fetch_specific_tag'
     */
    public function test_super_admin_can_fetch_specific_tag()
    {
        $super_admin = $this->loginSuperAdminSPA();
        $tag = Tag::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $tag)
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_each_received_tag_contains_required_fields'
     */
    public function test_each_received_tag_contains_required_fields()
    {
        $this->allowPolicyAbility(TagPolicy::class, ['viewAny']);

        $user = $this->loginSPA();
        $tagsCount = mt_rand(2, 5);
        $tags = Tag::factory($tagsCount)->create();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT)
            ->assertJsonCount($tagsCount, 'data')
            ->assertJsonStructure(
                TagFixtures::collection()
            );
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_single_received_tag_contains_required_fields'
     */
    public function test_single_received_tag_contains_required_fields()
    {
        $this->allowPolicyAbility(TagPolicy::class, ['view']);

        $user = $this->loginSPA();
        $tag = Tag::factory()->create();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $tag)
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure(
                TagFixtures::resource()
            );
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_fetch_single_non_existent_tag'
     */
    public function test_not_found_when_attempt_to_fetch_single_non_existent_tag()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseMissing(Tag::TABLE, [
                'id' => 888,
            ])
            ->assertAuthenticated()
            ->getJsonApi('show', 888)
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
