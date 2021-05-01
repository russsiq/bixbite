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
            // Appending children of category.
            if ($collection->firstWhere('parent_id', $category->id)) {
                $category->children = $collection->where('parent_id', $category->id);
            }

            return $category;
        })
        // Return only root category.
        ->filter(fn (Category $category) => $category->is_root);
    }
}
