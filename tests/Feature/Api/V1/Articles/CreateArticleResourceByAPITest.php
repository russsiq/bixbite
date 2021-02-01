<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Articles;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
            ->assertUnauthorized();
    }

    public function test_user_can_create_article()
    {
        Sanctum::actingAs(
            $user = User::factory()->create()
        );

        $response = $this->assertAuthenticated()
            ->postJson(route('api.articles.store'), [

            ])
            ->assertCreated();
    }
}
