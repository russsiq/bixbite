<?php

declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers;

use App\Contracts\Responses\SuccessfulCommentCreateResponseContract;
use App\Http\Controllers\ArticleCommentController;
use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Tests\TestCase;
use \Illuminate\Testing\TestResponse;

/**
 * @coversDefaultClass \App\Http\Controllers\ArticleCommentController
 *
 * @cmd vendor\bin\phpunit tests\Feature\App\Http\Controllers\ArticleCommentControllerTest.php
 */
class ArticleCommentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers ::__invoke
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_comment_article_with_minimal_provided_data'
     */
    public function test_super_admin_can_comment_article_with_minimal_provided_data(): void
    {
        $super_admin = $this->loginSuperAdmin();
        $user = $this->createUser();
        $article = Article::factory()->for($user)->createOne();

        $response = $this->assertAuthenticated()
            ->post(route('articles.comments.store', $article), [
                'content' => 'Класс `Illuminate\Testing\TestResponse` содержит множество своих методов утверждения.'
            ])->assertRedirect();

        $response->assertSessionHasNoErrors();
        $response->assertSessionHasAll([
            'status' => SuccessfulCommentCreateResponseContract::STATUSES[1],
        ]);
    }
}
