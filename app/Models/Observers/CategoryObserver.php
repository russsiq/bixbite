<?php

namespace App\Models\Observers;

use App\Models\Article;
use App\Models\Category;

class CategoryObserver
{
    /**
     * Handle the Category "creating" event.
     *
     * @param  Category  $category
     * @return void
     */
    public function creating(Category $category): void
    {
        $category->position = Category::max('position') + 1;
    }

    /**
     * Handle the Category "deleting" event.
     *
     * @param  Category  $category
     * @return void
     */
    public function deleting(Category $category): void
    {
        $category->articles()
            ->update([
                'articles.state' => Article::STATE['draft'],
            ]);

        $category->articles()->detach();
    }

    /**
     * Handle the Category "deleted" event.
     *
     * @param  Category  $category
     * @return void
     */
    public function deleted(Category $category): void
    {
        Category::where('position', '>', $category->position)
            ->decrement('position');
    }
}
