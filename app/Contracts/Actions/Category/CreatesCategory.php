<?php

namespace App\Contracts\Actions\Category;

use App\Models\Category;

interface CreatesCategory
{
    /**
     * Validate and create a newly category.
     *
     * @param  array  $input
     * @return Category
     */
    public function create(array $input): Category;
}
