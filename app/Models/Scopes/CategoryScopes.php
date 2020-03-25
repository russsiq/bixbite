<?php

namespace App\Models\Scopes;

// Сторонние зависимости.
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

trait CategoryScopes
{
    /**
     * [scopeShort description]
     * @param  Builder  $builder
     * @return void
     */
    public function scopeShort(Builder $builder): void
    {
        $builder->addSelect([
            'categories.id',
            'categories.title',
            'categories.slug',
            'categories.alt_url',
            'categories.parent_id',
            'categories.show_in_menu',

        ]);
    }

    /**
     * [scopeShowInMenu description]
     * @param  Builder  $builder
     * @return void
     */
    public function scopeShowInMenu(Builder $builder): void
    {
        $builder->where('show_in_menu', true);
    }

    /**
     * [scopeOrderByPosition description]
     * @param  Builder  $builder
     * @return void
     */
    public function scopeOrderByPosition(Builder $builder): void
    {
        $builder->orderByRaw('ISNULL(`position`), `position` ASC');
    }
}
