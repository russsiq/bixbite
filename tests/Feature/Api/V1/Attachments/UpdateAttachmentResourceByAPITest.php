<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Attachments;

use App\Models\Attachment;
use App\Policies\AttachmentPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\Fixtures\AttachmentFixtures;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\AttachmentsController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Attachments\UpdateAttachmentResourceByAPITest.php
 */
class UpdateAttachmentResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = Attachment::TABLE;

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_update_attachment'
     */
    public function test_guest_cannot_update_attachment()
    {
        $user = $this->createUser();
        $attachment = Attachment::factory()->imageOnPublicDisk()->createOne();

        $response = $this->assertGuest()
            ->putJsonApi('update', $attachment)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_update_attachment'
     */
    public function test_user_without_ability_cannot_update_attachment()
    {
        $this->denyPolicyAbility(AttachmentPolicy::class, ['update']);

        $user = $this->loginSPA();
        $attachment = Attachment::factory()->imageOnPublicDisk()->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $attachment)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_cannot_update_attachment_without_minimal_provided_data'
     */
    public function test_user_with_ability_cannot_update_attachment_without_minimal_provided_data()
    {
        $this->allowPolicyAbility(AttachmentPolicy::class, ['update']);

        $user = $this->loginSPA();
        $attachment = Attachment::factory()->imageOnPublicDisk()->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $attachment)
            // ->dump()
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_update_attachment_with_minimal_provided_data'
     */
    public function test_user_with_ability_can_update_attachment_with_minimal_provided_data()
    {
        $this->allowPolicyAbility(AttachmentPolicy::class, ['update']);

        $user = $this->loginSPA();
        $attachment = Attachment::factory()->imageOnPublicDisk()->createOne();

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $attachment, [
                'title' => 'New title for old attachment',
            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonPath('data.title', 'New title for old attachment');
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_update_attachment_with_minimal_provided_data'
     */
    public function test_super_admin_can_update_attachment_with_minimal_provided_data()
    {
        $this->assertDatabaseCount(Attachment::TABLE, 0);

        $super_admin = $this->loginSuperAdminSPA();
        $attachment = Attachment::factory()
            ->imageOnPublicDisk()
            ->createOne([
                'title' => 'Title for attachment',
            ]);

        Storage::disk($disk = 'public')->assertExists([
            $attachment->path,
            // "{$attachment->type}/{$attachment->folder}/thumb/{$attachment->name}.{$attachment->extension}",
            // "{$attachment->type}/{$attachment->folder}/small/{$attachment->name}.{$attachment->extension}",
        ]);

        $response = $this->assertAuthenticated()
            ->putJsonApi('update', $attachment, [
                'title' => 'New title for old attachment',
            ])
            // ->dump()
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonPath('data.title', 'New title for old attachment')
            ->assertJsonStructure(
                AttachmentFixtures::resource()
            );

        $this->assertDatabaseHas(Attachment::TABLE, [
            'title' => 'New title for old attachment',
        ]);

        $data = (object) $response['data'];

        Storage::disk($disk)->assertExists([
            $data->path,
            // "{$data->type}/{$data->folder}/thumb/{$data->name}.{$data->extension}",
            // "{$data->type}/{$data->folder}/small/{$data->name}.{$data->extension}",
        ]);

        Storage::disk($disk)->assertMissing([
            // "{$data->type}/{$data->folder}/medium/{$data->name}.{$data->extension}",
        ]);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_update_non_existent_attachment'
     */
    public function test_not_found_when_attempt_to_update_non_existent_attachment()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseMissing(Attachment::TABLE, [
                'id' => 888,
            ])
            ->assertAuthenticated()
            ->putJsonApi('update', 888)
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
