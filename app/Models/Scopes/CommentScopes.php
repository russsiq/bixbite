<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @method static static|EloquentBuilder|QueryBuilder approved(bool $isApproved = true)
 */
trait CommentScopes
{
    /**
     * Scope a query to only include approved / unapproved comments.
     *
     * @param  EloquentBuilder  $builder
     * @param  boolean  $isApproved
     * @return EloquentBuilder
     */
    public function scopeApproved(EloquentBuilder $builder, bool $isApproved = true): EloquentBuilder
    {
        return $builder->where('comments.is_approved', $isApproved);
    }
}
