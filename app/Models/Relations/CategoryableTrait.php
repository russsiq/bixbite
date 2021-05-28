<?php

namespace App\Models\Relations;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property-read EloquentCollection|Category[] $categories Get all of the categories for the current model.
 * @property-read Category $category Get the main / first category for the current model.
 */
trait CategoryableTrait
{
    /**
     * Get all of the categories for the current model.
     *
     * @return MorphToMany
     */
    public function categories(): MorphToMany
    {
        return $this->morphToMany(
                Category::class,    // $related
                'categoryable',     // $name
                'categoryables',    // $table
                'categoryable_id',  // $foreignPivotKey
                'category_id',      // $relatedPivotKey
                'id',               // $parentKey
                'id',               // $relatedKey
            )
            ->withPivot('is_main');
    }

    /**
     * Get the main / first category for the current model.
     *
     * @return Category
     */
    public function getCategoryAttribute(): Category
    {
        return $this->categories->where('is_main', true)->first()
            ?? $this->categories->first();
    }
}
