<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Attachments;

use App\Models\Attachment;
use App\Policies\AttachmentPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\Fixtures\AttachmentFixtures;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\AttachmentsController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Attachments\FetchAttachmentResourceByAPITest.php
 */
class FetchAttachmentResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = Attachment::TABLE;

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_fetch_attachments'
     */
    public function test_guest_cannot_fetch_attachments()
    {
        $response = $this->assertGuest()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_fetch_attachments'
     */
    public function test_user_without_ability_cannot_fetch_attachments()
    {
        $this->denyPolicyAbility(AttachmentPolicy::class, ['viewAny']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_fetch_attachments'
     */
    public function test_user_with_ability_can_fetch_attachments()
    {
        $this->allowPolicyAbility(AttachmentPolicy::class, ['viewAny']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_fetch_attachments'
     */
    public function test_super_admin_can_fetch_attachments()
    {
        $super_admin = $this->loginSuperAdminSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_fetch_specific_attachment'
     */
    public function test_guest_cannot_fetch_specific_attachment()
    {
        $attachment = Attachment::factory()->imageOnPublicDisk()->createOne();

        $response = $this->assertGuest()
            ->getJsonApi('show', $attachment)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_fetch_specific_attachment'
     */
    public function test_user_without_ability_cannot_fetch_specific_attachment()
    {
        $this->denyPolicyAbility(AttachmentPolicy::class, ['view']);

        $user = $this->loginSPA();
        $attachment = Attachment::factory()->imageOnPublicDisk()->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $attachment)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_fetch_specific_attachment'
     */
    public function test_user_with_ability_can_fetch_specific_attachment()
    {
        $this->allowPolicyAbility(AttachmentPolicy::class, ['view']);

        $user = $this->loginSPA();
        $attachment = Attachment::factory()->imageOnPublicDisk()->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $attachment)
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_fetch_specific_attachment'
     */
    public function test_super_admin_can_fetch_specific_attachment()
    {
        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $attachment = Attachment::factory()->imageOnPublicDisk()->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $attachment)
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_each_received_attachment_contains_required_fields'
     */
    public function test_each_received_attachment_contains_required_fields()
    {
        $this->allowPolicyAbility(AttachmentPolicy::class, ['viewAny']);

        $user = $this->loginSPA();
        $attachmentsCount = mt_rand(4, 12);
        $attachments = Attachment::factory($attachmentsCount)->imageOnPublicDisk()->create();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT)
            ->assertJsonCount($attachmentsCount, 'data')
            ->assertJsonStructure(
                AttachmentFixtures::collection()
            );
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_single_received_attachment_contains_required_fields'
     */
    public function test_single_received_attachment_contains_required_fields()
    {
        $this->allowPolicyAbility(AttachmentPolicy::class, ['view']);

        $user = $this->loginSPA();
        $attachment = Attachment::factory()->imageOnPublicDisk()->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $attachment)
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure(
                AttachmentFixtures::resource()
            );
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_fetch_single_non_existent_attachment'
     */
    public function test_not_found_when_attempt_to_fetch_single_non_existent_attachment()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseMissing(Attachment::TABLE, [
                'id' => 888,
            ])
            ->assertAuthenticated()
            ->getJsonApi('show', 888)
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
