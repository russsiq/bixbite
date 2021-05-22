<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\XFields;

use App\Models\User;
use App\Models\XField;
use App\Policies\XFieldPolicy;
use Illuminate\Foundation\Testing\DatabaseMigrations;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\Fixtures\XFieldFixtures;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\XFieldsController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\XFields\UpdateXFieldResourceByAPITest.php
 *
 * @see CreateXFieldResourceByAPITest
 */
class UpdateXFieldResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use WithFaker;
    use DatabaseMigrations;

    public const JSON_API_PREFIX = XField::TABLE;

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_update_x_field'
     */
    public function test_guest_cannot_update_x_field()
    {
        $user = $this->createUser();
        $x_field = XField::factory()->createOne();

        $response = $this->assertGuest()
            ->putJsonApi('update', $x_field)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_update_x_field'
     */
    public function test_user_without_ability_cannot_update_x_field()
    {
        $this->denyPolicyAbility(XFieldPolicy::class, ['update']);

        $user = $this->loginSPA();
        $x_field = XField::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $x_field)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_cannot_update_x_field_without_minimal_provided_data'
     */
    public function test_user_with_ability_cannot_update_x_field_without_minimal_provided_data()
    {
        $this->allowPolicyAbility(XFieldPolicy::class, ['update']);

        $user = $this->loginSPA();
        $x_field = XField::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $x_field)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_update_x_field_with_minimal_provided_data'
     */
    public function test_user_with_ability_can_update_x_field_with_minimal_provided_data()
    {
        $this->allowPolicyAbility(XFieldPolicy::class, ['update']);

        $user = $this->loginSPA();
        $x_field = XField::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $x_field, [
                'title' => 'New title for old extra field',
            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonPath('data.title', 'New title for old extra field');
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_update_x_field_with_minimal_provided_data'
     */
    public function test_super_admin_can_update_x_field_with_minimal_provided_data()
    {
        $this->assertDatabaseCount(XField::TABLE, 0);

        $super_admin = $this->loginSuperAdminSPA();
        $x_field = XField::factory()
            ->withSpecifiedExtensible('articles')
            ->withSpecifiedType('string')
            ->createOne([
                'title' => 'Title for extra field',
            ]);

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $x_field, [
                'title' => 'New title for old extra field',
            ])
            // ->dump()
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonPath('data.title', 'New title for old extra field')
            ->assertJsonStructure(
                XFieldFixtures::resource()
            );

        $this->assertDatabaseHas(XField::TABLE, [
            'title' => 'New title for old extra field',
        ]);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_update_non_existent_x_field'
     */
    public function test_not_found_when_attempt_to_update_non_existent_x_field()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseMissing(XField::TABLE, [
                'id' => 888,
            ])
            ->assertAuthenticated()
            ->putJsonApi('update', 888)
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
