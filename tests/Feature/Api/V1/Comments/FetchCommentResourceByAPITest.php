<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Comments;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Policies\CommentPolicy;
use Database\Seeders\ArticlesTableSeeder;
use Database\Seeders\TestContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\Fixtures\CommentFixtures;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\CommentsController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Comments\FetchCommentResourceByAPITest.php
 */
class FetchCommentResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = Comment::TABLE;

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_fetch_comments'
     */
    public function test_guest_cannot_fetch_comments()
    {
        $response = $this->assertGuest()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_fetch_comments'
     */
    public function test_user_without_ability_cannot_fetch_comments()
    {
        $this->denyPolicyAbility(CommentPolicy::class, ['viewAny']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_fetch_comments'
     */
    public function test_user_with_ability_can_fetch_comments()
    {
        $this->allowPolicyAbility(CommentPolicy::class, ['viewAny']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_fetch_comments'
     */
    public function test_super_admin_can_fetch_comments()
    {
        $super_admin = $this->loginSuperAdminSPA();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_fetch_specific_comment'
     */
    public function test_guest_cannot_fetch_specific_comment()
    {
        $user = $this->createUser();
        $article = Article::factory()->for($user)->createOne();
        $comment = Comment::factory()->for($article, 'commentable')->createOne();

        $response = $this->assertGuest()
            ->getJsonApi('show', $comment)
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_fetch_specific_comment'
     */
    public function test_user_without_ability_cannot_fetch_specific_comment()
    {
        $this->denyPolicyAbility(CommentPolicy::class, ['view']);

        $user = $this->loginSPA();
        $article = Article::factory()->for($user)->createOne();
        $comment = Comment::factory()->for($article, 'commentable')->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $comment)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_fetch_specific_comment'
     */
    public function test_user_with_ability_can_fetch_specific_comment()
    {
        $this->allowPolicyAbility(CommentPolicy::class, ['view']);

        $user = $this->loginSPA();
        $article = Article::factory()->for($user)->createOne();
        $comment = Comment::factory()->for($article, 'commentable')->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $comment)
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_fetch_specific_comment'
     */
    public function test_super_admin_can_fetch_specific_comment()
    {
        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $article = Article::factory()->for($user)->createOne();
        $comment = Comment::factory()->for($article, 'commentable')->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $comment)
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @covers ::index
     * @cmd vendor\bin\phpunit --filter '::test_each_received_comment_contains_required_fields'
     */
    public function test_each_received_comment_contains_required_fields()
    {
        $this->allowPolicyAbility(CommentPolicy::class, ['viewAny']);

        $user = $this->loginSPA();
        $article = Article::factory()->for($user)->createOne();
        $commentsCount = mt_rand(4, 12);
        $comments = Comment::factory($commentsCount)->for($article, 'commentable')->create();

        $response = $this->assertAuthenticated()
            ->getJsonApi('index')
            ->assertStatus(JsonResponse::HTTP_PARTIAL_CONTENT)
            ->assertJsonCount($commentsCount, 'data')
            ->assertJsonStructure(
                CommentFixtures::collection()
            );
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_single_received_comment_contains_required_fields'
     */
    public function test_single_received_comment_contains_required_fields()
    {
        $this->allowPolicyAbility(CommentPolicy::class, ['view']);

        $user = $this->loginSPA();
        $article = Article::factory()->for($user)->createOne();
        $comment = Comment::factory()->for($article, 'commentable')->createOne();

        $response = $this->assertAuthenticated()
            ->getJsonApi('show', $comment)
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure(
                CommentFixtures::resource()
            );
    }

    /**
     * @covers ::show
     * @cmd vendor\bin\phpunit --filter '::test_not_found_when_attempt_to_fetch_single_non_existent_comment'
     */
    public function test_not_found_when_attempt_to_fetch_single_non_existent_comment()
    {
        $user = $this->loginSPA();

        $response = $this->assertDatabaseCount('comments', 0)
            ->assertAuthenticated()
            ->getJsonApi('show', 888)
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
