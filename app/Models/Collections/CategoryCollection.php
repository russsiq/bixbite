<?php

namespace App\Models\Collections;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryCollection extends Collection
{
    public function nested(array $related = []): Collection
    {
        $collection = $this;

        return $collection->transform(function (Category $category) use ($collection) {

            // Appends children categories if exists.
            if ($collection->firstWhere('parent_id', $category->id)) {
                $category->children = $collection->where('parent_id', $category->id);
                    // ->sortBy('position', SORT_NATURAL);
            }

            return $category;

        })->filter(function (Category $category) {

            // Return only root category.
            return empty($category->parent_id);

        });
    }
}
