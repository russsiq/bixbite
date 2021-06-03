<?php

namespace App\Contracts\Actions\Category;

interface DetachesCategory
{
    /**
     * Validate and detach the category.
     *
     * @param  string  $categoryable_type
     * @param  integer  $categoryable_id
     * @param  integer  $category_id
     * @return void
     */
    public function detach(string $categoryable_type, int $categoryable_id, int $category_id): void;
}
