<?php

namespace App\Contracts\Actions\Comment;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface MassUpdatesComment
{
    /**
     * Validate and mass update specific comments.
     *
     * @param  array  $input
     * @return EloquentCollection
     */
    public function massUpdate(array $input): EloquentCollection;
}
