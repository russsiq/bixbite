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
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\XFieldsController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\XFields\DeleteXFieldResourceByAPITest.php
 *
 * @see CreateXFieldResourceByAPITest
 */
class DeleteXFieldResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use DatabaseMigrations;

    public const JSON_API_PREFIX = XField::TABLE;

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_delete_x_field'
     */
    public function test_guest_cannot_delete_x_field()
    {
        Event::fake("eloquent.creating: ".XField::class);

        $x_field = XField::factory()->createOne();

        $response = $this->assertGuest()
            ->deleteJsonApi('destroy', $x_field)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_delete_x_field'
     */
    public function test_user_without_ability_cannot_delete_x_field()
    {
        Event::fake("eloquent.creating: ".XField::class);

        $this->denyPolicyAbility(XFieldPolicy::class, ['delete']);

        $user = $this->loginSPA();
        $x_field = XField::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $x_field)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_delete_x_field'
     */
    public function test_user_with_ability_can_delete_x_field()
    {
        $this->allowPolicyAbility(XFieldPolicy::class, ['delete']);

        $user = $this->loginSPA();
        $x_field = XField::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $x_field)
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_delete_x_field'
     */
    public function test_super_admin_can_delete_x_field()
    {
        $super_admin = $this->loginSuperAdminSPA();
        $x_field = XField::factory()->createOne();

        $this->assertDatabaseHas(XField::TABLE, [
            'id' => $x_field->id,
        ]);

        $this->assertTrue(
            $this->getConnection()
                ->getSchemaBuilder()
                ->hasColumn($x_field->extensible, $x_field->name)
        );

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $x_field)
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing(XField::TABLE, [
            'id' => $x_field->id,
        ]);

        $this->assertTrue(
            ! $this->getConnection()
                ->getSchemaBuilder()
                ->hasColumn($x_field->extensible, $x_field->name)
        );
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_delete_non_existent_x_field'
     */
    public function test_not_found_when_attempt_to_delete_non_existent_x_field()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseMissing(XField::TABLE, [
                'id' => 888,
            ])
            ->assertAuthenticated()
            ->deleteJsonApi('destroy', 888)
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
