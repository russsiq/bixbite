<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface TaggableContract
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

    public static function bootTaggableTrait(): void;

    public function tags(): MorphToMany;
}
