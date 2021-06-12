<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Comments;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Policies\CommentPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\CommentsController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Comments\DeleteCommentResourceByAPITest.php
 */
class DeleteCommentResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = Comment::TABLE;

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_delete_comment'
     */
    public function test_guest_cannot_delete_comment()
    {
        $user = $this->createUser();
        $article = Article::factory()->for($user)->createOne();
        $comment = Comment::factory()->for($article, 'commentable')->createOne();

        $response = $this->assertGuest()
            ->deleteJsonApi('destroy', $comment)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_delete_comment'
     */
    public function test_user_without_ability_cannot_delete_comment()
    {
        $this->denyPolicyAbility(CommentPolicy::class, ['delete']);

        $user = $this->loginSPA();
        $article = Article::factory()->for($user)->createOne();
        $comment = Comment::factory()->for($article, 'commentable')->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $comment)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_delete_comment'
     */
    public function test_user_with_ability_can_delete_comment()
    {
        $this->allowPolicyAbility(CommentPolicy::class, ['delete']);

        $user = $this->loginSPA();
        $article = Article::factory()->for($user)->createOne();
        $comment = Comment::factory()->for($article, 'commentable')->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $comment)
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_delete_comment'
     */
    public function test_super_admin_can_delete_comment()
    {
        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $article = Article::factory()->for($user)->createOne();
        $comment = Comment::factory()->for($user, 'author')->for($article, 'commentable')->createOne();

        $response = $this->assertAuthenticated()
            ->deleteJsonApi('destroy', $comment)
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_delete_non_existent_comment'
     */
    public function test_not_found_when_attempt_to_delete_non_existent_comment()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseCount('comments', 0)
            ->assertAuthenticated()
            ->deleteJsonApi('destroy', '888')
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
