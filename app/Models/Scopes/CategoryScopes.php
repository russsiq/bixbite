<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @method static static|EloquentBuilder|QueryBuilder excludeExternal() Scope a query to exclude internal links by `alt_url` attribute.
 * @method static static|EloquentBuilder|QueryBuilder includeExternal() Scope a query to include also external links by `alt_url` attribute.
 * @method static static|EloquentBuilder|QueryBuilder orderByPosition() Order categories by position.
 * @method static static|EloquentBuilder|QueryBuilder short() Scope a query to include only short categories.
 * @method static static|EloquentBuilder|QueryBuilder showInMenu() Scope a query to include only visible in the menu categories.
 */
trait CategoryScopes
{
    /**
     * Scope a query to exclude internal links by `alt_url` attribute.
     *
     * @param  EloquentBuilder  $builder
     * @return EloquentBuilder
     */
    public function scopeExcludeExternal(EloquentBuilder $builder): EloquentBuilder
    {
        return $builder->whereNull('categories.alt_url');
    }

    /**
     * Scope a query to include also external links by `alt_url` attribute.
     *
     * @param  EloquentBuilder  $builder
     * @return EloquentBuilder
     */
    public function scopeIncludeExternal(EloquentBuilder $builder): EloquentBuilder
    {
        return $builder->addSelect('categories.alt_url');
    }

    /**
     * Order categories by position.
     *
     * Used in `\App\Http\Controllers\SiteController`.
     *
     * @param  EloquentBuilder  $builder
     * @return EloquentBuilder
     */
    public function scopeOrderByPosition(EloquentBuilder $builder): EloquentBuilder
    {
        return $builder->orderByRaw('ISNULL(`position`), `position` ASC');
    }

    /**
     * Scope a query to include only short categories.
     *
     * @param  EloquentBuilder  $builder
     * @return EloquentBuilder
     */
    public function scopeShort(EloquentBuilder $builder): EloquentBuilder
    {
        return $builder->addSelect([
            'categories.id',
            'categories.parent_id',
            'categories.title',
            'categories.slug',
            'categories.show_in_menu',
        ]);
    }

    /**
     * Scope a query to include only visible in the menu categories.
     *
     * @param  EloquentBuilder  $builder
     * @return EloquentBuilder
     */
    public function scopeShowInMenu(EloquentBuilder $builder): EloquentBuilder
    {
        return $builder->where('show_in_menu', true);
    }
}
