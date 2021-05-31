<?php

namespace App\Models\Observers;

use App\Models\Category;

class CategoryObserver
{
    public function creating(Category $category): void
    {
        $category->position = Category::max('position') + 1;
    }

    /**
     * Обработать событие `deleting` модели.
     * @param  Category  $category
     * @return void
     */
    public function deleting(Category $category): void
    {
        $category->articles()
            ->select([
                'articles.id',
                'articles.state',
            ])
            ->update([
                'articles.state' => 0,
            ]);

        $category->articles()->detach();
    }

    public function deleted(Category $category)
    {
        Category::where('position', '>', $category->position)
            ->decrement('position');
    }
}
