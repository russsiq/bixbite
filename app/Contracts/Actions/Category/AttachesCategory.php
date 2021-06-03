<?php

namespace App\Contracts\Actions\Category;

interface AttachesCategory
{
    /**
     * Validate and attach the category.
     *
     * @param  string  $categoryable_type
     * @param  integer  $categoryable_id
     * @param  integer  $category_id
     * @return void
     */
    public function attach(string $categoryable_type, int $categoryable_id, int $category_id): void;
}
