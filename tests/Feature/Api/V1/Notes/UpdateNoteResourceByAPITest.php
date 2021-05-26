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
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Notes\UpdateNoteResourceByAPITest.php
 */
class UpdateNoteResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = Note::TABLE;

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_update_note'
     */
    public function test_guest_cannot_update_note()
    {
        $user = $this->createUser();
        $note = Note::factory()->for($user)->createOne();

        $response = $this->assertGuest()
            ->putJsonApi('update', $note)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_update_note'
     */
    public function test_user_without_ability_cannot_update_note()
    {
        $this->denyPolicyAbility(NotePolicy::class, ['update']);

        $user = $this->loginSPA();
        $note = Note::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $note)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_cannot_update_note_without_minimal_provided_data'
     */
    public function test_user_with_ability_cannot_update_note_without_minimal_provided_data()
    {
        $this->allowPolicyAbility(NotePolicy::class, ['update']);

        $user = $this->loginSPA();
        $note = Note::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $note)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_update_note_with_minimal_provided_data'
     */
    public function test_user_with_ability_can_update_note_with_minimal_provided_data()
    {
        $this->allowPolicyAbility(NotePolicy::class, ['update']);

        $user = $this->loginSPA();
        $note = Note::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $note, [
                'title' => 'New title for old note',
            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonPath('data.title', 'New title for old note')
            ->assertJsonStructure(
                NoteFixtures::resource()
            );

        $this->assertDatabaseHas(Note::TABLE, [
            'title' => 'New title for old note',
        ]);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_update_note_with_minimal_provided_data'
     */
    public function test_super_admin_can_update_note_with_minimal_provided_data()
    {
        $this->assertDatabaseCount(Note::TABLE, 0);

        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $note = Note::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $note, [
                'title' => 'New title for old note',
            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonPath('data.title', 'New title for old note')
            ->assertJsonStructure(
                NoteFixtures::resource()
            );

        $this->assertDatabaseHas(Note::TABLE, [
            'title' => 'New title for old note',
        ]);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_update_non_existent_note'
     */
    public function test_not_found_when_attempt_to_update_non_existent_note()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseMissing(Note::TABLE, [
                'id' => 888,
            ])
            ->assertAuthenticated()
            ->putJsonApi('update', 888)
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
