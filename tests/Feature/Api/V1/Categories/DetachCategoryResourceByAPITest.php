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
 * @cmd vendor/bin/phpunit Tests\Feature\Api\V1\Categories\DetachCategoryResourceByAPITest.php
 */
class DetachCategoryResourceByAPITest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers ::destroy
     * @cmd vendor\bin\phpunit --filter '::test_super_admin_can_detach_category_to_article'
     */
    public function test_super_admin_can_detach_category_to_article()
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
            ->deleteJson(route('api.categoryable.destroy', [
                'categoryable_type' => Article::TABLE,
                'categoryable_id' => $article->id,
                'category_id' => $category->id,
            ]))
            // ->dump()
            ->assertStatus(JsonResponse::HTTP_ACCEPTED);

        $this->assertDatabaseCount('categoryables', 2);
        $this->assertDatabaseMissing('categoryables', [
            'categoryable_type' => Article::TABLE,
            'categoryable_id' => $article->id,
            'category_id' => $category->id,
        ]);
    }
}
