<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Articles;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Тестирование получения ресурса `Article`.
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Articles\FetchArticleResourceByAPITest.php
 */
class FetchArticleResourceByAPITest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_fetch_articles()
    {
        $response = $this->assertGuest()
            ->getJson(route('api.articles.index'))
            ->assertUnauthorized();
    }

    public function test_user_can_fetch_articles()
    {
        Sanctum::actingAs(
            $user = User::factory()->create()
        );

        $response = $this->assertAuthenticated()
            ->getJson(route('api.articles.index'))
            ->assertOk();
    }
}
