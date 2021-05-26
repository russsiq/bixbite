<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Notes;

use App\Models\Note;
use App\Policies\NotePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\Fixtures\NoteFixtures;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\NotesController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Notes\CreateNoteResourceByAPITest.php
 */
class CreateNoteResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = Note::TABLE;

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_create_note'
     */
    public function test_guest_cannot_create_note()
    {
        $response = $this->assertGuest()
            ->postJsonApi('store')
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_create_note'
     */
    public function test_user_without_ability_cannot_create_note()
    {
        $this->denyPolicyAbility(NotePolicy::class, ['create']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store')
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_cannot_create_note_without_minimal_provided_data'
     */
    public function test_user_with_ability_cannot_create_note_without_minimal_provided_data()
    {
        $this->allowPolicyAbility(NotePolicy::class, ['create']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store')
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_create_note_with_minimal_provided_data'
     */
    public function test_user_with_ability_can_create_note_with_minimal_provided_data()
    {
        $this->assertDatabaseCount(Note::TABLE, 0);

        $this->allowPolicyAbility(NotePolicy::class, ['create']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store', [], [
                'title' => 'Laravel'
            ])
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonPath('data.title', 'Laravel')
            ->assertJsonStructure(
                NoteFixtures::resource()
            );

        $this->assertDatabaseHas(Note::TABLE, [
            'title' => 'Laravel',
        ]);
    }

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_create_note_with_minimal_provided_data'
     */
    public function test_super_admin_can_create_note_with_minimal_provided_data()
    {
        $this->assertDatabaseCount(Note::TABLE, 0);

        $super_admin = $this->loginSuperAdminSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store', [], [
                'title' => 'Laravel'
            ])
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonPath('data.title', 'Laravel')
            ->assertJsonStructure(
                NoteFixtures::resource()
            );

        $this->assertDatabaseHas(Note::TABLE, [
            'title' => 'Laravel',
        ]);
    }
}
