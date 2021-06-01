<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Tags;

use App\Models\Tag;
use App\Policies\TagPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\TagsController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Tags\DeleteTagResourceByAPITest.php
 */
class DeleteTagResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = Tag::TABLE;

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_delete_tag'
     */
    public function test_guest_cannot_delete_tag()
    {
        $tag = Tag::factory()->createOne();

        $response = $this->assertGuest()
            ->deleteJsonApi('destroy', $tag)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_delete_tag'
     */
    public function test_user_without_ability_cannot_delete_tag()
    {
        $this->denyPolicyAbility(TagPolicy::class, ['delete']);

        $user = $this->loginSPA();
        $tag = Tag::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $tag)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_delete_tag'
     */
    public function test_user_with_ability_can_delete_tag()
    {
        $this->allowPolicyAbility(TagPolicy::class, ['delete']);

        $user = $this->loginSPA();
        $tag = Tag::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $tag)
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing(Tag::TABLE, [
            'id' => $tag->id,
        ]);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_delete_tag'
     */
    public function test_super_admin_can_delete_tag()
    {
        $super_admin = $this->loginSuperAdminSPA();
        $tag = Tag::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $tag)
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing(Tag::TABLE, [
            'id' => $tag->id,
        ]);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_delete_non_existent_tag'
     */
    public function test_not_found_when_attempt_to_delete_non_existent_tag()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseMissing(Tag::TABLE, [
                'id' => 888,
            ])
            ->assertAuthenticated()
            ->deleteJsonApi('destroy', 888)
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
