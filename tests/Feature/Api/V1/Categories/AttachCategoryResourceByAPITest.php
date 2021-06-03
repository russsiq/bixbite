<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Categories;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\Api\V1\CategoryableController
 *
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Categories\AttachCategoryResourceByAPITest.php
 */
class AttachCategoryResourceByAPITest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers ::store
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_attach_category_to_article'
     */
    public function test_super_admin_can_attach_category_to_article()
    {
        $this->assertDatabaseCount(Article::TABLE, 0);
        $this->assertDatabaseCount(Category::TABLE, 0);
        $this->assertDatabaseCount('categoryables', 0);

        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $article = Article::factory()->for($user)->createOne();
        $category = Category::factory()->createOne();

        $response = $this->assertAuthenticated()
            ->postJson(route('api.categoryable.store', [
                'categoryable_type' => Article::TABLE,
                'categoryable_id' => $article->id,
                'category_id' => $category->id,
            ]))
            // ->dump()
            ->assertStatus(JsonResponse::HTTP_ACCEPTED);

        $this->assertDatabaseHas('categoryables', [
            'categoryable_type' => Article::TABLE,
            'categoryable_id' => $article->id,
            'category_id' => $category->id,
        ]);
    }
}
