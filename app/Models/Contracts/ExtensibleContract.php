<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface ExtensibleContract
{
    public function getXFieldsAttribute(): EloquentCollection;

    public function scopeIncludeExtensibleAttributes(EloquentBuilder $builder): EloquentBuilder;
}
