<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Notes;

use App\Models\Note;
use App\Policies\NotePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\NotesController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Notes\DeleteNoteResourceByAPITest.php
 */
class DeleteNoteResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = Note::TABLE;

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_delete_note'
     */
    public function test_guest_cannot_delete_note()
    {
        $user = $this->createUser();
        $note = Note::factory()->for($user)->createOne();

        $response = $this->assertGuest()
            ->deleteJsonApi('destroy', $note)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_delete_note'
     */
    public function test_user_without_ability_cannot_delete_note()
    {
        $this->denyPolicyAbility(NotePolicy::class, ['delete']);

        $user = $this->loginSPA();
        $note = Note::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $note)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_delete_note'
     */
    public function test_user_with_ability_can_delete_note()
    {
        $this->allowPolicyAbility(NotePolicy::class, ['delete']);

        $user = $this->loginSPA();
        $note = Note::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $note)
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing(Note::TABLE, [
            'id' => $note->id,
        ]);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_delete_note'
     */
    public function test_super_admin_can_delete_note()
    {
        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $note = Note::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $note)
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing(Note::TABLE, [
            'id' => $note->id,
        ]);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_delete_non_existent_note'
     */
    public function test_not_found_when_attempt_to_delete_non_existent_note()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseMissing(Note::TABLE, [
                'id' => 888,
            ])
            ->assertAuthenticated()
            ->deleteJsonApi('destroy', 888)
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
