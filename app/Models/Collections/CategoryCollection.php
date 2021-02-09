<?php

namespace App\Models\Collections;

use Illuminate\Database\Eloquent\Collection;

class CategoryCollection extends Collection
{
    public function treated(bool $nested = false, array $related = [])
    {
        $collection = $this;

        return $collection->transform(function ($category) use ($collection, $nested, $related) {
            // Formatting of a category tree, if this need.
            if ($nested and $collection->firstWhere('parent_id', $category->id)) {
                $category->children = $collection->where('parent_id', $category->id);
            }

            return $category;
        })
        ->when($nested, function($collection) {
            // Finish stage formatting of a category tree, return only root category.
            return $collection->reject(function ($category) {
                return $category->parent_id !== null and $category->parent_id !== 0;
            });
        });
    }

    public function nested()
    {
        return $this->treated(true);
    }
}
