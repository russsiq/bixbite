<?php

namespace App\Contracts\Actions\Article;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface MassUpdatesArticle
{
    /**
     * Validate and mass update specific articles.
     *
     * @param  array  $input
     * @return EloquentCollection
     */
    public function massUpdate(array $input): EloquentCollection;
}
