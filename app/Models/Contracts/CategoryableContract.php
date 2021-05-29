<?php

namespace App\Models\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface CategoryableContract
{
    /**
     * Get the table associated with the model.
     *
     * @return string
     *
     * @ignore Because Eloquent models do not have a public methods interface.
     * @see \Illuminate\Database\Eloquent\Model::getTable()
     */
    public function getTable();

    public static function bootCategoryableTrait(): void;

    public function categories(): MorphToMany;

    public function getCategoryAttribute(): Category;
}
