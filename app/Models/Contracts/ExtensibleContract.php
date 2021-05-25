<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;

/**
 * @property-read Collection $x_fields
 * @method static static|EloquentBuilder|QueryBuilder includeExtensibleAttributes()
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
     * @param  EloquentBuilder  $builder
     * @return void
     */
    public function scopeIncludeExtensibleAttributes(EloquentBuilder $builder): void;
}
