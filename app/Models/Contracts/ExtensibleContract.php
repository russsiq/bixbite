<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property-read Collection $x_fields
 * @method-read static static|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder includeXFieldsNames()
 */
interface ExtensibleContract
{
    /**
     * Get the value of the dynamic attribute `x_fields`.
     *
     * @return Collection
     */
    public function getXFieldsAttribute(): Collection;

    /**
     * Include names of extra fields in the query.
     *
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeIncludeXFieldsNames(Builder $builder): Builder;
}
