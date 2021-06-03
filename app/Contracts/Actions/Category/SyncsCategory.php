<?php

namespace App\Contracts\Actions\Category;

interface SyncsCategory
{
    /**
     * Validate and sync the categories associations.
     *
     * @param  string  $categoryable_type
     * @param  integer  $categoryable_id
     * @param  array  $input
     * @return void
     */
    public function sync(string $categoryable_type, int $categoryable_id, array $input = []): void;
}
