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
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Categories\SyncCategoryResourceByAPITest.php
 */
class SyncCategoryResourceByAPITest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers ::update
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_sync_category_to_article'
     */
    public function test_super_admin_can_sync_category_to_article()
    {
        $this->assertDatabaseCount(Article::TABLE, 0);
        $this->assertDatabaseCount(Category::TABLE, 0);
        $this->assertDatabaseCount('categoryables', 0);

        $super_admin = $this->loginSuperAdminSPA();
        $user = $this->createUser();
        $article = Article::factory()->for($user)/*->hasCategories(Category::factory()->count(3))*/->createOne();
        $categories = Category::factory()->count(3)->create();
        $category = $categories->random();

        $article->categories()->attach($categories);

        $this->assertDatabaseCount(Article::TABLE, 1);
        $this->assertDatabaseCount(Category::TABLE, 3);
        $this->assertDatabaseCount('categoryables', 3);

        $response = $this->assertAuthenticated()
            ->putJson(route('api.categoryable.update', [
                'categoryable_type' => Article::TABLE,
                'categoryable_id' => $article->id,
                'categories' => [
                    [
                        'category_id' => $category->id,
                        'is_main' => true,
                    ]
                ],
            ]))
            // ->dump()
            ->assertStatus(JsonResponse::HTTP_ACCEPTED);

        $this->assertDatabaseCount('categoryables', 1);
        $this->assertDatabaseHas('categoryables', [
            'categoryable_type' => Article::TABLE,
            'categoryable_id' => $article->id,
            'category_id' => $category->id,
            'is_main' => true,
        ]);
    }
}
