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
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Notes\FetchNoteResourceByAPITest.php
 */
class FetchNoteResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = Note::TABLE;

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_fetch_notes'
     */
    public function test_guest_cannot_fetch_notes()
    {
        $response = $this->assertGuest()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_fetch_notes'
     */
    public function test_user_without_ability_cannot_fetch_notes()
    {
        $this->denyPolicyAbility(NotePolicy::class, ['viewAny']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_fetch_notes'
     */
    public function test_user_with_ability_can_fetch_notes()
    {
        $this->allowPolicyAbility(NotePolicy::class, ['viewAny']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_fetch_notes'
     */
    public function test_super_admin_can_fetch_notes()
    {
        $super_admin = $this->loginSuperAdminSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_fetch_specific_note'
     */
    public function test_guest_cannot_fetch_specific_note()
    {
        $user = $this->createUser();
        $note = Note::factory()->for($user)->createOne();

        $response = $this->assertGuest()
            ->getJsonApi('show', $note)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_fetch_specific_note'
     */
    public function test_user_without_ability_cannot_fetch_specific_note()
    {
        $this->denyPolicyAbility(NotePolicy::class, ['view']);

        $user = $this->loginSPA();
        $note = Note::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $note)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_fetch_specific_note'
     */
    public function test_user_with_ability_can_fetch_specific_note()
    {
        $this->allowPolicyAbility(NotePolicy::class, ['view']);

        $user = $this->loginSPA();
        $note = Note::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $note)
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_fetch_specific_note'
     */
    public function test_super_admin_can_fetch_specific_note()
    {
        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $note = Note::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $note)
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_each_received_note_contains_required_fields'
     */
    public function test_each_received_note_contains_required_fields()
    {
        $this->allowPolicyAbility(NotePolicy::class, ['viewAny']);

        $user = $this->loginSPA();
        $notesCount = mt_rand(2, 5);
        $notes = Note::factory($notesCount)->for($user)->create();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT)
            ->assertJsonCount($notesCount, 'data')
            ->assertJsonStructure(
                NoteFixtures::collection()
            );
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_single_received_note_contains_required_fields'
     */
    public function test_single_received_note_contains_required_fields()
    {
        $this->allowPolicyAbility(NotePolicy::class, ['view']);

        $user = $this->loginSPA();
        $note = Note::factory()->for($user)->create();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $note)
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure(
                NoteFixtures::resource()
            );
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_fetch_single_non_existent_note'
     */
    public function test_not_found_when_attempt_to_fetch_single_non_existent_note()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseMissing(Note::TABLE, [
                'id' => 888,
            ])
            ->assertAuthenticated()
            ->getJsonApi('show', 888)
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
