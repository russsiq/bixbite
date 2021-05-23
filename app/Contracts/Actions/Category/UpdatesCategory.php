<?php

namespace App\Contracts\Actions\Category;

use App\Models\Category;

interface UpdatesCategory
{
    /**
     * Validate and update the given category.
     *
     * @param  Category  $category
     * @param  array  $input
     * @return Category
     */
    public function update(Category $category, array $input): Category;
}
