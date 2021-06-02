<?php

namespace App\Models\Relations;

use App\Models\Category;
use App\Models\Contracts\CategoryableContract;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property-read EloquentCollection|Category[] $categories Get all of the categories for the current model.
 * @property-read Category $category Get the main / first category for the current model.
 */
trait CategoryableTrait
{
    /**
     * Boot the Categoryable trait for a model.
     *
     * @return void
     */
    public static function bootCategoryableTrait(): void
    {
        static::registerModelEvent('booted', static function (CategoryableContract $categoryable) {
            $relation = (string) $categoryable->getTable();

            Category::resolveRelationUsing(
                $relation,
                fn (Category $categoryModel): MorphToMany => $categoryModel->morphedByMany(
                    $categoryable::class,   // $related
                    'categoryable',         // $name
                    'categoryables',        // $table
                    'category_id',          // $foreignPivotKey
                    'categoryable_id',      // $relatedPivotKey
                )
            );
        });

        static::deleting(function (CategoryableContract $categoryable) {
            $categoryable->categories()->detach();
        });
    }

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
