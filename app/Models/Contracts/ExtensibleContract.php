<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface ExtensibleContract
{
    /**
     * Determine whether an attribute should be cast to a native type.
     *
     * @param  string  $key
     * @param  array|string|null  $types
     * @return bool
     *
     * @ignore Because Eloquent models do not have a public methods interface.
     * @see \Illuminate\Database\Eloquent\Concerns\HasAttributes::hasCast($key, $types = null)
     */
    public function hasCast($key, $types = null);

    public function getXFieldsAttribute(): EloquentCollection;

    public function scopeIncludeExtensibleAttributes(EloquentBuilder $builder): EloquentBuilder;
}
