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
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Tags\DetachTagResourceByAPITest.php
 */
class DetachTagResourceByAPITest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_detach_tag_to_article'
     */
    public function test_super_admin_can_detach_tag_to_article()
    {
        $this->assertDatabaseCount(Article::TABLE, 0);
        $this->assertDatabaseCount(Tag::TABLE, 0);
        $this->assertDatabaseCount('taggables', 0);

        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $article = Article::factory()->for($user)/*->hasTags(Tag::factory()->count(3))*/->createOne();
        $tags = Tag::factory()->count(3)->create();
        $tag = $tags->random();

        $article->tags()->attach($tags);

        $this->assertDatabaseCount(Article::TABLE, 1);
        $this->assertDatabaseCount(Tag::TABLE, 3);
        $this->assertDatabaseCount('taggables', 3);

        $response = $this->assertAuthenticated()
            ->deleteJson(route('api.taggable.destroy', [
                'taggable_type' => Article::TABLE,
                'taggable_id' => $article->id,
                'tag_id' => $tag->id,
            ]))
            // ->dump()
            ->assertStatus(JsonResponse::HTTP_ACCEPTED);

        $this->assertDatabaseCount('taggables', 2);
        $this->assertDatabaseMissing('taggables', [
            'taggable_type' => Article::TABLE,
            'taggable_id' => $article->id,
            'tag_id' => $tag->id,
        ]);
    }
}
