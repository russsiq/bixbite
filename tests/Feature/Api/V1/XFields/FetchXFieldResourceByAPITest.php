<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\XFields;

use App\Models\XField;
use App\Policies\XFieldPolicy;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\Fixtures\XFieldFixtures;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\XFieldsController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\XFields\FetchXFieldResourceByAPITest.php
 *
 * @see CreateXFieldResourceByAPITest
 */
class FetchXFieldResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use DatabaseMigrations;

    public const JSON_API_PREFIX = XField::TABLE;

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_fetch_x_fields'
     */
    public function test_guest_cannot_fetch_x_fields()
    {
        $response = $this->assertGuest()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_fetch_x_fields'
     */
    public function test_user_without_ability_cannot_fetch_x_fields()
    {
        $this->denyPolicyAbility(XFieldPolicy::class, ['viewAny']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_fetch_x_fields'
     */
    public function test_user_with_ability_can_fetch_x_fields()
    {
        $this->allowPolicyAbility(XFieldPolicy::class, ['viewAny']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_fetch_x_fields'
     */
    public function test_super_admin_can_fetch_x_fields()
    {
        $super_admin = $this->loginSuperAdminSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_fetch_specific_x_field'
     */
    public function test_guest_cannot_fetch_specific_x_field()
    {
        Event::fake("eloquent.creating: ".XField::class);

        $user = $this->createUser();
        $x_field = XField::factory()->createOne();

        $response = $this->assertGuest()
            ->getJsonApi('show', $x_field)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_fetch_specific_x_field'
     */
    public function test_user_without_ability_cannot_fetch_specific_x_field()
    {
        Event::fake("eloquent.creating: ".XField::class);

        $this->denyPolicyAbility(XFieldPolicy::class, ['view']);

        $user = $this->loginSPA();
        $x_field = XField::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $x_field)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_fetch_specific_x_field'
     */
    public function test_user_with_ability_can_fetch_specific_x_field()
    {
        Event::fake("eloquent.creating: ".XField::class);

        $this->allowPolicyAbility(XFieldPolicy::class, ['view']);

        $user = $this->loginSPA();
        $x_field = XField::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $x_field)
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_fetch_specific_x_field'
     */
    public function test_super_admin_can_fetch_specific_x_field()
    {
        Event::fake("eloquent.creating: ".XField::class);

        $super_admin = $this->loginSuperAdminSPA();
        $x_field = XField::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $x_field)
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_each_received_x_field_contains_required_fields'
     */
    public function test_each_received_x_field_contains_required_fields()
    {
        $this->allowPolicyAbility(XFieldPolicy::class, ['viewAny']);

        $user = $this->loginSPA();
        $x_fieldsCount = mt_rand(2, 5);
        $x_fields = XField::factory($x_fieldsCount)->create();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT)
            ->assertJsonCount($x_fieldsCount, 'data')
            ->assertJsonStructure(
                XFieldFixtures::collection()
            );
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_single_received_x_field_contains_required_fields'
     */
    public function test_single_received_x_field_contains_required_fields()
    {
        $this->allowPolicyAbility(XFieldPolicy::class, ['view']);

        $user = $this->loginSPA();
        $x_field = XField::factory()->create();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $x_field)
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure(
                XFieldFixtures::resource()
            );
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_fetch_single_non_existent_x_field'
     */
    public function test_not_found_when_attempt_to_fetch_single_non_existent_x_field()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseMissing(XField::TABLE, [
                'id' => 888,
            ])
            ->assertAuthenticated()
            ->getJsonApi('show', 888)
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
