<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Attachments;

use App\Models\Article;
use App\Models\Attachment;
use App\Policies\AttachmentPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\Fixtures\AttachmentFixtures;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\AttachmentsController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Attachments\CreateAttachmentResourceByAPITest.php
 */
class CreateAttachmentResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = Attachment::TABLE;

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_create_attachment'
     */
    public function test_guest_cannot_create_attachment()
    {
        $response = $this->assertGuest()
            ->postJsonApi('store')
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_create_attachment'
     */
    public function test_user_without_ability_cannot_create_attachment()
    {
        $this->denyPolicyAbility(AttachmentPolicy::class, ['create']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store')
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_cannot_create_attachment_without_minimal_provided_data'
     */
    public function test_user_with_ability_cannot_create_attachment_without_minimal_provided_data()
    {
        $this->allowPolicyAbility(AttachmentPolicy::class, ['create']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->postJsonApi('store')
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_create_attachment_to_article_with_minimal_provided_data'
     */
    public function test_super_admin_can_create_attachment_to_article_with_minimal_provided_data()
    {
        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $article = Article::factory()->for($user)->createOne();

        Storage::fake($disk = 'public');

        $uploadedFile = UploadedFile::fake()->image('simple_attachment.jpg', $width = 1024, $height = 768);

        $data = [
            Attachment::UPLOADED_FILE => $uploadedFile,
            'attachable_type' => $article->getTable(),
            'attachable_id' => $article->id,
        ];

        $expected = array_diff_key(array_merge($data, [
            'extension' => 'jpeg',
            'properties' => [],
        ]), [
            Attachment::UPLOADED_FILE => null,
        ]);

        $this->assertDatabaseCount(Attachment::TABLE, 0);

        $response = $this->assertAuthenticated()
            ->postJsonApi('store', [], $data)
            // ->dump()
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure(AttachmentFixtures::resource())
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', fn (AssertableJson $json) =>
                    $json->where('id', 1)
                        ->where('extension', $expected['extension'])
                        ->etc()
                )
            );

        $this->assertDatabaseHas(Attachment::TABLE, $expected);

        $data = (object) $response['data'];

        Storage::disk($disk)->assertExists([
            $data->path,
            "{$data->type}/{$data->folder}/thumb/{$data->name}.{$data->extension}",
            "{$data->type}/{$data->folder}/small/{$data->name}.{$data->extension}",
        ]);

        Storage::disk($disk)->assertMissing([
            "{$data->type}/{$data->folder}/medium/{$data->name}.{$data->extension}",
        ]);
    }
}
