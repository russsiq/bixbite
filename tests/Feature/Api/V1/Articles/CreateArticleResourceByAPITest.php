<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Articles;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * Тестирование создания ресурса `Article`.
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Articles\CreateArticleResourceByAPITest.php
 */
class CreateArticleResourceByAPITest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_article()
    {
        $response = $this->assertGuest()
            ->postJson(route('api.articles.store'), [

            ])
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_create_article()
    {
        $this->withoutExceptionHandling();
        $user = $this->loginSPA();

        $response = $this->assertAuthenticated()
            ->postJson(route('api.articles.store'), [
                'title' => 'New article title',
            ])
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure([
                'links' => [
                    'self',
                ],
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'user_id',
                        'title', 'slug', 'teaser', 'content',
                        'meta_description', 'meta_keywords', 'meta_robots',
                        'on_mainpage', 'is_favorite', 'is_pinned',
                        'views',
                        'created_at', 'updated_at',
                    ],
                    'relationships',
                ],
            ]);
    }
}
