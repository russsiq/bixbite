<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Tags;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\TaggableController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Tags\AttachTagResourceByAPITest.php
 */
class AttachTagResourceByAPITest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_attach_tag_to_article'
     */
    public function test_super_admin_can_attach_tag_to_article()
    {
        $this->assertDatabaseCount(Article::TABLE, 0);
        $this->assertDatabaseCount(Tag::TABLE, 0);
        $this->assertDatabaseCount('taggables', 0);

        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $article = Article::factory()->for($user)->createOne();
        $tag = Tag::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->postJson(route('api.taggable.store', [
                'taggable_type' => Article::TABLE,
                'taggable_id' => $article->id,
                'tag_id' => $tag->id,
            ]))
            // ->dump()
            ->assertStatus(JsonResponse::HTTP_ACCEPTED);

        $this->assertDatabaseHas('taggables', [
            'taggable_type' => Article::TABLE,
            'taggable_id' => $article->id,
            'tag_id' => $tag->id,
        ]);
    }
}
