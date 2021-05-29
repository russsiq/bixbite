<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @method static static|EloquentBuilder|QueryBuilder searchByKeyword(?string $keyword) Scope a query to include only relevant by keywords tags.
 */
trait TagScopes
{
    /**
     * Scope a query to include only relevant by keywords tags.
     *
     * @param  EloquentBuilder  $builder
     * @param  string|null  $keyword
     * @return EloquentBuilder
     */
    public function scopeSearchByKeyword(EloquentBuilder $builder, ?string $keyword): EloquentBuilder
    {
        return $builder->when($keyword, function(EloquentBuilder $builder, string $keyword) {
            return $builder->where('title', 'like', '%'.$keyword.'%');
        });
    }
}
