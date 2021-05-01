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
     * @return Builder
     */
    public function scopeShort(Builder $builder): Builder
    {
        return $builder->addSelect([
            'categories.id',
            'categories.title',
            'categories.slug',
            'categories.parent_id',
            'categories.show_in_menu',

        ]);
    }

    /**
     * [scopeIncludeExternal description]
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeIncludeExternal(Builder $builder): Builder
    {
        return $builder->addSelect('categories.alt_url');
    }

    /**
     * [scopeExcludeExternal description]
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeExcludeExternal(Builder $builder): Builder
    {
        return $builder->whereNull('categories.alt_url');
    }

    /**
     * [scopeShowInMenu description]
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeShowInMenu(Builder $builder): Builder
    {
        return $builder->where('show_in_menu', true);
    }

    /**
     * [scopeOrderByPosition description]
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeOrderByPosition(Builder $builder): Builder
    {
        return $builder->orderByRaw('ISNULL(`position`), `position` ASC');
    }
}
