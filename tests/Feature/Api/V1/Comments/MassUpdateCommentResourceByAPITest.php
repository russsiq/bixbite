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
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Concerns\InteractsWithPolicy;
use Tests\Concerns\JsonApiTrait;
use Tests\Fixtures\CommentFixtures;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\CommentsController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Comments\MassUpdateCommentResourceByAPITest.php
 */
class MassUpdateCommentResourceByAPITest extends TestCase
{
    use JsonApiTrait;
    use InteractsWithPolicy;
    use RefreshDatabase;

    public const JSON_API_PREFIX = 'comments';

    /**
     * @covers ::massUpdate
     * @cmd vendor\bin\phpunit --filter '::test_guest_cannot_mass_update_comment'
     */
    public function test_guest_cannot_mass_update_comment()
    {
        $user = $this->createUser();

        $response = $this->assertGuest()
            ->putJsonApi('massUpdate')
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @covers ::massUpdate
     * @cmd vendor\bin\phpunit --filter '::test_user_without_ability_cannot_mass_update_comment'
     */
    public function test_user_without_ability_cannot_mass_update_comment()
    {
        $this->denyPolicyAbility(CommentPolicy::class, ['massUpdate']);

        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->putJsonApi('massUpdate', [], [])
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @covers ::massUpdate
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_cannot_mass_update_comment_without_minimal_provided_data'
     */
    public function test_user_with_ability_cannot_mass_update_comment_without_minimal_provided_data()
    {
        $this->allowPolicyAbility(CommentPolicy::class, ['massUpdate']);

        $user = $this->loginSPA();
        $article = Article::factory()->for($user)->createOne();
        $commentsCount = mt_rand(4, 12);
        $comments = Comment::factory($commentsCount)->for($article, 'commentable')->create();

        $this->assertAuthenticated()
            ->putJsonApi('massUpdate', [], [])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'comments', 'mass_action',
            ]);
    }

    /**
     * @covers ::massUpdate
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_cannot_mass_update_comment_without_minimal_provided_data'
     */
    public function test_super_admin_cannot_mass_update_comment_without_minimal_provided_data()
    {
        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $article = Article::factory()->for($user)->createOne();
        $commentsCount = mt_rand(2, 5);
        $comments = Comment::factory($commentsCount)->for($user)
            ->for($article, 'commentable')
            ->unApproved()
            ->create();

        $this->assertAuthenticated();

        // Супер-админ сайта не указал никаких данных.
        $this->putJsonApi('massUpdate', [], [])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'comments', 'mass_action',
            ]);

        // Супер-админ сайта не указал, что изменять в записях.
        $this->putJsonApi('massUpdate', [], [
                'comments' => $comments->modelKeys()
            ])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('mass_action');

        // Супер-админ сайта не указал какие записи необходимо обновить.
        $this->putJsonApi('massUpdate', [], [
                'mass_action' => 'approved'
            ])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('comments');
    }

    /**
     * @covers ::massUpdate
     * @cmd vendor\bin\phpunit --filter '::test_user_with_ability_can_mass_update_comment_with_minimal_provided_data'
     */
    public function test_user_with_ability_can_mass_update_comment_with_minimal_provided_data()
    {
        $this->allowPolicyAbility(CommentPolicy::class, ['massUpdate']);

        $user = $this->loginSPA();
        $article = Article::factory()->for($user)->createOne();
        $commentsCount = mt_rand(2, 5);
        $comments = Comment::factory($commentsCount)->for($user)
            ->for($article, 'commentable')
            ->unApproved()
            ->create();

        $response = $this->assertAuthenticated()
            ->putJsonApi('massUpdate', [], [
                'comments' => $comments->modelKeys(),
                'mass_action' => 'approved',
            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonCount($commentsCount, 'data')
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', $commentsCount, fn (AssertableJson $json) =>
                    $json->where('id', $comments->first()->value('id'))
                        ->where('is_approved', true)
                        ->etc()
                )
            )
            ->assertJsonStructure([
                'data' => [
                    '*' => CommentFixtures::resource()['data']
                ]
            ]);
    }

    /**
     * @covers ::massUpdate
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_mass_update_comment_with_minimal_provided_data'
     */
    public function test_super_admin_can_mass_update_comment_with_minimal_provided_data()
    {
        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $article = Article::factory()->for($user)->createOne();
        $commentsCount = mt_rand(2, 5);
        $comments = Comment::factory($commentsCount)->for($user)
            ->for($article, 'commentable')
            ->unApproved()
            ->create();

        $response = $this->assertAuthenticated()
            ->putJsonApi('massUpdate', [], [
                'comments' => $comments->modelKeys(),
                'mass_action' => 'approved',
            ])
            ->assertStatus(JsonResponse::HTTP_ACCEPTED)
            ->assertJsonCount($commentsCount, 'data')
            ->assertJsonPath('data.0.is_approved', true)
            ->assertJsonStructure([
                'data' => [
                    '*' => CommentFixtures::resource()['data']
                ]
            ]);
    }
}
