<?php

namespace App\Contracts\Actions\Category;

use App\Models\Category;

interface DeletesCategory
{
    /**
     * Delete the given category.
     *
     * @param  Category  $category
     * @return int  Remote category ID.
     */
    public function delete(Category $category): int;
}
