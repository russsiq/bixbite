<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Attachments;

use App\Models\Attachment;
use App\Policies\AttachmentPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\AttachmentsController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Attachments\DeleteAttachmentResourceByAPITest.php
 */
class DeleteAttachmentResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = Attachment::TABLE;

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_delete_attachment'
     */
    public function test_guest_cannot_delete_attachment()
    {
        $attachment = Attachment::factory()->imageOnPublicDisk()->createOne();

        $response = $this->assertGuest()
            ->deleteJsonApi('destroy', $attachment)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_delete_attachment'
     */
    public function test_user_without_ability_cannot_delete_attachment()
    {
        $this->denyPolicyAbility(AttachmentPolicy::class, ['delete']);

        $user = $this->loginSPA();
        $attachment = Attachment::factory()->imageOnPublicDisk()->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $attachment)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_delete_attachment'
     */
    public function test_user_with_ability_can_delete_attachment()
    {
        $this->allowPolicyAbility(AttachmentPolicy::class, ['delete']);

        $user = $this->loginSPA();
        $attachment = Attachment::factory()->imageOnPublicDisk()->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $attachment)
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('attachments', [
            'id' => $attachment->id,
        ]);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_delete_attachment'
     */
    public function test_super_admin_can_delete_attachment()
    {
        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $attachment = Attachment::factory()->imageOnPublicDisk()->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $attachment)
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('attachments', [
            'id' => $attachment->id,
        ]);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_delete_non_existent_attachment'
     */
    public function test_not_found_when_attempt_to_delete_non_existent_attachment()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseMissing(Attachment::TABLE, [
                'id' => 888,
            ])
            ->assertAuthenticated()
            ->deleteJsonApi('destroy', 888)
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
