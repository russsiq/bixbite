<?php

// NOT USED. https://github.com/laravel/docs/commit/524527dee52330e19a3b94737713d7a64b22f0de

namespace BBCMS\Models\Scopes;

use BBCMS\Models\XField;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ExtensibleScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        // $builder->addSelect(
        //     XField::query()->where('extensible', $model->getTable())
        //         ->get()->pluck('name')->all()
        // );
    }
}
