<?php

namespace App\Models\Collections;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class CategoryCollection extends EloquentCollection
{
    public function nested(): EloquentCollection
    {
        $collection = $this;

        return $collection->map(function (Category $category) use ($collection) {
            // Appending child of category.
            if ($collection->firstWhere('parent_id', $category->id)) {
                $category->children = $collection->where('parent_id', $category->id);
            }

            return $category;
        })
        ->reject(function (Category $category) {
            // Return only root category.
            return $category->parent_id > 0;
        });
    }
}
